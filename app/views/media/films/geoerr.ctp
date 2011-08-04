<div id="content">
<?php
if (!empty($action))
{
	echo'<h2>' . __('Your message has been sent. Thank you for reporting the error.', true) . '</h2>';
}
else
{
?>
<h3><?php __('Dear User'); ?>, <?php echo $authUser['username'];?>!</h3>
<?php __('If a site is incorrectly identified your geographical location, please notify the administrator. Depends on this is available for download by you of certain films'); ?>
<h2><?php __('Report error'); ?></h2>
<?php
//$html->css('style', null, array(), false);
echo '<form action="/media/geoerr/send" class="reg" method="post">';
$site = Configure::read('App.siteUrl');
$geoPlace = '';
if (!empty($geoInfo['Geoip']['region_id']))
{
	$geoPlace .= implode(' ', array($geoInfo['city'], $geoInfo['region']));
}
else
{
	$geoPlace .= __('unknown', true);
}
echo $form->textarea('msg', array('class' => 'textInput', 'value' => __('Hello', true) . "!\n{$site} " . __('is identified geographic location as', true) . " '{$geoPlace}'.\n" . __('In fact, I\'m in another city / region', true) . ".\n\n[" . __('specify where is', true) . "]")); ?></p>
<br>
<?php
echo $form->end(__('Report error', true));
}
?>
</div>
