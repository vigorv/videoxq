<?php
    App::import('Model', 'User');
	App::import('Model', 'Pay');

	define('_PAY_ROBOX_',	0);
	define('_PAY_SMSCOIN_',	1);
	define('_PAY_ASSIST_',	2);
	define('_PAY_W1_',		3);
	define('_PAY_ERBX_',	4);
	define('_PAY_PAYPAL_',	5);
	define('_PAY_STK_',		6);

class PaysController extends AppController
{
    public $name = 'Pays';
    public $uses = array('User', 'Pay', 'Useragreement');

    /**
     * Модель пользователей
     *
     * @var User
     */
    public $User;

    /**
     * Модель платежей (история платежей)
     *
     * @var Pay
     */
    public $Pay;

    private function payLog($methodName, $inv_id, $out_summ)
    {
		$fn = $_SERVER['DOCUMENT_ROOT'] . '/app/tmp/pay.log';
		$logText[] = date('d.m.y H:i:s');
		$logText[] = $methodName;
		$logText[] = $inv_id;
		$logText[] = number_format($out_summ, 2, ',', ' ');
		if ($f = fopen($fn, 'a+'))
		{
			fwrite($f, implode("\t", $logText) . "\r\n");
			fclose($f);
		}
    }

    /**
     * failURL
     *
     */
    public function failpay()
    {
		$mrh_pass1	= Configure::read('Robo.pass1'); // пароль 1

		// HTTP parameters:
		$out_summ	= (isset($_REQUEST["OutSum"])			? $_REQUEST["OutSum"] : 0); //сколько оплачено
		$inv_id		= (isset($_REQUEST["InvId"]) 			? intval($_REQUEST["InvId"]) : 0);;
		$crc		= (isset($_REQUEST["SignatureValue"])	? $_REQUEST["SignatureValue"] : 0); // HTTP parameters: $out_summ, $inv_id, $crc
		$crc = strtoupper($crc); // force uppercase
		// build own CRC
		$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1"));
		$success = false;

		$this->payLog("FailUrl (failpay)", $inv_id, $out_summ);

		if (strtoupper($my_crc) == strtoupper($crc))
		{
			$payData = $this->Pay->read(null, $inv_id);
			if (!empty($payData) && ($payData['Pay']['status'] == _PAY_WAIT_))
			{
				$payData['Pay']['status'] = _PAY_FAIL_;
				$payData['Pay']['paydate'] = time();
				$payData['Pay']['summ'] = $out_summ;
				$payData['Pay']['findate'] = time();
				$this->Pay->save($payData);
				$success = true;
			}
		}

		$this->set('success', $success);
    }

    /**
     * SuccesURL
     *
     */
    public function ok()
    {
    }

    /**
     * SuccesURL
     *
     */
    public function notsupport()
    {
    }

