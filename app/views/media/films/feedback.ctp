<div id="content">
<?php
if (!empty($search_words))
{
   foreach ($search_words as $searchWord)
   {
       echo '<h3>Возможно, то, что вы ищите находится ';
       echo $html->link('здесь', $searchWord['SearchWord']['url']);
       echo '</h3><br>';
   }
}
?>


Извините, в данный момент у нас нет фильма, который вы ищите.<br>
Вы можете оставить заявку и мы известим вас, когда он появится.
<h2>Заявка</h2>
<?php
//$html->css('style', null, array(), false);
echo $form->create('Feedback', array('class' => 'reg'));
?>
<p><label for="UserEmail">E-mail
<!--<em class="required">*</em
-->
:</label><br>
<?php

//echo $form->error('email');
if (!isset($user['email'])) $user['email'] = '';
?>
<?php echo $form->text('email', array('class' => 'textInput', 'value' => $user['email'])); ?></p>
<p><label for="UserCaptcha">Название фильма<em class="required">*</em> :</label><br>
<?php echo $form->text('film', array('class' => 'textInput')); ?></p>
<br>
<?php
echo $form->end('Хочу этот фильм!');
?>
</div>