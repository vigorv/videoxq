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

    <div>
        <br/>
<?php 
//if (!$isWS){
?>

    <?=__('You can also search', true).' <b>"' .$search_str. '"</b> '.__('in other search engines', true).': ';?> 
    <?php
        $search_str = urlencode($search_str);
    ?>        
    <a href="http://www.google.ru/search?q=<?=$search_str?>" target="_blank">Google</a>
    <a href="http://yandex.ru/yandsearch?text=<?=$search_str?>" target="_blank">Yandex</a>
    <a href="http://go.mail.ru/search?q=<?=$search_str?>" target="_blank">Mail.ru</a>
    <a href="http://http://nova.Rambler.ru/search?query=<?=$search_str?>" target="_blank">Rambler</a>
    <a href="http://bing.com/search?q=<?=$search_str?>" target="_blank">Bing</a>
    <a href="http://search.yahoo.com/search?q=<?=$search_str?>" target="_blank">Yahoo!</a>

    </div>    
<?php
//}
?>
</div>

    