<?php
	echo '<form name="aliasform" action="/roadmaps/asave" method="post">';
if (empty($alias))
{
	$msg = __('new_alias', true);
}
else
{
	$msg = __('edit_alias', true);
	echo $form->hidden('id', array("name" => "data[Alias][id]", "value" => $alias["Alias"]["id"]));
}
echo'
	<h3>' . $msg . '</h3>
' . $form->input('Alias.name',	array("name" => "data[Alias][name]",	"value" => $alias["Alias"]["name"])) . '
' . $form->input('Alias.url',	array("name" => "data[Alias][url]",		"value" => $alias["Alias"]["url"])) . '
' . $form->input('Alias.power',	array("name" => "data[Alias][power]",	"value" => $alias["Alias"]["power"])) . '
' . $form->input('Alias.except',	array("type" => "checkbox", "name" => "data[Alias][except]",	"value" => 1,	"checked" => (($alias["Alias"]["except"]) ? "checked" : ""))) . '
' . $form->end('Submit') . '

	<p><a href="/roadmaps/alist">' . __('back2_alias_list', true) . '</a></p>
	</form>
';
