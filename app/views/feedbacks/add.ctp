<div id="content">
Извините, в данный момент у нас нет фильма, который вы ищите.<br>
Вы можете оставить заявку и мы известим вас, когда он появится.
<h2>Заявка</h2>
<?php
//$html->css('style', null, array(), false);
echo $form->create('Feedback', array('class' => 'reg'));
?>
<p><label for="UserEmail">E-mail
<!--
<em class="required">*</em>
-->
 :</label><br>
<?php
//echo $form->error('email');
?>
<?php echo $form->text('email', array('class' => 'textInput')); ?></p>
<p><label for="UserCaptcha">Название фильма<em class="required">*</em> :</label><br>
<?php echo $form->error('film'); ?>
<?php echo $form->text('film', array('class' => 'textInput')); ?></p>
<br>
<?php
echo $form->end('Хочу этот фильм!');
?>
</div>