    /**
     * resultURL
     * обработчик ответа от платежной системы
     * меняет статус записи об оплате
     *
     */
    public function resultpay()
    {
    	$this->layout = 'ajax';
		// as a part of ResultURL script
		// your registration data
		$mrh_pass2	= Configure::read('Robo.pass2'); // пароль 2

		$perMonth	= Configure::read('costPerMonth');
		$perWeek	= Configure::read('costPerWeek');
		$perDay		= Configure::read('costPerDay');

		// HTTP parameters:
		$out_summ	= (isset($_REQUEST["OutSum"])			? $_REQUEST["OutSum"] : 0); //сколько оплачено
		$inv_id		= (isset($_REQUEST["InvId"]) 			? intval($_REQUEST["InvId"]) : 0);;
		$crc		= (isset($_REQUEST["SignatureValue"])	? $_REQUEST["SignatureValue"] : 0); // HTTP parameters: $out_summ, $inv_id, $crc
		$crc = strtoupper($crc); // force uppercase
		// build own CRC
		$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2"));
		$success = false;

		$this->payLog("ResultUrl (resultpay)", $inv_id, $out_summ);
		$this->payLog(serialize($_REQUEST), ';_POST', 0);

		if (strtoupper($my_crc) == strtoupper($crc))
		{
			$payData = $this->Pay->read(null, $inv_id);
			if (!empty($payData) && ($payData['Pay']['status'] == _PAY_WAIT_))
			{
				$payData['Pay']['status'] = _PAY_DONE_;
				$payData['Pay']['paydate'] = time();
				$payData['Pay']['summ'] = $out_summ;

				//ДАТУ "ПРОПЛАЧЕНО ПО" СЧИТАЕМ ОТ ПОСЛЕДНЕЙ ОПЛАЧЕННОЙ
				$months = 0;
				$weeks = 0;
				$days = 0;

				$months = intval($out_summ / $perMonth);
				$out_summ = $out_summ - $perMonth * $months;

				$weeks = intval($out_summ / $perWeek);
				$out_summ = $out_summ - $perWeek * $weeks;

				$days = intval($out_summ / $perDay);
				$out_summ = $out_summ - $perDay * $days;

				$secs = ($days + $weeks * 7 + $months * 31) * 24 * 60 * 60;

				$lastFinDate = $payData['Pay']['paydate'];
				$last = $this->Pay->find(array('Pay.user_id' => $payData['Pay']['user_id'], 'Pay.status' => _PAY_DONE_), null, 'Pay.findate desc');
				if (!empty($last))
				{
					if ($last['Pay']['findate'] > $lastFinDate)
						$lastFinDate = $last['Pay']['findate'];
				}
				$payData['Pay']['paydate'] = $lastFinDate;
				$payData['Pay']['findate'] = $lastFinDate + $secs;
				$this->Pay->save($payData);
				$success = true;
				$this->set('inv_id', $inv_id);

	   			$sql = 'delete from groups_users where user_id = ' . $payData['Pay']['user_id'] . ' and group_id = ' . Configure::read('VIPgroupId') . ';';
   	   			$this->Pay->query($sql);
	   			$sql= 'insert into groups_users (user_id, group_id) values(' . $payData['Pay']['user_id'] . ', ' . Configure::read('VIPgroupId') . ');';
   	   			$this->Pay->query($sql);

   				//корректируем VIP-группу форума (это сделает beforeSave при холостом обновлении)
   				$uInfo = array('User' => array('userid' => $payData['Pay']['user_id'], 'lastactivity' => time()));
   				$this->User->save($uInfo);

   	   			Configure::write('debug', 1);
   	   			$userInfo = $this->User->read(null, $payData['Pay']['user_id']);
		        $result = $this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
                /*to  */$userInfo['User']['username'] .
                '<' .
                $userInfo['User']['email'] .
                                     '>',
                /*subj*/Configure::read('App.siteName') . ' - ' . __('payment success', true),
                /*body*/__('Dear', true) . " " . __("User", true) . ", " . $userInfo['User']['username'] . ".\n" . __('Received payment from you. Amount', true) . " " . $payData['Pay']['summ'] . " wmr.\n\n" . __("Thank you", true) . ".\n" . Configure::read('App.siteName') . " Robot");
			}
		}
		$this->set('success', $success);
    }

    /**
     * вывод статистики по платежам
     */
    public function admin_stat($paysystem = 0, $period = 0, $step = 0)
    {
    	$this->set('step', $step);
    	switch ($period)
    	{
    		case 2: //ЗА ДЕНЬ
		    	$step = 3600 * 24 * $step;
		    	$start = mktime(0, 0, 0, date('m'), date('d'), date('Y')) + $step;
		    	$fin = mktime(0, 0, 0, date('m'), (date('d') + 1), date('Y')) - 1 + $step;
		    break;

    		case 1: //ЗА НЕДЕЛЮ (С ПОНЕДЕЛЬНИКА)
    			$w = date('w') - 1;
    			if ($w < 0) $w = 6;
    			$w = $w * 3600 * 24; //СКОЛЬКО НУЖНО ОТКРУТИТЬ ДО ПРОШЕДШЕГО ПОНЕДЕЛЬНИКА
		    	$step = 3600 * 24 * 7 * $step;

		    	$start = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - $w + $step;
		    	$fin = mktime(0, 0, 0, date('m'), (date('d') + 7), date('Y')) - 1 - $w + $step;
    		break;

    		default: //ЗА МЕСЯЦ
		    	$start = mktime(0, 0, 0, date('m') + $step, 1, date('Y'));
		    	$fin = mktime(0, 0, 0, date('m') + $step + 1, 1, date('Y')) - 1;
    	}
    	//ВЫБИРАЕМ ПЛАТЕЖИ ЗА ПЕРИОД
    	$lst = $this->Pay->findAll(array('Pay.paysystem' => $paysystem, 'Pay.created >=' . $start, 'Pay.created <=' . $fin));
    	$this->set('paysystem', $paysystem);
    	$this->set('period', $period);
    	$this->set('start', $start);
    	$this->set('fin', $fin);
    	$this->set('lst', $lst);

		$paysystemList = array();
		$paysystemList[_PAY_ROBOX_]['id'] = _PAY_ROBOX_;
		$paysystemList[_PAY_ROBOX_]['nm'] = "Робокасса";
		$paysystemList[_PAY_ROBOX_]['vl'] = Configure::read('Robo.currency');
		$paysystemList[_PAY_SMSCOIN_]['id'] = _PAY_SMSCOIN_;
		$paysystemList[_PAY_SMSCOIN_]['nm'] = "SMS coin";
		$paysystemList[_PAY_SMSCOIN_]['vl'] = Configure::read('Sms.currency');
		$paysystemList[_PAY_ASSIST_]['id'] = _PAY_ASSIST_;
		$paysystemList[_PAY_ASSIST_]['nm'] = "Assist";
		$paysystemList[_PAY_ASSIST_]['vl'] = Configure::read('Assist.currency');
    	$this->set('paysystemList', $paysystemList);

		$periodList = array();
		$periodList[0]['id'] = 0;
		$periodList[0]['nm'] = "месяц";
		$periodList[1]['id'] = 1;
		$periodList[1]['nm'] = "неделя";
		$periodList[2]['id'] = 2;
		$periodList[2]['nm'] = "день";
    	$this->set('periodList', $periodList);
    }

    /**
     * вывод истории платежей по юзерам
     */
    public function admin_index()
    {
    	/*
    	чистка платежей, которые в ожидании более часа (платежные системы не ждут долго)
    	*/
    	//$this->Pay->deleteAll(array('Pay.status' => _PAY_WAIT_, "Pay.created <" => (time() - 60*60)));
        //$pagination['Pay']['order'][] = ;

        $pagination['Pay']['page'] = (isset($this->passedArgs['page']) ? $this->passedArgs['page'] : 0);
        $pagination['Pay']['limit'] = 20;
//    	$lst = $this->Pay->paginate(array('Pay.status <>' => _PAY_WAIT_));
        $this->paginate = $pagination;
        $lst = $this->paginate($this->Pay);
    	$this->set('lst', $lst);
    }

    /**
     * редактирование информации о платеже
     *
     * @param integer $id
     */
    public function admin_edit($id = null)
    {
        if (!$id && empty($this->data))
        {
//            $this->Session->setFlash(__('Invalid Payment', true));
//            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data))
        {
        	$this->data['Pay']['findate'] = mktime(
        		$this->data['Pay']['findate']['hour'],
        		$this->data['Pay']['findate']['min'],
        		0,
        		$this->data['Pay']['findate']['month'],
        		$this->data['Pay']['findate']['day'],
        		$this->data['Pay']['findate']['year']
        	);
            if ($this->Pay->save($this->data))
            {
            	//ВНОСИМ В ГРУППУ ВИП
	   			$sql = 'delete from groups_users where user_id = ' . $this->data['Pay']['user_id'] . ' and group_id = ' . Configure::read('VIPgroupId') . ';';
   	   			$this->Pay->query($sql);
	   			$sql= 'insert into groups_users (user_id, group_id) values(' . $this->data['Pay']['user_id'] . ', ' . Configure::read('VIPgroupId') . ');';
   	   			$this->Pay->query($sql);

    			//корректируем VIP-группу форума (это сделает beforeSave при холостом обновлении)
   				$uInfo = array('User' => array('userid' => $this->data['Pay']['user_id'], 'lastactivity' => time()));
				$this->User->create();
   				$this->User->save($uInfo);

    			$this->Session->setFlash(__('The Payment has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Payment could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data))
        {
            $this->data = $this->Pay->read(null, $id);
        }
        $this->set("data", $this->data);
    }

	/**
	 * SMSCOIN the function returns an MD5 of parameters passed
	 *
	 * @return string
	 */
	function ref_sign() {
		$params = func_get_args();
		$prehash = implode("::", $params);
		return md5($prehash);
	}

    /**
     *
     * генерация ссылок на оплату через smscoin
     *
     * @param integer $summ
     */
	public function sms($summ = 0)
	{
		$summ = (float)($summ);
		if (empty($this->authUser['userid']))
			$summ = -1;//СЕРВИС ПРИОСТАНОВДЕН
		$perMonth	= Configure::read('Sms.costPerMonth');
		$this->set('perMonth', $perMonth);
		$perWeek	= Configure::read('Sms.costPerWeek');
		$this->set('perWeek', $perWeek);
		$perDay		= Configure::read('Sms.costPerDay');
		$this->set('perDay', $perDay);
		$payDesc = array(
			$perDay		=> Configure::read('descPerDay'),
			$perWeek	=> Configure::read('descPerWeek'),
			$perMonth	=> Configure::read('descPerMonth'),
		);
		$this->set('payDesc', $payDesc);

		if ($summ > 0)
		{
			if (empty($this->authUser['userid']))
			{
				$this->redirect('/users/login');
			}
	    	$allowDownload = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER['REMOTE_ADDR']);
	    	if (!$allowDownload)
	    	{
				//$this->redirect('/pays/notsupport');
	    	}

			$out_summ = $summ;
/*
			if ($out_summ < $perDay) $out_summ = $perDay;
			if (($out_summ > $perDay) && ($out_summ < $perWeek)) $out_summ = $perWeek;
			if ($out_summ > $perWeek) $out_summ = $perMonth;
*/

			$payData = array('Pay' => array(
					'user_id'	=> $this->authUser['userid'],
			));
			$payData['Pay']['created']	= time();
			$payData['Pay']['summ']		= $out_summ;
			$payData['Pay']['paysystem']= _PAY_SMSCOIN_;

			if ($this->Pay->save($payData))
			{
				$this->layout = 'ajax';
				// информация об оплате
				$payData['Pay']['id'] = $this->Pay->getLastInsertID();
				$order_id		= $payData['Pay']['id'];
				$secret_code	= Configure::read('Sms.secret_code');
				$purse			= Configure::read('Sms.bank_id');
				$amount			= $out_summ;
				$clear_amount	= 0; // billing algorithm
				$description	= "VIP " . __('access', true) . " " . $payDesc[$out_summ]; // описание платежа
				$sign			= $this->ref_sign($purse, $order_id, $amount, $clear_amount, $description, $secret_code);

				$data = 's_purse=' . $purse;

				$data.= '&';
				$data.= 's_order_id=' . $order_id;

				$data.= '&';
				$data.= 's_amount=' . $amount;

				$data.= '&';
				$data.= 's_clear_amount=' . $clear_amount;

				$data.= '&';
				$data.= 's_description=' . $description;

				$data.= '&';
				$data.= 's_sign=' . $sign;

				$host = "http://service.smscoin.com/bank/";

				$this->set('host', $host);
				$this->set('data', $data);
			}
		}
	}

    /**
     * successURL for STK card payment
     *
     */
    public function stkok()
    {

    }

    /**
     *
     * ФОРМА ОПЛАТЫ ПО КАРТЕ СТК
     *
     *
     */
	public function stkcard()
	{

	}

    /**
     *
     * ОПЛАТА ПО КАРТЕ СТК
     * ВХОДНЫЕ ДАННЫЕ ОТПРАВЛЯТЬ МЕТОДОМ POST
     *
     */
	public function stk()
	{
		$this->layout = 'ajax';
		if (empty($_POST['card']) || empty($_POST['pin']) || empty($_POST['sum']))
		{
			$this->Session->setFlash(__('Pay data error', true));
			$this->redirect('/pays');
		}

		$sum = (float)($_POST['sum']);
		if (empty($this->authUser['userid']))
			$sum = -1;//СЕРВИС ПРИОСТАНОВДЕН
		$perMonth	= Configure::read('STK.costPerMonth');
		$this->set('perMonth', $perMonth);
		$perWeek	= Configure::read('STK.costPerWeek');
		$this->set('perWeek', $perWeek);
		$perDay		= Configure::read('STK.costPerDay');
		$this->set('perDay', $perDay);
		$payDesc = array(
			$perDay		=> Configure::read('descPerDay'),
			$perWeek	=> Configure::read('descPerWeek'),
			$perMonth	=> Configure::read('descPerMonth'),
		);
		$this->set('payDesc', $payDesc);

		if ($sum > 0)
		{
			if (empty($this->authUser['userid']))
			{
				$this->redirect('/users/login');
			}
			$paySum = $sum;
/*
			if ($out_summ < $perDay) $out_summ = $perDay;
			if (($out_summ > $perDay) && ($out_summ < $perWeek)) $out_summ = $perWeek;
			if ($out_summ > $perWeek) $out_summ = $perMonth;
*/

			$payData = array('Pay' => array(
					'user_id'	=> $this->authUser['userid'],
			));
			$payData['Pay']['created']	= time();
			$payData['Pay']['summ']		= $paySum;
			$payData['Pay']['paysystem']= _PAY_STK_;

			if ($this->Pay->save($payData))
			{
				$this->layout = 'ajax';
				// информация об оплате
				$payData['Pay']['id'] = $this->Pay->getLastInsertID();
				$order_id		= $payData['Pay']['id'];
				$description	= "VIP " . __('access', true) . " " . $payDesc[$paySum]; // описание платежа
				$cardNumber		= $_POST['card'];
				$pinCode		= $_POST['pin'];
				$host			= '';
$paySum = 0.01;//ДЛЯ ТЕСТИРОВАНИЯ
				$data = '<?xml version="1.0" encoding="windows-1251"?>
				<request>
				<card>' . $cardNumber . '</card>
				<pin>' . $pinCode . '</pin>
				<summa>' . $paySum . '</summa>
				<recipient>patentmedia</recipient>
				<command>payment</command>
				<id>' . $order_id . '</id>
				</request>';

				$sock = fsockopen("ssl://089.ab.ru", 443, $errno, $errstr, 30);
				if ($sock)
				{
					fwrite($sock, "POST /FeUapSn8Ozcs8Ga/extovupay.php HTTP/1.0\r\n");
					fwrite($sock, "Host: 089.ab.ru\r\n");
					fwrite($sock, "Content-type: application/xml\r\n");
					fwrite($sock, "Content-length: " . strlen($data) . "\r\n");
					fwrite($sock, "Accept: */*\r\n");
					fwrite($sock, "\r\n");
					fwrite($sock, "$data\r\n");
					fwrite($sock, "\r\n");

					$response = "";
					while (!feof($sock)) $response .= fgets($sock);
					fclose($sock);

//РАЗБИРАЕМ ОТВЕТ
/*
<?xml version="1.0" encoding="windows-1251"?>
<response>
<result>ERROR</result>
<code>-2200</code>
<error>Неверно задан получатель платежа</error>
<recipient>videoxq1</recipient>
</response>
*/
					$matches = array();
					preg_match('/<result>(.*?)<\/result>/', $response, $matches, PREG_OFFSET_CAPTURE);
					if ($matches[1][0] == 'OK')
					{
			   			$sql = 'delete from groups_users where user_id = ' . $payData['Pay']['user_id'] . ' and group_id = ' . Configure::read('VIPgroupId') . ';';
		   	   			$this->Pay->query($sql);
			   			$sql= 'insert into groups_users (user_id, group_id) values(' . $payData['Pay']['user_id'] . ', ' . Configure::read('VIPgroupId') . ');';
		   	   			$this->Pay->query($sql);

		    			//корректируем VIP-группу форума (это сделает beforeSave при холостом обновлении)
		   				$uInfo = array('User' => array('userid' => $payData['Pay']['user_id'], 'lastactivity' => time()));
		   				$this->User->save($uInfo);

						//ДАТУ "ПРОПЛАЧЕНО ПО" СЧИТАЕМ ОТ ПОСЛЕДНЕЙ ОПЛАЧЕННОЙ
						$months = 0;
						$weeks = 0;
						$days = 0;
$paySum = 10;//ДЛЯ ТЕСТИРОВАНИЯ
						$months = intval($paySum / $perMonth);
						$paySum = $paySum - $perMonth * $months;

						$weeks = intval($paySum / $perWeek);
						$paySum = $paySum - $perWeek * $weeks;

						$days = intval($paySum / $perDay);
						$paySum = $paySum - $perDay * $days;

						$secs = ($days + $weeks * 7 + $months * 31) * 24 * 60 * 60;

						$lastFinDate = $payData['Pay']['created'];
						$last = $this->Pay->find(array('Pay.user_id' => $payData['Pay']['user_id'], 'Pay.status' => _PAY_DONE_), null, 'Pay.findate desc');
						if (!empty($last))
						{
							if ($last['Pay']['findate'] > $lastFinDate)
								$lastFinDate = $last['Pay']['findate'];
						}
						$payData['Pay']['paydate'] = $lastFinDate;
						$payData['Pay']['findate'] = $lastFinDate + $secs;
						$payData['Pay']['status'] = _PAY_DONE_;
						$this->Pay->save($payData);
						$this->redirect('/pays/stkok');
					}
					else
					{
						$payData['Pay']['status'] = _PAY_FAIL_;
						$this->Pay->save($payData);
						$matches = array();
						preg_match('/<error>(.*?)<\/error>/', $response, $matches, PREG_OFFSET_CAPTURE);
						$error = '';
						if (!empty($matches[1][0]))
						{
							$error = ': ' . $error;
						}
						$this->Session->setFlash(__('Payment failed', true) . iconv('windows-1251', 'utf8', $error));
						$this->redirect('/pays');
					}
				}
				else
				{
					$this->Session->setFlash(__('Pay server error', true));
					$this->redirect('/pays');
				}
			}
		}
		else
		{
			$this->Session->setFlash(__('Pay data error', true));
			$this->redirect('/pays');
		}
	}

    /**
     *
     * генерация ссылок на оплату через W1 (Единый кошелек)
     *
     * @param integer $summ
     */
	public function w1($summ = 0)
	{
		if (empty($this->authUser['userid']))
			$summ = -1;//СЕРВИС ПРИОСТАНОВДЕН
		$perMonth	= Configure::read('W1.costPerMonth');
		$this->set('perMonth', $perMonth);
		$perWeek	= Configure::read('W1.costPerWeek');
		$this->set('perWeek', $perWeek);
		$perDay		= Configure::read('W1.costPerDay');
		$this->set('perDay', $perDay);
		$payDesc = array(
			$perDay		=> Configure::read('descPerDay'),
			$perWeek	=> Configure::read('descPerWeek'),
			$perMonth	=> Configure::read('descPerMonth'),
		);
		$this->set('payDesc', $payDesc);

		if ($summ > 0)
		{
			if (empty($this->authUser['userid']))
			{
				$this->redirect('/users/login');
			}
	    	$allowDownload = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER['REMOTE_ADDR']);
	    	if (!$allowDownload)
	    	{
				//$this->redirect('/pays/notsupport');
	    	}

			$out_summ = sprintf("%01.2f", (float)($summ));
			$payData = array('Pay' => array(
					'user_id'	=> $this->authUser['userid'],
			));
			$payData['Pay']['created']	= time();
			$payData['Pay']['summ']		= $out_summ;
			$payData['Pay']['paysystem']= _PAY_W1_;

			if ($this->Pay->save($payData))
			{
				$this->layout = 'ajax';
				// информация об оплате
				$payData['Pay']['id'] = $this->Pay->getLastInsertID();

				$fields = array(
					"WMI_PAYMENT_NO"		=> $payData['Pay']['id'],
					"WMI_MERCHANT_ID"		=> Configure::read('W1.id'),
					"WMI_PAYMENT_AMOUNT"	=> $out_summ,
					"WMI_CURRENCY_ID"		=> Configure::read('W1.currency_id'),
					"WMI_DESCRIPTION"		=> "VIP " . __('access', true) . " " . ((!empty($payDesc[$summ])) ? $payDesc[$summ] : ""), // описание платежа
					"WMI_SUCCESS_URL"		=> "http://www.videoxq.com/pays/w1success",
					"WMI_FAIL_URL"			=> "http://www.videoxq.com/pays/w1fail",
				);

				ksort($fields);
				$fieldValues = "";
				foreach($fields as $name => $val)
				{
				   $fieldValues .= iconv("utf-8", "windows-1251", $val);
				}
				$secret_code	= Configure::read('W1.secret_code');
				$signature 		= base64_encode(pack("H*", md5($fieldValues . $secret_code)));

				$fields["WMI_SIGNATURE"] = $signature;

				$data = ''; $amp = '';
				foreach ($fields as $key => $value)
				{
					$data .= $amp . $key . '=' . $value;
					$amp = '&';
				}

				$host = "https://merchant.w1.ru/checkout/default.aspx";

				$this->set('host', $host);
				$this->set('data', $data);
			}
		}
	}

    /**
     * failURL for W1
     *
     */
    public function w1fail()
    {

    }

    /**
     * successURL for Ц1
     *
     */
    public function w1success()
    {

    }

    /**
     * resultURL for W1(единая касса) service
     * обработчик ответа от платежной системы
     * меняет статус записи об оплате
     *
     */
    public function w1result()
    {
    	$this->layout = 'ajax';

		$perMonth	= Configure::read('W1.costPerMonth');
		$perWeek	= Configure::read('W1.costPerWeek');
		$perDay		= Configure::read('W1.costPerDay');

		if (!isset($_POST["WMI_SIGNATURE"]))
		{
			$this->set("result". "Retry");
			$this->set("description", "Отсутствует параметр WMI_SIGNATURE");
		}

		if (!isset($_POST["WMI_PAYMENT_NO"]))
		{
			$this->set("result". "Retry");
			$this->set("description", "Отсутствует параметр WMI_PAYMENT_NO");
		}

		if (!isset($_POST["WMI_ORDER_STATE"]))
		{
			$this->set("result". "Retry");
			$this->set("description", "Отсутствует параметр WMI_ORDER_STATE");
		}
		return;

		$fields = array();
		foreach ($_POST as $key => $value)
		{
			if ($key == 'WMI_SIGNATURE') continue;
			$fields[$key] = $value;
		}

		ksort($fields);
		$fieldValues = "";
		foreach($fields as $name => $val)
		{
		   $fieldValues .= $val;
		}
		$secret_code	= Configure::read('W1.secret_code');
		$signature 		= base64_encode(pack("H*", md5($fieldValues . $secret_code)));

		$this->payLog("ResultUrl (resultpay)", $fields["WMI_PAYMENT_NO"], $fields["WMI_PAYMENT_AMOUNT"]);
		$this->payLog(serialize($_POST), '$_POST', 0);

		// validating the signature
		if($_POST["WMI_SIGNATURE"] == $signature)
		{
			switch (strtoupper($_POST["WMI_ORDER_STATE"]))
			{
				case "ACCEPTED":
				case "PROCESSING":
					$payData = $this->Pay->read(null, $order_id);
					if (!empty($payData) && ($payData['Pay']['status'] == _PAY_WAIT_))
					{
						$payData['Pay']['status'] = _PAY_DONE_;
						$payData['Pay']['paydate'] = time();
						$payData['Pay']['summ'] = $amount;

						$out_summ = $fields["WMI_PAYMENT_AMOUNT"];
						//ДАТУ "ПРОПЛАЧЕНО ПО" СЧИТАЕМ ОТ ПОСЛЕДНЕЙ ОПЛАЧЕННОЙ
						$months = 0;
						$weeks = 0;
						$days = 0;

						$months = intval($out_summ / $perMonth);
						$out_summ = $out_summ - $perMonth * $months;

						$weeks = intval($out_summ / $perWeek);
						$out_summ = $out_summ - $perWeek * $weeks;

						$days = intval($out_summ / $perDay);
						$out_summ = $out_summ - $perDay * $days;

						$secs = ($days + $weeks * 7 + $months * 31) * 24 * 60 * 60;

						$lastFinDate = $payData['Pay']['paydate'];
						$last = $this->Pay->find(array('Pay.user_id' => $payData['Pay']['user_id'], 'Pay.status' => _PAY_DONE_), null, 'Pay.findate desc');
						if (!empty($last))
						{
							if ($last['Pay']['findate'] > $lastFinDate)
								$lastFinDate = $last['Pay']['findate'];
						}
						$payData['Pay']['paydate'] = $lastFinDate;
						$payData['Pay']['findate'] = $lastFinDate + $secs;
						$this->Pay->save($payData);
						$success = true;

			   			$sql = 'delete from groups_users where user_id = ' . $payData['Pay']['user_id'] . ' and group_id = ' . Configure::read('VIPgroupId') . ';';
		   	   			$this->Pay->query($sql);
			   			$sql= 'insert into groups_users (user_id, group_id) values(' . $payData['Pay']['user_id'] . ', ' . Configure::read('VIPgroupId') . ');';
		   	   			$this->Pay->query($sql);

		    			//корректируем VIP-группу форума (это сделает beforeSave при холостом обновлении)
		   				$uInfo = array('User' => array('userid' => $payData['Pay']['user_id'], 'lastactivity' => time()));
		   				$this->User->save($uInfo);

		   				Configure::write('debug', 1);
		   	   			$userInfo = $this->User->read(null, $payData['Pay']['user_id']);
				        $result = $this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
		                /*to  */$userInfo['User']['username'] .
		                '<' .
		                $userInfo['User']['email'] .
		                                     '>',
		                /*subj*/Configure::read('App.siteName') . ' - ' . __('payment success', true),
		                /*body*/__('Dear', true) . " " . __("User", true) . ", " . $userInfo['User']['username'] . ".\n" . __('Received payment from you. Amount', true) . " " . $payData['Pay']['summ'] . " у.е.\n\n" . __("Thank you", true) . ".\n" . Configure::read('App.siteName') . " Robot");
					}
					$this->set("result". "OK");
					$this->set("description", __("Order") . " #" . $_POST["WMI_PAYMENT_NO"] . " " . __("paid successfully!", true));
					break;
			}
		}
		$this->payLog("ResultUrl (resultpay): Bad signature", $_POST["WMI_PAYMENT_NO"], $_POST["WMI_PAYMENT_AMOUNT"]);
    }


    /**
     *
     * генерация ссылок на оплату через e-card ROBOX
     *
     * @param integer $summ
     */
	public function erbx($summ = 0)
	{
		if (empty($this->authUser['userid']))
			$summ = -1;//СЕРВИС ПРИОСТАНОВДЕН
		$perMonth	= Configure::read('erbx.costPerMonth');
		$this->set('perMonth', $perMonth);
		$perWeek	= Configure::read('erbx.costPerWeek');
		$this->set('perWeek', $perWeek);
		$perDay		= Configure::read('erbx.costPerDay');
		$this->set('perDay', $perDay);
		$payDesc = array(
			$perDay		=> Configure::read('descPerDay'),
			$perWeek	=> Configure::read('descPerWeek'),
			$perMonth	=> Configure::read('descPerMonth'),
		);
		$this->set('payDesc', $payDesc);

		if ($summ > 0)
		{
			if (empty($this->authUser['userid']))
			{
				$this->redirect('/users/login');
			}
	    	$allowDownload = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER['REMOTE_ADDR']);
	    	if (!$allowDownload)
	    	{
				//$this->redirect('/pays/notsupport');
	    	}

			$out_summ = sprintf("%01.2f", (float)($summ));
			$payData = array('Pay' => array(
					'user_id'	=> $this->authUser['userid'],
			));
			$payData['Pay']['created']	= time();
			$payData['Pay']['summ']		= $out_summ;
			$payData['Pay']['paysystem']= _PAY_ERBX_;

			if ($this->Pay->save($payData))
			{
				$this->layout = 'ajax';
				// информация об оплате
				$payData['Pay']['id'] = $this->Pay->getLastInsertID();

				$fields = array(
					"MrchLogin"			=> Configure::read('erbx.login'),
					"OutSum"			=> $out_summ,
					"InvId"				=> $payData['Pay']['id'],
					"Desc"				=> "VIP " . __('access', true) . " " . ((!empty($payDesc[$summ])) ? $payDesc[$summ] : ""), // описание платежа
//					"IncCurrLabel"		=> 'RUR',
				);

				$secret_code	= Configure::read('erbx.pass1');
				$signature 		= md5($fields["MrchLogin"] . ':' . $fields["OutSum"] . ':' . $fields["InvId"] . ':' . $secret_code);
				$fields["SignatureValue"] = $signature;

				$data = ''; $amp = '';
				foreach ($fields as $key => $value)
				{
					$data .= $amp . $key . '=' . $value;
					$amp = '&';
				}

				if (Configure::read('erbx.testMode') == '1')
				{
					$host = "http://test.robokassa.ru/Index.aspx";
				}
				else
				{
					$host = "https://merchant.roboxchange.com/Index.aspx";
				}

//echo $data;
//exit;
				$this->redirect($host . '?' . $data);

				$this->set('host', $host);
				$this->set('data', $data);
			}
		}
	}

    /**
     * failURL for e-card ROBOX
     *
     */
    public function erbxfail()
    {

    }

    /**
     * successURL for e-card ROBOX
     *
     */
    public function erbxsuccess()
    {

    }

    /**
     * resultURL for e-card ROBOX
     * обработчик ответа от платежной системы
     * меняет статус записи об оплате
     *
     */
    public function erbxresult()
    {
    	$this->layout = 'ajax';

		$perMonth	= Configure::read('erbx.costPerMonth');
		$perWeek	= Configure::read('erbx.costPerWeek');
		$perDay		= Configure::read('erbx.costPerDay');

		$field["OutSum"] = $_POST['OutSum'];
		$field["InvId"] = intval($_POST['InvId']);
		$field["Sign"] = strtoupper($_POST['SignatureValue']);

		$secret_code	= Configure::read('erbx.pass2');
		$signature 		= strtoupper(md5($field["OutSum"] . ':' . $field["InvId"] . ':' . $secret_code));

		$this->payLog("ResultUrl (resultpay) ERBX");
		$this->payLog(serialize($_POST), '$_POST', 0);

		// validating the signature
		if($field["Sign"] == $signature)
		{
			$amount = sprintf("%01.2f", (float)($field["OutSum"]));
			$payData = $this->Pay->read(null, $field["InvId"]);
			if (!empty($payData) && ($payData['Pay']['status'] == _PAY_WAIT_))
			{
				$payData['Pay']['status'] = _PAY_DONE_;
				$payData['Pay']['paydate'] = time();
				$payData['Pay']['summ'] = $amount;

				$out_summ = $amount;
				//ДАТУ "ПРОПЛАЧЕНО ПО" СЧИТАЕМ ОТ ПОСЛЕДНЕЙ ОПЛАЧЕННОЙ
				$months = 0;
				$weeks = 0;
				$days = 0;

				$months = intval($out_summ / $perMonth);
				$out_summ = $out_summ - $perMonth * $months;

				$weeks = intval($out_summ / $perWeek);
				$out_summ = $out_summ - $perWeek * $weeks;

				$days = intval($out_summ / $perDay);
				$out_summ = $out_summ - $perDay * $days;

				$secs = ($days + $weeks * 7 + $months * 31) * 24 * 60 * 60;

				$lastFinDate = $payData['Pay']['paydate'];
				$last = $this->Pay->find(array('Pay.user_id' => $payData['Pay']['user_id'], 'Pay.status' => _PAY_DONE_), null, 'Pay.findate desc');
				if (!empty($last))
				{
					if ($last['Pay']['findate'] > $lastFinDate)
						$lastFinDate = $last['Pay']['findate'];
				}
				$payData['Pay']['paydate'] = $lastFinDate;
				$payData['Pay']['findate'] = $lastFinDate + $secs;
				$this->Pay->save($payData);
				$success = true;

	   			$sql = 'delete from groups_users where user_id = ' . $payData['Pay']['user_id'] . ' and group_id = ' . Configure::read('VIPgroupId') . ';';
   	   			$this->Pay->query($sql);
	   			$sql= 'insert into groups_users (user_id, group_id) values(' . $payData['Pay']['user_id'] . ', ' . Configure::read('VIPgroupId') . ');';
   	   			$this->Pay->query($sql);

    			//корректируем VIP-группу форума (это сделает beforeSave при холостом обновлении)
   				$uInfo = array('User' => array('userid' => $payData['Pay']['user_id'], 'lastactivity' => time()));
   				$this->User->save($uInfo);

   				Configure::write('debug', 1);
   	   			$userInfo = $this->User->read(null, $payData['Pay']['user_id']);
		        $result = $this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
                /*to  */$userInfo['User']['username'] .
                '<' .
                $userInfo['User']['email'] .
                                     '>',
                /*subj*/Configure::read('App.siteName') . ' - ' . __('payment success', true),
                /*body*/__('Dear', true) . " " . __("User", true) . ", " . $userInfo['User']['username'] . ".\n" . __('Received payment from you. Amount', true) . " " . $payData['Pay']['summ'] . " у.е.\n\n" . __("Thank you", true) . ".\n" . Configure::read('App.siteName') . " Robot");
			}
			$this->set("result", $field["InvId"]);
			break;
			}
		$this->payLog("ResultUrl (resultpay): Bad signature");
    }


    /**
     *
     * генерация ссылок на оплату через Assist
     *
     * @param integer $summ
     */
	public function assist($summ = 0)
	{
		$summ = (float)($summ);
		if (empty($this->authUser['userid']))
			$summ = -1;//СЕРВИС ПРИОСТАНОВДЕН
		$perMonth	= Configure::read('Assist.costPerMonth');
		$this->set('perMonth', $perMonth);
		$perWeek	= Configure::read('Assist.costPerWeek');
		$this->set('perWeek', $perWeek);
		$perDay		= Configure::read('Assist.costPerDay');
		$this->set('perDay', $perDay);
		$payDesc = array(
			$perDay		=> Configure::read('descPerDay'),
			$perWeek	=> Configure::read('descPerWeek'),
			$perMonth	=> Configure::read('descPerMonth'),
		);
		$this->set('payDesc', $payDesc);

		if ($summ > 0)
		{
			if (empty($this->authUser['userid']))
			{
				$this->redirect('/users/login');
			}
	    	$allowDownload = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER['REMOTE_ADDR']);
	    	if (!$allowDownload)
	    	{
				//$this->redirect('/pays/notsupport');
	    	}

			$out_summ = $summ;
/*
			if ($out_summ < $perDay) $out_summ = $perDay;
			if (($out_summ > $perDay) && ($out_summ < $perWeek)) $out_summ = $perWeek;
			if ($out_summ > $perWeek) $out_summ = $perMonth;
*/

			$payData = array('Pay' => array(
					'user_id'	=> $this->authUser['userid'],
			));
			$payData['Pay']['created']	= time();
			$payData['Pay']['summ']		= $out_summ;
			$payData['Pay']['paysystem']= _PAY_ASSIST_;

			if ($this->Pay->save($payData))
			{
				$this->layout = 'ajax';
				// информация об оплате
				$payData['Pay']['id'] = $this->Pay->getLastInsertID();
				$Order_IDP		= $payData['Pay']['id'];
				$Shop_IDP		= Configure::read('Assist.Shop_IDP');
				$Subtotal_P		= $out_summ;
				$Comment		= "VIP " . __('access', true) . " " . $payDesc[$out_summ]; // описание платежа
				$Delay			= 0; //НЕМЕДЛЕННОЕ СПИСАНИЕ

				$DemoResult		= 'AS000'; //ОЖИДАЕМ ЧТО ОПЛАТА ПРОЙДЕТ УСПЕШНО
				//$DemoResult		= 'AS100'; //ОЖИДАЕМ ЧТО ОПЛАТА НЕ ПРОЙДЕТ

				$URL_RETURN		= 'http://' . $_SERVER['HTTP_HOST'] . '/pays'; //ВОЗВРАТ НА СТРАНИЦУ ОПЛАТЫ
				$URL_RETURN_OK	= 'http://' . $_SERVER['HTTP_HOST'] . '/assistok.php'; //ПРИ УСПЕШНОЙ ОПЕРАЦИИ ОПЛАТЫ
				$URL_RETURN_NO	= 'http://' . $_SERVER['HTTP_HOST'] . '/assistno.php'; //ПРИ НЕОПЛАТЕ

				$data = 'Order_IDP=' . $Order_IDP;

				$data.= '&';
				$data.= 'Shop_IDP=' . $Shop_IDP;

				$data.= '&';
				$data.= 'Subtotal_P=' . $Subtotal_P;

				$data.= '&';
				$data.= 'Comment=' . iconv('utf-8', 'windows-1251', $Comment);

				$data.= '&';
				$data.= 'Delay=' . $Delay;

				$data.= '&';
				$data.= 'URL_RETURN=' . $URL_RETURN;

				$data.= '&';
				$data.= 'URL_RETURN_NO=' . $URL_RETURN_NO;

				$data.= '&';
				$data.= 'URL_RETURN_OK=' . $URL_RETURN_OK;

				$data.= '&';
				$data.= 'WebMoneyPayment=1';

				$data.= '&';
				$data.= 'PayCashPayment=1';

				$data.= '&';
				$data.= 'EPBeelinePayment=1';

				$data.= '&';
				$data.= 'CardPayment=1';

				$data.= '&';
				$data.= 'AssistIDCCPayment=1';

				if (Configure::read('Assist.testMode'))
				{
					$data.= '&';
					$data.= 'DemoResult=' . $DemoResult;
					$host = "https://test.assist.ru/shops/purchase.cfm";
				}
				else
				{
					$host = "https://secure.assist.ru/shops/purchase.cfm";
				}

				//$this->set('host', $host);
				//$this->set('data', $data);

				$url = $host . "?" . $data;

				header('location: ' . $url);

			}
		}
	}

	/**
	 * ВЫЗЫВАЕТСЯ Assist'ом ПРИ УСПЕШНОЙ ОПЛАТЕ
	 *
	 * @param integer $orderId
	 */
	function assistok($orderId = 0)
	{
		if (empty($orderId))
		{
			$this->redirect('/pays');
		}
		file_get_contents("http://" . $_SERVER['HTTP_HOST'] . '/pays/assistresult');//ДЕЛАЕМ ПОПЫТКУ ПОЛУЧИТЬ СОСТОЯНИЕ ПЛАТЕЖЕЙ
	}

	/**
	 * ВЫЗЫВАЕТСЯ Assist'ом ПРИ НЕОПЛАТЕ
	 *
	 * @param integer $orderId
	 */
	function assistno($orderId = 0)
	{
		if (empty($orderId))
		{
			$this->redirect('/pays');
		}
		//СОХРАНЯЕМ СТАТУС ПЛАТЕЖА КАК ОТМЕНЕННЫЙ
		$payData = $this->Pay->read(null, $orderId);
		if (!empty($payData) && ($payData['Pay']['status'] == _PAY_WAIT_))
		{
			$payData['Pay']['status'] = _PAY_FAIL_;
			$payData['Pay']['paydate'] = time();
			$payData['Pay']['findate'] = time();
			$this->Pay->save($payData);
		}
	}

	/**
	 * Запрос списка операций у системы Assist
	 *
	 */
	function assistresult()
	{
		$this->layout = 'ajax';

		$ShopOrderNumber= '%';//ВСЕ
		$Shop_ID		= Configure::read('Assist.Shop_IDP');
		$Login			= Configure::read('Assist.login');
		$Password		= Configure::read('Assist.pass');
		$Success		= 2; //КАКИЕ ВОЗВРАЩАТЬ (0 - неуспешные, 1 - успешные, 2 - все)
		$Format			= 4; //XML
		$ZipFlag		= 0; //Режим выдачи результата (0 – браузер, 1 – файл, 2 – архивированный файл)

		//ДАТУ-ВРЕМЯ НЕ УКАЗЫВАЕМ, БУДЕМ ВЫБИРАТЬ ПО УМОЛЧАНИЮ (ЗА ТРИ ДНЯ) СЕРВИС ОТВЕЧАЕТ ОДИН РАЗ ЗА ДЕСЯТЬ МИНУТ

		$data = 'ShopOrderNumber=' . $ShopOrderNumber;

		$data.= '&';
		$data.= 'Shop_ID=' . $Shop_ID;

		$data.= '&';
		$data.= 'Login=' . $Login;

		$data.= '&';
		$data.= 'Password=' . $Password;

		$data.= '&';
		$data.= 'Success=' . $Success;

		$data.= '&';
		$data.= 'Format=' . $Format;

		$data.= '&';
		$data.= 'ZipFlag=' . $ZipFlag;

		if (Configure::read('Assist.testMode'))
		{
			$host = "https://test.assist.ru/results/results.cfm";
		}
		else
		{
			$host = "https://secure.assist.ru/results/results.cfm";
		}

		$payInfo = $this->Pay->findAll(array('Pay.paysystem' => _PAY_ASSIST_, 'Pay.created >' . (time() - 3600 * 24 * 3), 'Pay.status' => _PAY_WAIT_), null);

		if (!empty($payInfo) && count($payInfo) > 0)
		{
			$xml = file_get_contents($host . "?" . $data);
			if ($xml)
			{
				global $orders;
				global $curOrder;
				global $tag;

				$orders = array();

				function startElement($parser, $name, $attrs)
				{
					global $orders;
					global $curOrder;
					global $tag;

					$tag = $name;
					switch ($tag)
					{
						case "ORDER":
							$curOrder = array();
					}
				    //$depth[$parser]++;
				}

				function characterData($parser, $data)
				{
					global $orders;
					global $curOrder;
					global $tag;

					if (!trim($data))
						return;

					switch ($tag)
					{
				    	case "ORDERNUMBER":
							$curOrder['id'] = intval($data);
						break;

				    	case "RESPONSE_CODE":
							$curOrder['code'] = $data;
				    	break;

				    	case "DATE":
							$date = sscanf($data, "%02s.%02s.%04s %02s:%02s:%02s");
							$curOrder['date'] = sprintf("%04s-%02s-%02s %02s:%02s:%02s", $date[2], $date[1], $date[0], $date[3], $date[4], $date[5]);
				    	break;

				    	case "TOTAL":
							$curOrder['total'] = $data;
				    	break;
					}
				}

				function endElement($parser, $name)
				{
					global $orders;
					global $curOrder;

				    //$depth[$parser]--;

				    switch ($name)
				    {
				    	case "ORDER":
				    		if (!empty($curOrder['id']))
				    		{
				    			$orders[$curOrder['id']] = $curOrder;
				    		}
				    	break;
				    }
				}

				$xml_parser = xml_parser_create();
				xml_set_element_handler($xml_parser, "startElement", "endElement");
				xml_set_character_data_handler($xml_parser, "characterData");

			    if (!xml_parse($xml_parser, $xml, true))
			    {

			        die(sprintf("XML error: %s at line %d",
			                    xml_error_string(xml_get_error_code($xml_parser)),
			                    xml_get_current_line_number($xml_parser)));
			    }
				xml_parser_free($xml_parser);

				foreach ($orders as $o)
				{
					if (!empty($o['id']))
					{
						foreach ($payInfo as $data)
						{
							if ($data['Pay']['id'] == $o['id'])
							{
								if ($o['code'] == 'AS000')
								{
									$data['Pay']['status'] = _PAY_DONE_;

									$out_summ = $o['total'];
									$perMonth	= Configure::read('Assist.costPerMonth');
									$perWeek	= Configure::read('Assist.costPerWeek');
									$perDay		= Configure::read('Assist.costPerDay');
									//ДАТУ "ПРОПЛАЧЕНО ПО" СЧИТАЕМ ОТ ПОСЛЕДНЕЙ ОПЛАЧЕННОЙ
									$months = 0;
									$weeks = 0;
									$days = 0;

									$months = intval($out_summ / $perMonth);
									$out_summ = $out_summ - $perMonth * $months;

									$weeks = intval($out_summ / $perWeek);
									$out_summ = $out_summ - $perWeek * $weeks;

									$days = intval($out_summ / $perDay);
									$out_summ = $out_summ - $perDay * $days;

									$secs = ($days + $weeks * 7 + $months * 31) * 24 * 60 * 60;

									$lastFinDate = time();
									$last = $this->Pay->find(array('Pay.user_id' => $data['Pay']['user_id'], 'Pay.status' => _PAY_DONE_), null, 'Pay.findate desc');
									if (!empty($last))
									{
										if ($last['Pay']['findate'] > $lastFinDate)
											$lastFinDate = $last['Pay']['findate'];
									}
									$data['Pay']['paydate'] = $lastFinDate;
									$data['Pay']['findate'] = $lastFinDate + $secs;
									$data['Pay']['summ'] = $o['total'];
									$this->Pay->save($data);

						   			$sql = 'delete from groups_users where user_id = ' . $data['Pay']['user_id'] . ' and group_id = ' . Configure::read('VIPgroupId') . ';';
					   	   			$this->Pay->query($sql);
						   			$sql= 'insert into groups_users (user_id, group_id) values(' . $data['Pay']['user_id'] . ', ' . Configure::read('VIPgroupId') . ');';
					   	   			$this->Pay->query($sql);

					   				//корректируем VIP-группу форума (это сделает beforeSave при холостом обновлении)
					   				//$uInfo = array('User' => array('userid' => $data['Pay']['user_id'], 'lastactivity' => time()));
					   				//$this->User->save($uInfo);

					   	   			Configure::write('debug', 1);
					   	   			$userInfo = $this->User->read(null, $data['Pay']['user_id']);
							        $result = $this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
					                /*to  */$userInfo['User']['username'] .
					                '<' .
					                $userInfo['User']['email'] .
					                                     '>',
					                /*subj*/Configure::read('App.siteName') . ' - ' . __('payment success', true),
	                                /*body*/__('Dear', true) . " " . __("User", true) . ", " . $userInfo['User']['username'] . ".\n" . __('Received payment from you. Amount', true) . " " . $payData['Pay']['summ'] . " rur.\n\n" . __("Thank you", true) . ".\n" . Configure::read('App.siteName') . " Robot");
					}
								else
								{
									$data['Pay']['status'] = _PAY_FAIL_;
									$this->Pay->save($data);
								}
							}
						}
					}
				}
				$this->set('orders', $orders);
			}
		}
	}

    /**
     * resultURL for SMSCOIN service
     * обработчик ответа от платежной системы
     * меняет статус записи об оплате
     *
     */
    public function smsresult()
    {
    	$this->layout = 'ajax';
		// as a part of ResultURL script
		// your registration data

		$perMonth	= Configure::read('Sms.costPerMonth');
		$perWeek	= Configure::read('Sms.costPerWeek');
		$perDay		= Configure::read('Sms.costPerDay');

		foreach($_REQUEST as $request_key => $request_value) {
			$_REQUEST[$request_key] = substr(strip_tags(trim($request_value)), 0, 250);
		}

		// service secret code
		$secret_code = Configure::read('Sms.secret_code');

		// collecting required data
		$purse        = $_REQUEST["s_purse"];        // sms:bank id
		$order_id     = $_REQUEST["s_order_id"];     // operation id
		$amount       = $_REQUEST["s_amount"];       // transaction sum
		$clear_amount = $_REQUEST["s_clear_amount"]; // billing algorithm
		$inv          = $_REQUEST["s_inv"];          // operation number
		$phone        = $_REQUEST["s_phone"];        // phone number
		$sign         = $_REQUEST["s_sign_v2"];      // signature

		// making the reference signature
		$reference = $this->ref_sign($secret_code, $purse, $order_id, $amount, $clear_amount, $inv, $phone);

		$success = false;
		$this->payLog("ResultUrl (resultpay)", $order_id, $amount);
		$this->payLog(serialize($_REQUEST), '$_POST', 0);

		// validating the signature
		if($sign == $reference)
		{
			// success, proceeding
			$payData = $this->Pay->read(null, $order_id);
			if (!empty($payData) && ($payData['Pay']['status'] == _PAY_WAIT_))
			{
				$payData['Pay']['status'] = _PAY_DONE_;
				$payData['Pay']['paydate'] = time();
				$payData['Pay']['summ'] = $amount;

				$out_summ = $amount;
				//ДАТУ "ПРОПЛАЧЕНО ПО" СЧИТАЕМ ОТ ПОСЛЕДНЕЙ ОПЛАЧЕННОЙ
				$months = 0;
				$weeks = 0;
				$days = 0;

				$months = intval($out_summ / $perMonth);
				$out_summ = $out_summ - $perMonth * $months;

				$weeks = intval($out_summ / $perWeek);
				$out_summ = $out_summ - $perWeek * $weeks;

				$days = intval($out_summ / $perDay);
				$out_summ = $out_summ - $perDay * $days;

				$secs = ($days + $weeks * 7 + $months * 31) * 24 * 60 * 60;

				$lastFinDate = $payData['Pay']['paydate'];
				$last = $this->Pay->find(array('Pay.user_id' => $payData['Pay']['user_id'], 'Pay.status' => _PAY_DONE_), null, 'Pay.findate desc');
				if (!empty($last))
				{
					if ($last['Pay']['findate'] > $lastFinDate)
						$lastFinDate = $last['Pay']['findate'];

					$last['Pay']['info'] = 'phone: ' . $phone;
				}
				$payData['Pay']['paydate'] = $lastFinDate;
				$payData['Pay']['findate'] = $lastFinDate + $secs;
				$this->Pay->save($payData);
				$success = true;

	   			$sql = 'delete from groups_users where user_id = ' . $payData['Pay']['user_id'] . ' and group_id = ' . Configure::read('VIPgroupId') . ';';
   	   			$this->Pay->query($sql);
	   			$sql= 'insert into groups_users (user_id, group_id) values(' . $payData['Pay']['user_id'] . ', ' . Configure::read('VIPgroupId') . ');';
   	   			$this->Pay->query($sql);

    			//корректируем VIP-группу форума (это сделает beforeSave при холостом обновлении)
   				$uInfo = array('User' => array('userid' => $payData['Pay']['user_id'], 'lastactivity' => time()));
   				$this->User->save($uInfo);

   				Configure::write('debug', 1);
   	   			$userInfo = $this->User->read(null, $payData['Pay']['user_id']);
		        $result = $this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
                /*to  */$userInfo['User']['username'] .
                '<' .
                $userInfo['User']['email'] .
                                     '>',
                /*subj*/Configure::read('App.siteName') . ' - ' . __('payment success', true),
                /*body*/__('Dear', true) . " " . __("User", true) . ", " . $userInfo['User']['username'] . ".\n" . __('Received payment from you. Amount', true) . " " . $payData['Pay']['summ'] . " у.е.\n\n" . __("Thank you", true) . ".\n" . Configure::read('App.siteName') . " Robot");
			}
		}
		$this->set('success', $success);
    }

    /**
     * failURL for SMSCOIN
     *
     */
    public function smsfail()
    {

    }

    /**
     * successURL for SMSCOIN
     *
     */
    public function smsuccess()
    {

    }

    /**
     * Инструкция пользователя (пользовательское соглашение)
     * генерация ссылок на оплату
     *
     * @param integer $summ
     */
	public function index($summ = 0)
	{
		$summ = intval($summ);
//if (empty($this->authUser['userid']))
//$summ = -1;//СЕРВИС ПРИОСТАНОВДЕН
//elseif ($this->authUser['username'] != 'vanoveb')
//$summ = -1;//СЕРВИС ПРИОСТАНОВДЕН
//$summ = 0;//СЕРВИС АКТИВИРУЕТСЯ
		$mrh_login = Configure::read('Robo.login'); // логин аккаунта на ROBO
		$mrh_pass1 = Configure::read('Robo.pass1'); // пароль 1
		$success = false;

		$perMonth	= Configure::read('costPerMonth');
		$this->set('perMonth', $perMonth);
		$perWeek	= Configure::read('costPerWeek');
		$this->set('perWeek', $perWeek);
		$perDay		= Configure::read('costPerDay');
		$this->set('perDay', $perDay);
		$payDesc = array(
			$perDay		=> Configure::read('descPerDay'),
			$perWeek	=> Configure::read('descPerWeek'),
			$perMonth	=> Configure::read('descPerMonth'),
		);
		$this->set('payDesc', $payDesc);

		$erbxPerMonth	= Configure::read('erbx.costPerMonth');
		$this->set('erbxPerMonth', $perMonth);
		$erbxPerWeek	= Configure::read('erbx.costPerWeek');
		$this->set('erbxPerWeek', $perWeek);
		$erbxPerDay		= Configure::read('erbx.costPerDay');
		$this->set('erbxPerDay', $perDay);

		$smsPerMonth	= Configure::read('Sms.costPerMonth');
		$this->set('smsPerMonth', $smsPerMonth);
		$smsPerWeek	= Configure::read('Sms.costPerWeek');
		$this->set('smsPerWeek', $smsPerWeek);
		$smsPerDay		= Configure::read('Sms.costPerDay');
		$this->set('smsPerDay', $smsPerDay);
		$smsPayDesc = array(
			$smsPerDay		=> Configure::read('descPerDay'),
			$smsPerWeek		=> Configure::read('descPerWeek'),
			$smsPerMonth	=> Configure::read('descPerMonth'),
		);
		$this->set('smsPayDesc', $smsPayDesc);

		$paypalPerMonth	= Configure::read('paypal.costPerMonth');
		$this->set('paypalPerMonth', $paypalPerMonth);
		$paypalPerWeek	= Configure::read('paypal.costPerWeek');
		$this->set('paypalPerWeek', $paypalPerWeek);
		$paypalPerDay	= Configure::read('paypal.costPerDay');
		$this->set('paypalPerDay', $paypalPerDay);
		$paypalPayDesc = array(
			$paypalPerDay	=> Configure::read('descPerDay'),
			$paypalPerWeek	=> Configure::read('descPerWeek'),
			$paypalPerMonth	=> Configure::read('descPerMonth'),
		);
		$this->set('paypalPayDesc', $paypalPayDesc);

		$assistPerMonth	= Configure::read('Assist.costPerMonth');
		$this->set('assistPerMonth', $assistPerMonth);
		$assistPerWeek	= Configure::read('Assist.costPerWeek');
		$this->set('assistPerWeek', $assistPerWeek);
		$assistPerDay	= Configure::read('Assist.costPerDay');
		$this->set('assistPerDay', $assistPerDay);
		$assistPayDesc = array(
			$assistPerDay	=> Configure::read('descPerDay'),
			$assistPerWeek	=> Configure::read('descPerWeek'),
			$assistPerMonth	=> Configure::read('descPerMonth'),
		);
		$this->set('assistPayDesc', $assistPayDesc);

		// инфгрмация об оплате
		if ($summ > 0)
		{
			if (empty($this->authUser['userid']))
			{
				$this->redirect('/users/login');
			}
/* //ФИЛЬТР ПО IP
	    	$allowDownload = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER['REMOTE_ADDR']);
	    	if (!$allowDownload)
	    	{
				$this->redirect('/pays/notsupport');
	    	}
*/
			$out_summ = intval($summ);

			if ($out_summ < $perDay) $out_summ = $perDay;
			if (($out_summ > $perDay) && ($out_summ < $perWeek)) $out_summ = $perWeek;
			if ($out_summ > $perWeek) $out_summ = $perMonth;
			$payData = array('Pay' => array(
				'user_id'	=> $this->authUser['userid'],
			));
			$payData['Pay']['created']	= time();
			$payData['Pay']['summ']		= $out_summ;

			$this->Pay->create();
			if ($this->Pay->save($payData))
			{
				$payData['Pay']['id'] = $this->Pay->getLastInsertID();

				//id оплаты (должен быть уникален в системе) нельзя сделать два запроса к roboxchange c одинаковым $inv_id
				$inv_id = $payData['Pay']['id'];

				$inv_desc = "VIP " . __("access", true) . " " . $payDesc[$out_summ]; // описание платежа
				$crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");
				$url = "https://merchant.roboxchange.com/Index.aspx?MrchLogin=$mrh_login&". "OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc";

				header('location: ' . $url);
				$url = '';

				$this->set('url', $url);
				$success = true;

				$this->payLog("payUrl (index)", $inv_id, $out_summ);
			}
		}
		//ВЫБОРКА ПОСЛЕДНИХ ОПЛАТ
		//$lst = $this->Pay->findAll(array('Pay.user_id' => $this->authUser['userid'], array('NOT' => array('Pay.paydate' => null))), null, 'Pay.created DESC', 10);

		file_get_contents("http://" . $_SERVER['HTTP_HOST'] . '/pays/assistresult');//ДЕЛАЕМ ПОПЫТКУ ПОЛУЧИТЬ СОСТОЯНИЕ ПЛАТЕЖЕЙ

		$lst = $this->Pay->findAll(array('Pay.user_id' => $this->authUser['userid'], 'Pay.status' => _PAY_DONE_, 'Pay.summ >' => 0), null, 'Pay.created DESC');
		$this->set('lst', $lst);
		$this->set('success', $success);
		$this->set('summ', $summ);
		$this->set('authUser', $this->authUser);
	}

	function agree()
	{
		$info = $this->Useragreement->find(array('Useragreement.user_id' => $this->authUser['userid']));
		$info['Useragreement']['user_id'] = $this->authUser['userid'];
		if (empty($info['Useragreement']['agree']))
		{
			$info['Useragreement']['agree'] = 1;
		}
		else
		{
			$info['Useragreement']['agree'] = 0;
		}
		$this->Useragreement->save($info);
		$this->redirect('/pays');
	}

	/**
	 * в зависимости от режима (тестовый/рабочий) возвращает название сервиса PayPal
	 *
	 * @return string
	 */
	function getPaypalEnvironment()
	{
		if (Configure::read('paypal.testMode'))
			return 'sandbox';
		else
			return '';
	}

	/**
	 * Send HTTP POST Request
	 *
	 * @param	string	The API method name
	 * @param	string	The POST Message fields in &name=value pair format
	 * @return	array	Parsed HTTP Response body
	 */
	function paypalRequest($methodName_, $nvpStr_)
	{
		$environment = $this->getPaypalEnvironment();	// or 'beta-sandbox' or 'live'

		// Set up your API credentials, PayPal end point, and API version.
		$API_UserName = urlencode(Configure::read('paypal.username'));
		$API_Password = urlencode(Configure::read('paypal.password'));
		$API_Signature = urlencode(Configure::read('paypal.signature'));
		$API_Endpoint = "https://api-3t.paypal.com/nvp";
		if("sandbox" === $environment || "beta-sandbox" === $environment) {
			$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
		}
		$version = urlencode('61.0');

		// Set the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);

		// Set the API operation, version, and API signature in the request.
		$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

		// Set the request as a POST FIELD for curl.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
//echo $nvpreq;
//exit;

		// Get response from the server.
		$httpResponse = curl_exec($ch);

		if(!$httpResponse) {
			exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
		}
//echo $httpResponse;
//exit;
		// Extract the response details.
		$httpResponseAr = explode("&", $httpResponse);

		$httpParsedResponseAr = array();
		foreach ($httpResponseAr as $i => $value) {
			$tmpAr = explode("=", $value);
			if(sizeof($tmpAr) > 1) {
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}

		if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
			return false;
			//exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
		}

		return $httpParsedResponseAr;
	}

    /**
     *
     * генерация ссылок на оплату через PayPal
     *
     * @param integer $summ
     */
	public function paypal($summ = 0)
	{
/*
//you can test your credentials with the simple form below

<form method=post action= https://api-3t.paypal.com/nvp>
<input type=hidden name=USER value= api_user>
<input type=hidden name=PWD value= api_password>
<input type=hidden name=SIGNATURE value= api_signature>
<input type=hidden name=VERSION value= 61.0>
<input type=hidden name=PAYMENTACTION value=Sale>
<input type=hidden name=AMT value=1.00>
<input type=hidden name=RETURNURL value=https://www.paypal.com>
<input type=hidden name=CANCELURL value=https://www.paypal.com>
<input type=submit name=METHOD value=SetExpressCheckout>
</form>
*/
		if (empty($this->authUser['userid']))
			$summ = -1;//СЕРВИС ПРИОСТАНОВДЕН
		$perMonth	= Configure::read('paypal.costPerMonth');
		$this->set('perMonth', $perMonth);
		$perWeek	= Configure::read('paypal.costPerWeek');
		$this->set('perWeek', $perWeek);
		$perDay		= Configure::read('paypal.costPerDay');
		$this->set('perDay', $perDay);
		$payDesc = array(
			$perDay		=> Configure::read('descPerDay'),
			$perWeek	=> Configure::read('descPerWeek'),
			$perMonth	=> Configure::read('descPerMonth'),
		);
		$this->set('payDesc', $payDesc);

		if ($summ > 0)
		{
			if (empty($this->authUser['userid']))
			{
				$this->redirect('/users/login');
			}
	    	$allowDownload = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER['REMOTE_ADDR']);
	    	if (!$allowDownload)
	    	{
				//$this->redirect('/pays/notsupport');
	    	}

			$out_summ = sprintf("%01.2f", (float)($summ));

			$this->layout = 'ajax';

			$fields = array(
				"AMT"			=> $out_summ,
				//"PAYMENTACTION"	=> "Authorization",
				"PAYMENTACTION"	=> "Sale",
				"CURRENCYCODE"	=> Configure::read('paypal.currency'),
				"RETURNURL"		=> "http://www.videoxq.com/pays/paypalok",
				"CANCELURL"		=> "http://www.videoxq.com/pays/paypalno",
			);

			$data = ''; $amp = '&';
			foreach ($fields as $key => $value)
			{
				$data .= $amp . $key . '=' . urlencode($value);
			}

			// Execute the API operation; see the PPHttpPost function above.
			$httpParsedResponseAr = $this->paypalRequest('SetExpressCheckout', $data);

			$payPalURL = '';

			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
				// Redirect to paypal.com.
				$environment = $this->getPaypalEnvironment();
				$token = urldecode($httpParsedResponseAr["TOKEN"]);
				$payPalURL = "https://www.paypal.com/webscr&cmd=_express-checkout&token=$token";
				if("sandbox" === $environment || "beta-sandbox" === $environment) {
					$payPalURL = "https://www.$environment.paypal.com/webscr&cmd=_express-checkout&token=$token";
				}

				$payData = array('Pay' => array(
						'user_id'	=> $this->authUser['userid'],
				));
				$payData['Pay']['created']	= time();
				$payData['Pay']['summ']		= $out_summ;
				$payData['Pay']['paysystem']= _PAY_PAYPAL_;
				$payData['Pay']["info"]		= $token;

				if ($this->Pay->save($payData))
				{
					$this->payLog("PayPal payment", $this->Pay->getLastInsertID(), $out_summ);
					$this->payLog(serialize($httpParsedResponseAr), 'PayPal Answer', 0);
				}
				$this->redirect($payPalURL);
			} else  {
				exit('SetExpressCheckout failed: ' . print_r($httpParsedResponseAr, true));
			}

			$host = $payPalURL;

			$this->set('host', $host);
			$this->set('data', $data);
		}
	}

    /**
     * resultURL for PayPal payment
     * обработчик ответа от платежной системы
     * меняет статус записи об оплате
     *
     */
    public function paypalok()
    {
    	//$this->layout = 'ajax';
		// as a part of ResultURL script
		// your registration data

		$perMonth	= Configure::read('paypal.costPerMonth');
		$perWeek	= Configure::read('paypal.costPerWeek');
		$perDay		= Configure::read('paypal.costPerDay');

		$this->payLog("PayPal OK", 0, 0);
		$this->payLog(serialize($_REQUEST), 'PayPal Request OK', 0);

		//if (empty($token))
		{
			if (empty($_REQUEST['token']))
			{
				$this->redirect('/pays/paypalno');
			}
			$token = urldecode($_REQUEST['token']);
			$payerID = urldecode($_REQUEST['PayerID']);
			//$this->redirect('/pays/paypalok/' . $_REQUEST['token']);
		}
		//else
		{
			//$token = urlencode(htmlspecialchars($token));
		}

		// success, proceeding
		$payData = $this->Pay->find(array('Pay.info' => $token));
		if (!empty($payData) && ($payData['Pay']['status'] == _PAY_WAIT_))
		{
			$payData['Pay']['status'] = _PAY_DONE_;
			$payData['Pay']['paydate'] = time();
			$amount = $payData['Pay']['summ'];

			$out_summ = $amount;

			$token = urlencode(htmlspecialchars($token));
			$payerID = urlencode(htmlspecialchars($payerID));

			$paymentType = urlencode("Sale");			// 'Authorization' or 'Sale' or 'Order'
			$paymentAmount = urlencode($out_summ);
			$currencyID = urlencode(Configure::read('paypal.currency'));	// or other currency code ('USD', 'GBP', 'EUR', 'JPY', 'CAD', 'AUD')

			// Add request-specific fields to the request string.
			$nvpStr = "&TOKEN=$token&PAYERID=$payerID&PAYMENTACTION=$paymentType&AMT=$paymentAmount&CURRENCYCODE=$currencyID";

			// Execute the API operation; see the PPHttpPost function above.
			$httpParsedResponseAr = $this->paypalRequest('DoExpressCheckoutPayment', $nvpStr);

			$this->payLog("PayPal DoExpressCheckoutPayment", 0, 0);
			$this->payLog(serialize($httpParsedResponseAr), 'PayPal DoExpressCheckoutPayment', 0);

			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
			} else  {
				$this->redirect('/pays/paypalno');
			}

			//ДАТУ "ПРОПЛАЧЕНО ПО" СЧИТАЕМ ОТ ПОСЛЕДНЕЙ ОПЛАЧЕННОЙ
			$months = 0;
			$weeks = 0;
			$days = 0;

			$months = intval($out_summ / $perMonth);
			$out_summ = $out_summ - $perMonth * $months;

			$weeks = intval($out_summ / $perWeek);
			$out_summ = $out_summ - $perWeek * $weeks;

			$days = intval($out_summ / $perDay);
			$out_summ = $out_summ - $perDay * $days;

			$secs = ($days + $weeks * 7 + $months * 31) * 24 * 60 * 60;

			$lastFinDate = $payData['Pay']['paydate'];
			$last = $this->Pay->find(array('Pay.user_id' => $payData['Pay']['user_id'], 'Pay.status' => _PAY_DONE_), null, 'Pay.findate desc');
			if (!empty($last))
			{
				if ($last['Pay']['findate'] > $lastFinDate)
					$lastFinDate = $last['Pay']['findate'];

				$last['Pay']['info'] = 'phone: ' . $phone;
			}
			$payData['Pay']['paydate'] = $lastFinDate;
			$payData['Pay']['findate'] = $lastFinDate + $secs;
			$this->Pay->save($payData);
			$success = true;

   			$sql = 'delete from groups_users where user_id = ' . $payData['Pay']['user_id'] . ' and group_id = ' . Configure::read('VIPgroupId') . ';';
	   			$this->Pay->query($sql);
   			$sql= 'insert into groups_users (user_id, group_id) values(' . $payData['Pay']['user_id'] . ', ' . Configure::read('VIPgroupId') . ');';
	   			$this->Pay->query($sql);

			//корректируем VIP-группу форума (это сделает beforeSave при холостом обновлении)
				$uInfo = array('User' => array('userid' => $payData['Pay']['user_id'], 'lastactivity' => time()));
				$this->User->save($uInfo);

				Configure::write('debug', 1);
	   			$userInfo = $this->User->read(null, $payData['Pay']['user_id']);
	        $result = $this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
            /*to  */$userInfo['User']['username'] .
            '<' .
            $userInfo['User']['email'] .
                                 '>',
            /*subj*/Configure::read('App.siteName') . ' - ' . __('payment success', true),
            /*body*/__('Dear', true) . " " . __("User", true) . ", " . $userInfo['User']['username'] . ".\n" . __('Received payment from you. Amount', true) . " " . $payData['Pay']['summ'] . " " . Configure::read('paypal.currency') . "\n\n" . __("Thank you", true) . ".\n" . Configure::read('App.siteName') . " Robot");
		}
		$this->set('success', $success);
    }

    /**
     * failURL for PayPal
     *
     */
    public function paypalno()
    {
		$this->payLog("PayPal NO", 0, 0);
		$this->payLog(serialize($_REQUEST), 'PayPal Request NO', 0);

    }

}
