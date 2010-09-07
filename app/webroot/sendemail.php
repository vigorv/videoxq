<?php
header('Content-Type: text/html; charset=utf-8');
if (
	($_SERVER['REQUEST_METHOD'] == "POST")
	&&
	(!empty($_POST['email']))
	&&
	(!empty($_POST['subj']))
	&&
	(!empty($_POST['body']))
   )
   {
include_once($_SERVER['DOCUMENT_ROOT'] . '/app/vendors/mail.php');
		$mailObj = new simpleMail();
		$mailObj->addhdfield('X-Mailer', 'videoxqRobot');
		$mailObj->addhdfield('Precedence', 'bulk');
		$mailObj->setTo($_POST['email']);
		$mailObj->setFrom($_POST['email']);
		$mailObj->setSubject($_POST['subj']);
		$body .= "\n\nPS\nПисьмо отправлено почтовым роботом. Пожалуйста, не отвечайте на него.\n\n";
		$mailObj->setTextBody($_POST['body']);
		$mailObj->send();
		$mailObj = 0;
echo 'email send to ' . $_POST['email'];
$email = 0;
   }
   else
   {
   		echo 'invalid input data';
   }