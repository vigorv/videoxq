<?php
if (empty($data['User']['ip']))
{
	echo '<h2>Определить зону по IP адресу</h2>';
	echo '<h3>Укажите ip-адрес</h3>';
}
else
{
	echo '<h3>Указанному адресу ' . $data['User']['ip'];
	if ($zone)
	{
		echo ' соответсвует зона ' . $zone;
	}
	else
	{
		echo ' не соответствует ни одна зона.';
	}
	echo '</h3>';
}

echo $form->create('', array('action' => 'whereis'));
?>
<p>
<?php echo $form->text('ip', array('class' => 'textInput')); ?>
</nobr></p>
<?php
echo $form->end('Where is?');
