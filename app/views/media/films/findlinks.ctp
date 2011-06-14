<?php
$num = 1;

$isVip = (!empty($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups));

//$isWS = true;

if ($isWS)
{
	$geoIsGood = true;
}

if (($geoIsGood) && ($film["Film"]['is_license']) && ($authUser['userid']))
{
	$isWS = true;
}

echo '
	<br />
	<h3>' . __('Links List', true) . '</h3>
';

if (count($shareContent) > 0)
{
	$max = Configure::read('App.webLinksCount');
	$startFL = 0; $flCount = 0; $flStr = 'catalog/file/'; $flVipStr = 'catalog/viewv/';
	foreach($shareContent as $res)
    {
    	$isFL = strpos($res['url'], $flStr);//ЭТО ССЫЛКА ИЗ ОБМЕННИКА
		if ($isFL)
		{
			$flCount++;
		}
	}

	if ((!$isVip) && (!$isWS))//ЕСЛИ НЕ СТК, ПЕРЕМЕШИВАЕМ
	{
		$shareContent = array_slice($shareContent, 0, $max + 3);
		srand((float) microtime() * 10000000);
		if (rand(1, 10) > 9)
		{
			unset($shareContent[0]);//УДАЛЯЕМ ССЫЛКУ НА FL (ВЕРОЯТНОСТЬ ПОКАЗА 0.9)
		}
		srand((float) microtime() * 10000000);
		shuffle($shareContent);
	}
	$panelContent = '';
	$max++;//КОМПЕНСИРУЕМ, ЕСЛИ ССЫЛКА FL ОКАЖЕТСЯ НА ПОСЛЕДНЕМ МЕСТЕ
	foreach($shareContent as $res)
	{
//		echo '<h3 style="margin-bottom:0px;">' . ($num++) . '. <a target="_blank" href="' . $res['url'] . '">' . $res['title'] . '</a></h3>';
//		echo '<p>' . $res['content'] . '</p>';

    	$isFL = strpos($res['url'], $flStr);//ЭТО ССЫЛКА ИЗ ОБМЕННИКА
    	if ($isFL && !$isWS) continue;

		$recomended = '
			<div class="recomended">' . __('This link is recommending for your region', true) . '</div>
		';

		if ($isFL)
		{
	    	//if ($isVip)
	    	if (($isVip) || ($isWS))
	    	{
	    		$max++;//КОМПЕНСИРУЕМ МАКС. КОЛ-ВО ВЫВОДИМЫХ ССЫЛОК
	    		$res['url'] = str_replace($flStr, $flVipStr, $res['url']);
	    		if (empty($startFL))
	    		{
	    			if ($flCount > 1)
	    			{
						$panelContent .= '<h3 style="margin-bottom:0px;"><img src="/img/greenstar.png" width="20" /> ' . $res['title'] . ' ' . $film["Film"]["year"] . ' ';
						$panelContent .= '</h3>';
		    			$panelContent .= '<ul><li><a target="_blank" href="' . $res['url'] . '">' . $res['filename'] . '</a></li>';
	    			}
	    			else
	    			{
						$panelContent .= '<h3 style="margin-bottom:0px;"><img src="/img/greenstar.png" width="20" /> <a target="_blank" href="' . $res['url'] . '">' . $res['title'] . '</a> ' . $film["Film"]["year"] . ' ';
						$panelContent .= '<p></p></h3>';
	    			}
	    		}
	    		else
	    		{
	    			$panelContent .= '<li><a target="_blank" href="' . $res['url'] . '">' . $res['filename'] . '</a></li>';
	    		}
	    	}
	    	else
	    	{
	    		if ($startFL) continue;
	    		$panelContent .=  $recomended;
				$panelContent .= '<h3 style="margin-bottom:0px;"><img src="/img/greenstar.png" width="20" /> <a target="_blank" href="' . $res['url'] . '">' . $res['title'] . '</a> ' . $film["Film"]["year"] . '</h3><p></p>';
			}
			$startFL++;
			$max--;
		}
		else
		{
			if (!empty($startFL))
			{
				$panelContent .= '</ul>';
			}
			$startFL = 0;
			$panelContent .= '<h3 style="margin-bottom:0px;"><img src="/img/blackstar.png" width="20" />  <a target="_blank" href="' . $res['url'] . '">' . $res['title'] . '</a></h3>';
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
		echo '<h3 style="margin-bottom:0px;"><img src="/img/blackstar.png" width="20" />  <a target="_blank" href="' . $res->url . '">' . $res->title . '</a></h3>';
		echo '<p>' . $res->content . '</p>';

		if ($max-- <= 0) break;
	}
}

if ($num < 1)
{
	echo'<h3>' . __('No results for your search', true) . '</h3>';
}