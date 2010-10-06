<div class="contentCol">
<h2><?php __("Restore"); ?></h2>
<?php
//$html->css('style', null, array(), false);
echo $form->create('User', array('action' => 'restore' , 'class' => 'reg', 'onsubmit' => "return ((getElementById('UserEmail').value != '') && (getElementById('UserCaptcha').value != ''));"));
?>
<?php
//<p><label for="UserUsername">Логин<em class="required">*</em> :</label><br>
//echo $form->text('username', array('class' => 'textInput'));
//</p>
?>
<p><label for="UserEmail">E-mail<em class="required">*</em> :</label><br>
<?php echo $form->text('email', array('class' => 'textInput')); ?></p>
<p><label for="UserCaptcha"><?php __("Antibot"); ?><em class="required">*</em> :</label><br>
<?php echo $form->text('captcha', array('class' => 'textInput')); ?></p>
<!--<p>Можно писать как ПРОПИСНЫМИ, так и строчными буквами — как пожелаете.</p>-->
<p><img src="<?php echo $html->url('/users/captcha'); ?>" /></p>
<br>
<?php
echo $form->end(__("Forgot password", true));
?>
</div>
