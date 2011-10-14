<?php
class ReportsController extends AppController {

    var $name = 'Reports';
    var $viewPath = 'media/reports';
    var $components = array('Captcha' , 'Email');
    var $helpers = array('Html','Form','Javascript');
    var $uses = array('Report');

//------------------------------------------------------------------------------

    function index()
    {
        //pr ($this->Report->validate);
        if (!empty($this->data) && $this->data){
            $this->data['Report']['captcha2'] = $this->Session->read('captcha');
            $form_data = $this->data['Report'];
            $this->Report->set($form_data);
//            if (isset($form_data['captcha']))
//                $form_data['captcha2'] = $this->Session->read('captcha');
            //pr($form_data);

            if ($this->Report->validates($form_data)){
                //echo('OK');
                $email_to = 'admin@videoxq-wc2.anka.ws';
                $name_from = $this->data['Report']['name_from'];
                $email_from = $this->data['Report']['email_from'];
                $message = $this->data['Report']['message'];

                $this->Email->charset       = 'utf-8'; // кодировка
                $this->Email->to            = $email_to; // адресат - кому отправляем письмо
                $this->Email->сс          = ''; // копии письма
                $this->Email->bсс         = ''; // скрытые копии письма
                $this->Email->replyTo       = $email_from; // email, на который можно ответить
                $this->Email->return        = $email_from; // адрес, на который будут приходить ошибки отправки, в случае их возникновения
                $this->Email->from          = $name_from.' <'.$email_from.'>'; // от кого письмо
                $this->Email->subject       = 'Сообщение от доброжелателя! :) с сайта videoxq.com'; // тема письма
                $this->Email->template      = 'reports_email'; // шаблон письма
                $this->Email->sendAs        = 'html'; // в каком формате отсылать письмо (text, html или both)
                $this->Email->filePaths     = array(); // папка с файлами, которые хотите присоединить к письму
                $this->Email->attachments   = array(); // массив с файлами для отправки
                $this->Email->delivery      = 'mail'; // способ отправки (mail, smtp или debug)
                $this->Email->smtpOptions   = array(
                            'port' => '25',
                            'timeout' => '30',
                            'host' => 'smtp.server',
                            'username' => '',
                            'password' => '',
                            'client' => 'smtp_helo_hostname'
                        ); // если выбран способ отправки SMTP, то необходимо задать параметры отправки в виде ассоциативного массива

                $this->set('message', $message);
                //$this->Email->send();
                $this->Session->setFlash('Спасибо! Ваше сообщение отправлено! Оно поможет нам сделать мир видеоиндустрии чище и светлее :)))))');
                unset($form_data);
            }
            else{
                //echo('Error');
                $this->Session->setFlash('Ошибка, заполните все поля корректно');
            }
        }
        if (!empty($form_data)){
            $this->set('data',$form_data);
        }
    }

//------------------------------------------------------------------------------

    function captcha()
    {
        $this->layout = 'ajax';
        $this->Captcha->render();
        $this->view = null;
    }
//------------------------------------------------------------------------------
    public function scode()
    {
        $this->layout = 'ajax';
    	$this->set("captcha", $this->Session->read('captcha'));
    	$this->render();
    }
//------------------------------------------------------------------------------

}
?>