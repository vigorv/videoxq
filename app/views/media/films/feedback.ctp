<div id="content">
<?php
if (!empty($search_words))
{
   foreach ($search_words as $searchWord)
   {
       echo '<h3>' . __('Perhaps what you are looking for is', true) . ' ';
       echo $html->link(__('here', true), $searchWord['SearchWord']['url']);
       echo '</h3><br>';
   }
}
?>


<?php __('Sorry, currently we do not have a movie that you\'re looking for.'); ?><br>
<?php __('You can leave a request and we will notify you when it comes out.'); ?>
<h2><?php __('Request'); ?></h2>
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
<p><label for="UserCaptcha"><?php __('Movie title'); ?><em class="required">*</em> :</label><br>
<?php echo $form->text('film', array('class' => 'textInput')); ?></p>
<br>
<?php
echo $form->end(__('I want this movie!', true));
?>
</div>