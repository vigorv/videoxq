<p><a href="/roadmaps"><?php __('back2_roadmap_index');?></a></p>
<?php
/*
echo'<pre>';
var_dump($enters);
echo'</pre>';
//*/

function drawTree($parent)
{
	if (empty($parent['zveno']['aliases']['aname']))
	{
		$parent['zveno']['aliases']['aname'] = __('not_assigned_alias_for', true) . $parent['zveno']['referrers']['rurl'];
	}
	if (isset($parent['zveno']['a2']['lname']))
	{
		$parent['zveno']['aliases']['aname'] = $parent['zveno']['a2']['lname'] . ' <- ' . $parent['zveno']['aliases']['aname'];
	}
	//echo '<li>' . $parent['zveno']['chains']['url_id'] . ' - ' . $parent['zveno']['aliases']['aname'] . ' (' . $parent['zveno'][0]['chaincnt'] . ')';
	echo '<li>- ' . $parent['zveno']['aliases']['aname'] . ' (' . $parent['zveno'][0]['chaincnt'] . ')';
	if (!empty($parent['tree']))
	{
		echo '<ul>';
		foreach ($parent['tree'] as $t)
			drawTree($t);
		echo '</ul>';
	}
	echo'</li>';
}

$entersAlias[0] = __('from_bookmarks', true);
$entersAlias[1] = __('external_referrer', true);
if (count($enters) > 0)
{
	echo '<ul>';
	for($external = 0; $external < 2; $external++)
	{
		echo '<li><b>' . $entersAlias[$external] . '</b>';
		foreach ($enters[$external] as $e)
		{
			if (!empty($e['tree']))
			{
				echo '<ul>';
				foreach ($e['tree'] as $t)
					drawTree($t);
				echo '</ul>';
			}
		}
		echo'</li>';
	}
	echo '</ul>';
}
