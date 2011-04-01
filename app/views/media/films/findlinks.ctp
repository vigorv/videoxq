<?php
$num = 1;

$isVip = (!empty($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups));

//$isWS = true;

echo '
	<br />
	<h3>' . __('Links List', true) . '</h3>
';

if (count($shareContent) > 0)
{
	$max = Configure::read('App.webLinksCount');
	$startFL = 0; $flCount = 0; $flStr = 'catalog/view/'; $flVipStr = 'catalog/viewv/';
	foreach($shareContent as $res)
    {
    	$isFL = strpos($res['url'], $flStr);//ЭТО ССЫЛКА ИЗ ОБМЕННИКА
		if ($isFL)
		{
			$flCount++;
		}
	}

	$panelContent = '';
	foreach($shareContent as $res)
	{
//		echo '<h3 style="margin-bottom:0px;">' . ($num++) . '. <a target="_blank" href="' . $res['url'] . '">' . $res['title'] . '</a></h3>';
//		echo '<p>' . $res['content'] . '</p>';


    	$isFL = strpos($res['url'], $flStr);//ЭТО ССЫЛКА ИЗ ОБМЕННИКА
    	if ($isFL && !$isWS) continue;

		if ($isFL)
		{
	    	if ($isVip)
	    	{
	    		$res['url'] = str_replace($flStr, $flVipStr, $res['url']);
	    		if (empty($startFL))
	    		{
	    			if ($flCount > 1)
	    			{
						$panelContent .= '<h3 style="margin-bottom:0px;">' . ($num++) . '. ' . $res['title'] . '</h3>';
						$panelContent .= '<p>' . $res['content'] . '</p><ul>';
		    			$panelContent .= '<li><a target="_blank" href="' . $res['url'] . '">' . __('Part', true) . ' ' . ($startFL + 1) . '</a></li>';
	    			}
	    			else
	    			{
						$panelContent .= '<h3 style="margin-bottom:0px;">' . ($num++) . '. <a target="_blank" href="' . $res['url'] . '">' . $res['title'] . '</a></h3>';
						$panelContent .= '<p>' . $res['content'] . '</p>';
	    			}
	    		}
	    		else
	    		{
	    			$panelContent .= '<li><a target="_blank" href="' . $res['url'] . '">' . __('Part', true) . ' ' . ($startFL + 1) . '</a></li>';
	    		}
	    	}
	    	else
	    	{
	    		if ($startFL) continue;
				$panelContent .= '<h3 style="margin-bottom:0px;">' . ($num++) . '. <a target="_blank" href="' . $res['url'] . '">' . $res['title'] . '</a></h3>';
				$panelContent .= '<p>' . $res['content'] . '</p>';
	    	}
			$startFL++;
		}
		else
		{
			if (!empty($startFL))
			{
				$panelContent .= '</ul>';
			}
			$startFL = 0;
			$panelContent .= '<h3 style="margin-bottom:0px;">' . ($num++) . '. <a target="_blank" href="' . $res['url'] . '">' . $res['title'] . '</a></h3>';
			$panelContent .= '<p>' . $res['content'] . '</p>';
		}
	}
	echo $panelContent;
}

if (count($googleContent) > 0)
{
	$max = Configure::read('App.webLinksCount');
	foreach($googleContent as $res)
	{
		echo '<h3 style="margin-bottom:0px;">' . ($num++) . '. <a target="_blank" href="' . $res->url . '">' . $res->title . '</a></h3>';
		echo '<p>' . $res->content . '</p>';

		if ($max-- <= 0) break;
	}
}

if ($num < 1)
{
	echo'<h3>' . __('No results for your search', true) . '</h3>';
}