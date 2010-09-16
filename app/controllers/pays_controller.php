<?php
    App::import('Model', 'User');
	App::import('Model', 'Pay');

	define('_PAY_ROBOX_',	0);
	define('_PAY_SMSCOIN_',	1);
	define('_PAY_ASSIST_',	2);
	define('_PAY_W1_',		3);

class PaysController extends AppController
{
    public $name = 'Pays';
    public $uses = array('User', 'Pay');

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
                /*body*/"Уважаемый пользователь, " . $userInfo['User']['username'] . ".\nОт вас поступил платеж в размере " . $payData['Pay']['summ'] . " wmr.\n\nСпасибо.\n" . Configure::read('App.siteName') . " Robot");
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
				$description	= "VIP доступ " . $payDesc[$out_summ]; // описание платежа
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
					"WMI_DESCRIPTION"		=> "VIP доступ " . ((!empty($payDesc[$summ])) ? $payDesc[$summ] : ""), // описание платежа
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

		uksort($fields, "strcasecmp");
		$fieldValues = "";
		foreach($fields as $name => $val)
		{
		   $fieldValues .= $val;
		}
		$secret_code	= Configure::read('W1.secret_code');
		$signature 		= base64_encode(pack("H*", md5($fieldValues . $key)));

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
		                /*body*/"Уважаемый пользователь, " . $userInfo['User']['username'] . ".\nОт вас поступил платеж в размере " . $payData['Pay']['summ'] . " у.е.\n\nСпасибо.\n" . Configure::read('App.siteName') . " Robot");
					}
					$this->set("result". "OK");
					$this->set("description", "Заказ #" . $_POST["WMI_PAYMENT_NO"] . " оплачен!");
					break;
			}
		}
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
				$Comment		= "VIP доступ " . $payDesc[$out_summ]; // описание платежа
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
				$data.= 'CardPayment=0';

				$data.= '&';
				$data.= 'AssistIDCCPayment=0';

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
					                /*body*/"Уважаемый пользователь, " . $userInfo['User']['username'] . ".\nОт вас поступил платеж в размере " . $data['Pay']['summ'] . " RUR.\n\nСпасибо.\n" . Configure::read('App.siteName') . " Robot");
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
                /*body*/"Уважаемый пользователь, " . $userInfo['User']['username'] . ".\nОт вас поступил платеж в размере " . $payData['Pay']['summ'] . " у.е.\n\nСпасибо.\n" . Configure::read('App.siteName') . " Robot");
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

				$inv_desc = "VIP доступ " . $payDesc[$out_summ]; // описание платежа
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

		$lst = $this->Pay->findAll(array('Pay.user_id' => $this->authUser['userid'], 'Pay.status' => _PAY_DONE_), null, 'Pay.created DESC');
		$this->set('lst', $lst);
		$this->set('success', $success);
		$this->set('summ', $summ);
		$this->set('authUser', $this->authUser);
	}
}
