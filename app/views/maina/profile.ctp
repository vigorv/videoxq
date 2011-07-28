<script type="text/javascript">
<!--
	function doEdit(id, nm, val)
	{
		$('#' + id).html('');
	}
-->
</script>
<?php

	$html->addCrumb(__('Profile', true), '');

	if (!empty($authUser))
	{
?>
<h1><?php echo $authUser['username']; ?>, на - твой профиль на</h1>
<?php
		echo'
		<table cellspacing="5" cellpadding="0" border="0">
		<tr valign="middle">
			<td>' . __('Your Login', true) . '</td><td>*</td><td id="pusername"><input class="profileinput" type="text" value="' . htmlentities($authUser['username'] . '"') . '" /></td>
		</tr>
		</table>
		';
	}
	else
	{
?>
<h1>...авторизуйся, на</h1>
<?php
	}