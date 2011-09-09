<table height="300"><tr valign="top"><td>
<div class="contentColumns">
<?php
	echo '<h3>Вашему адресу ' . $_SERVER['REMOTE_ADDR'];
	if ($zone)
	{
		echo ' соответствует регион ' . $zone;
	}
	else
	{
		echo ' не соответствует ни один регион.';
	}
	echo '</h3>';
?>
</div>
</td></tr></table>