<?php
class FeedbacksController extends AppController
{

    var $name = 'Feedbacks';
    var $helpers = array('Html', 'Form');
    var $pageTitle = 'Обратная связь';
    var $components = array('Email');


    function add()
    {
        if (!empty($this->data))
        {
            $this->Feedback->create();
            if (empty($this->data['Feedback']['email']))
            	$this->data['Feedback']['email'] = 'не указан';
            if ($this->Feedback->save($this->data))
            {
                $this->Session->setFlash('Заявка принята.');
                $this->redirect('/media');
            }
            else
            {
                $this->Session->setFlash('Не могу принять заявку, попробуйте еще раз.');
            }
        }
    }


    function admin_index()
    {
		if (!empty($_POST['nm']))
		{
			$this->layout = 'ajax';
			switch ($_POST['tracker'])
			{
				case 'rutrackerorg':
/*
					$host = 'http://login.rutracker.org/forum/login.php';
					$ch = curl_init($host);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_AUTOREFERER, true);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

					//ПРОБУЕМ АВТОРИЗОВАТЬСЯ ПОД УЧЕТНОЙ ЗАПИСЬЮ ПОЛЬЗОВАТЕЛЯ vigorv007
					$data = 'login_username=vigorv007&login_password=eitoh5Dae2&redirect=index.php';
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
					$cookieFile = 'cookie.txt';
					curl_setopt ($ch, CURLOPT_COOKIEFILE, "cookie.txt"); // Сюда будем записывать cookies, файл в той же папке, что и сам скрипт
					curl_setopt ($ch, CURLOPT_COOKIEJAR, "cookie.txt");
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 0);
					$content = curl_exec($ch);
					curl_close($ch);

					//РАЗБОР COOKIES ПОСЛЕ АВТОРИЗАЦИИ
					$cookieFile = $_SERVER['DOCUMENT_ROOT'] . '/app/webroot/' . $cookieFile;
					$strCookies = '';
					if (file_exists($cookieFile))
					{
						$cookies = file_get_contents($cookieFile);
						unlink($cookieFile);
						$cookies = explode("\n", $cookies);
						foreach ($cookies as $cookie)
						{
							$cook = explode("\t", $cookie);
							if (isset($cook[5]))
							{
								$strCookies .= $cook[5] . '=' . $cook[6] . ';';
							}
						}
					}
echo '1111111111111 ' . $strCookies;
//*/
					$host = 'http://login.rutracker.org/forum/login.php';
					$host = 'http://rutracker.org/forum/tracker.php?nm=' . rawurlencode(iconv('utf-8','windows-1251', $_POST['nm']));
					$data = 'nm=' . rawurlencode(iconv('utf-8','windows-1251', $_POST['nm'])) . '&f[]=-1';
					$ch = curl_init($host);

					//curl_setopt ($ch, CURLOPT_COOKIE, $strCookies); //Устанавливаем нужные куки в необходимом формате
					curl_setopt($ch, CURLOPT_AUTOREFERER, true);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 0);
					header('Content-Type: text/html; charset=windows-1251');
					ob_start();
					curl_exec($ch);
					curl_close($ch);
					$content = ob_get_contents();
					ob_end_clean();
					$content = strtr($content, array('<head>' => '<head><base href="http://rutracker.org" />'));
//					$content = strtr($content, array('<body>' => '<body onload="document.enterform.submit();">'));
//					$content = strtr($content, array('<form action="http://login.rutracker.org/forum/login.php"' => '<form id="enterform" name="enterform" action="http://login.rutracker.org/forum/login.php"'));
					$content = strtr($content, array('name="redirect" value="/forum/tracker.php"' => 'name="redirect" value="/forum/tracker.php?nm=' . rawurlencode(iconv('utf-8','windows-1251', $_POST['nm'])). '"'));
					$content = strtr($content, array('name="login_password"' => 'name="login_password" value="eitoh5Dae2"'));
					$content = strtr($content, array('name="login_username"' => 'name="login_username" value="vigorv007"'));
					echo $content;
					exit;
				break;

				case 'rutrackerru':
					$host = 'http://rutracker.ru/tracker.php';
					$data = 'nm=' . iconv('utf-8','windows-1251', $_POST['nm']) . '&max=1&to=1';
					$ch = curl_init($host);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 0);
					header('Content-Type: text/html; charset=windows-1251');
					ob_start();
					curl_exec($ch);
					curl_close($ch);
					$content = ob_get_contents();
					ob_end_clean();
					$content = strtr($content, array('<head>' => '<head><base href="http://rutracker.ru" />'));
					echo $content;
					exit;
				break;
			}
		}
        $this->Feedback->recursive = 0;
        $this->paginate['Feedback']['group'] = 'film';
        if (!$this->canDelete())
	        $this->paginate['Feedback']['conditions'] = array('Feedback.deleted' => 0);
        //$this->paginate['Feedback']['sort'] = 'Feedback.deleted DESC';

        $this->set('feedbacks', $this->paginate());
    }

    function admin_view($id = null)
    {
        if (! $id)
        {
            $this->Session->setFlash(__('Invalid Feedback.', true));
            $this->redirect(array('action' => 'index'));
        }
        $feedback = $this->Feedback->read(null, $id);
        $conditions = array('film' => $feedback['Feedback']['film']);
        $all = $this->Feedback->find('all', compact('conditions'));

        $emails = Set::extract('/Feedback/email', $all);
        $this->data['Feedback']['subj'] = 'Заявка на фильм ' . $feedback['Feedback']['film'];
        $this->data['Feedback']['text'] = 'Ваша заявка на фильм ' . $feedback['Feedback']['film'] . ' обработана.';
        $this->data['Feedback']['text'] .= "<br>Вы можете скачать фильм по ссылке: ";
        $this->set(compact('feedback', 'emails'));
    }


    function admin_reply()
    {

        $this->Email->_debug = true;

        $this->Email->sendAs = 'html';
        $this->Email->bcc = explode(',', $this->data['Feedback']['to']);
        $result = $this->_sendEmail(/*from*/Configure::read('App.mailFrom'),
                          /*to  */'nobody@itdeluxe.com',
                          /*subj*/$this->data['Feedback']['subj'],
                          /*body*/$this->data['Feedback']['text']
                                  );

        if (!$result)
        {
            $this->Session->setFlash('Internal server error during sending mail');
            CakeLog::write(LOG_ERROR, 'Mail error: ' . $this->Email->smtpError);
        }
        $this->redirect(array('action' => 'index'));

    }


    function admin_add()
    {
        if (! empty($this->data))
        {
            $this->Feedback->create();
            if ($this->Feedback->save($this->data))
            {
                $this->Session->setFlash(__('The Feedback has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Feedback could not be saved. Please, try again.', true));
            }
        }
    }


    function admin_edit($id = null)
    {
        if (! $id && empty($this->data))
        {
            $this->Session->setFlash(__('Invalid Feedback', true));
            $this->redirect(array('action' => 'index'));
        }
        if (! empty($this->data))
        {
            if ($this->Feedback->save($this->data))
            {
                $this->Session->setFlash(__('The Feedback has been saved', true));
                $this->redirect(array('action' => 'index'));
            }
            else
            {
                $this->Session->setFlash(__('The Tag could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data))
        {
            $this->data = $this->Feedback->read(null, $id);
        }
    }

	function canDelete()
	{
		return (in_array(1, $this->authUserGroups));
	}

    function admin_delete_all()
    {
    	if (!empty($this->data))
    	{
    		if ($this->canDelete())
				$this->Feedback->deleteAll(array('Feedback.id' => $this->data));
			else
				$this->Feedback->updateAll(array('deleted' => 1), array('Feedback.id' => $this->data));
	        $this->Session->setFlash(count($this->data) . ' ' . __('records deleted', true));
    	}
    	else
	        $this->Session->setFlash(__('nothing checked', true));

        $this->redirect(array('action' => 'index'));
//    	$this->set('data', $this->data);
    }

    function admin_restore_all()
    {
    	if (!empty($this->data))
    	{
    		if ($this->canDelete())
    		{
				$this->Feedback->updateAll(array('deleted' => 0), array('Feedback.id' => $this->data));
    		}
	        $this->Session->setFlash(count($this->data) . ' ' . __('records restored', true));
    	}
    	else
	        $this->Session->setFlash(__('nothing checked', true));
        $this->redirect(array('action' => 'index'));
    }

    function admin_delete($id = null)
    {
        if (!$id)
        {
            $this->Session->setFlash(__('Invalid id for Feedback', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->canDelete())
        {
	        if ($this->Feedback->del($id))
	        {
	            $this->Session->setFlash(__('Feedback deleted', true));
	            $this->redirect(array('action' => 'index'));
	        }
        }
        else
			$this->Feedback->updateAll(array('deleted' => 1), array('Feedback.id' => $id));

        $this->Session->setFlash(__('Feedback deleted', true));
        $this->redirect(array('action' => 'index'));
    }

    function admin_restore($id = null)
    {
        if (!$id)
        {
            $this->Session->setFlash(__('Invalid id for Feedback', true));
            $this->redirect(array('action' => 'index'));
        }
		$this->Feedback->updateAll(array('deleted' => 0), array('Feedback.id' => $id));

        $this->Session->setFlash(__('Feedback restored', true));
        $this->redirect(array('action' => 'index'));
    }

}
?>