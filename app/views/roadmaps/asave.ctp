<?php
if ($result)
{
	$msg = __('alias_save_ok', true);
}
else
{
	$msg = __('alias_save_error', true);
}
echo'
	<h3>' . $msg . '</h3>
	<p><a href="/roadmaps/alist">' . __('back2_alias_list', true) . '</a></p>
';
