<div id="content">
<?php
if (!empty($action))
{
	echo'<h2>' . __('Your message has been sent', true) . '</h2>';
}
else
{
?>
<h2><?php __('Delete account order'); ?></h2>
<?php
//$html->css('style', null, array(), false);
echo '<form action="/users/drop/send" class="reg" method="post">';
$site = Configure::read('App.siteUrl');
echo $form->textarea('msg', array('class' => 'textInput', 'value' => __('Hello', true) . "!\n" . __('Please, delete my account from this site', true) . ".\n\nuserId: {$authUser['userid']}\nuserLogin: {$authUser['username']}\n\n[" . __('specify the reason', true) . "]")); ?></p>
<br>
<?php
echo $form->end(__('Sent', true));
}
?>
</div>
