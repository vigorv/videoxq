<?php
echo '<h2>' . __('classificator', true) . ' </h2>';
echo'
	<p><a href="/roadmaps">' . __('back', true) . '</a></p>
	<p><a href="/roadmaps/aform">' . __('new_alias', true) . '</a></p>
';
if (!empty($aliases))
{
	echo'<table width="50%" border="1">';
	foreach($aliases as $a)
	{
		echo'
		<tr>
			<td>' . (($a['Alias']['except']) ? '<font color="red"><span title="' . __('exception', true) . '">(!)</span></font> ' : '') .
			 $a['Alias']['name'] . ' = <b>' . $a['Alias']['url'] . '</b></td>
			<td>
			<a href="/roadmaps/aform/' . $a['Alias']['id'] . '">' . __('edit', true) . '</a>
			<a href="#" onclick="if (confirm(\'' . __('are_you_sure', true) . '\')) {location.href=\'/roadmaps/adel/' . $a['Alias']['id'] . '\'} return false;">' . __('delete', true) . '</a>
			<a title="deletes only chains" href="#" onclick="if (confirm(\'' . __('are_you_sure', true) . '\')) {location.href=\'/roadmaps/adel/' . $a['Alias']['id'] . '/chains\'} return false;">' . __('drop_chains', true) . '</a>
			</td>
		</tr>
		';
	}
	echo "</table>";
}
else
{
	echo '
		<h3>' . __('no_aliases', true) . '</h3>
	';
}
echo'
	<p><a href="/roadmaps">' . __('back', true) . '</a></p>
';
