<div class="contentCol">
<h2>Вход</h2>
<?php
//$html->css('style', null, array(), false);
echo $form->create('User', array('action' => 'login' , 'class' => 'reg'));
?>
<p><label for="UserUsername">Логин<em class="required">*</em> :</label><br>
<?php //echo $form->error('username'); ?>
<?php echo $form->text('username', array('class' => 'textInput')); ?>
</p>
<p><label for="UserPassword">Пароль<em class="required">*</em> :</label><br>
<?php //echo $form->error('password'); ?>
<?php echo $form->password('password', array('class' => 'textInput')); ?>
<input type="hidden" name="data[User][remember_me]" value="1" id="UserRememberMe" />
</p>
<br>
<?php
echo $form->end('Войти!');
?>
<p><a href=/users/restore>Потерял пароль?</a></p>
</div>