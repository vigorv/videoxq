<div class="contentCol">
<h2><?php __("Auth"); ?></h2>
<?php
//$html->css('style', null, array(), false);
echo $form->create('User', array('action' => 'login' , 'class' => 'reg'));
?>
<p><label for="UserUsername"><?php __("Your Login"); ?><em class="required">*</em> :</label><br>
<?php //echo $form->error('username'); ?>
<?php echo $form->text('username', array('class' => 'textInput')); ?>
</p>
<p><label for="UserPassword"><?php __("Your Password"); ?><em class="required">*</em> :</label><br>
<?php //echo $form->error('password'); ?>
<?php echo $form->password('password', array('class' => 'textInput')); ?>
<input type="hidden" name="data[User][remember_me]" value="1" id="UserRememberMe" />
</p>
<br>
<?php
echo $form->end(__("Sign In", true));
?>
<p><a href=/users/restore><?php __("Forgot password"); ?>?</a></p>
</div>