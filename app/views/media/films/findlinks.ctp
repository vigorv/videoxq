<?php
function msgBox($txt)
{
	return '
		<div class="attention">' . $txt . '</div>
	';
}

$num = 1;
$isVip = (!empty($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups));

/*
$isWS = false;
$geoIsGood = false;
//*/

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
';
$notFound = true;
$notFoundMsg = __('Links not found', true);

if (count($shareContent) > 0)
{
	$notFound = false;
	echo '
		<h3>' . __('Links List', true) . '</h3>
	';
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

		$recomended = msgBox(__('This link is recommending for your region', true));

		if ($isFL)
		{
			$ahref = '<a target="_blank" href="' . $res['url'] . '">';
			$aplay = '<a target="_blank" href="' . $res['url'] . '/1">';//AUTOPLAY
			$aplay = str_replace('catalog/viewv', 'catalog/file', $aplay);

	    	//if ($isVip)
	    	if (($isVip) || ($isWS))
	    	{
	    		$max++;//КОМПЕНСИРУЕМ МАКС. КОЛ-ВО ВЫВОДИМЫХ ССЫЛОК
	    		$res['url'] = str_replace($flStr, $flVipStr, $res['url']);
	    		$ahref = str_replace($flStr, $flVipStr, $ahref);

	    		//if($film['Film']['id']==28632)print_r($res);

	    		if (empty($startFL))
	    		{
	    			if ($flCount > 1)
	    			{
						$panelContent .= '<h3 style="margin-bottom:0px;"><img src="/img/greenstar.png" width="20" /> ' . $res['title'] . ' ' . $film["Film"]["year"] . ' ';
						$panelContent .= '</h3>';
						$metaHref = '<a href="' . Configure::read('App.webShare') . 'catalog/meta/' . $film['Film']['id'] . '/1">';
		    			$panelContent .= '
			    				<table><tr valign="middle">
			    					<td>' . $metaHref . '<img width="16" src="/img/icons/download-icon_16x16.png" /></a></td>
			    				 	<td style="width">' . $metaHref  . __('All Files', true) . '</a></td>
			    				 	<td></td>
			    				</tr>';
		    			$panelContent .= '<tr valign="middle">
			    					<td>' . $ahref . '<img width="16" src="/img/icons/download-icon_16x16.png" /></a></td>
			    				 	<td>' . $ahref . $res['filename'] . '</a></td>
			    				 	<td>' . $aplay . '<img width="16" src="/img/icons/play-icon_16x16.png" /></a></td>
			    				</tr>';
	    			}
	    			else
	    			{
						$panelContent .= '<table><tr valign="middle">
			    					<td><img src="/img/greenstar.png" width="20" /></td>
			    					<td>' . $ahref . '<img width="16" src="/img/icons/download-icon_16x16.png" /></a></td>
			    				 	<td><h3 style="margin-bottom:0px;">' . $ahref . $res['title'] . '</a> ' . $film["Film"]["year"] . '</h3></td>
			    				 	<td>' . $aplay . '<img width="16" src="/img/icons/play-icon_16x16.png" /></a></td>
			    				</tr></table>';
	    			}
	    		}
	    		else
	    		{
						$panelContent .= '<tr valign="middle">
			    					<td>' . $ahref . '<img width="16" src="/img/icons/download-icon_16x16.png" /></a></td>
			    				 	<td>' . $ahref . $res['filename'] . '</a></td>
			    				 	<td>' . $aplay . '<img width="16" src="/img/icons/play-icon_16x16.png" /></a></td>
			    				</tr>';
	    		}
	    	}
	    	else
	    	{
	    		if ($startFL) continue;
	    		$panelContent .=  $recomended;
				$panelContent .= '<table><tr valign="middle">
	    					<td><img src="/img/greenstar.png" width="20" /></td>
	    					<td>' . $ahref . '<img width="16" src="/img/icons/download-icon_16x16.png" /></a></td>
	    				 	<td><h3 style="margin-bottom:0px;">' . $ahref . $res['title'] . '</a> ' . $film["Film"]["year"] . '</h3></td>
	    				 	<td>' . $aplay . '<img width="16" src="/img/icons/play-icon_16x16.png" /></a></td>
	    				</tr></table>';
			}
			$startFL++;
			$max--;
		}
		else
		{
			if (!empty($startFL))
			{
				$panelContent .= '</table>';
			}
			$startFL = 0;
			if (!$isWS)//ДЛЯ ВС ССЫЛКИ НА СТОРОННИЕ РЕСУРСЫ НЕ ВЫДАЕМ
			{
				$panelContent .= '<h3 style="margin-bottom:0px;"><img src="/img/blackstar.png" width="20" />  <a target="_blank" href="' . $res['url'] . '">' . $res['title'] . '</a></h3>';
				$panelContent .= '<p>' . $res['content'] . '</p>';
			}
		}
	}
	echo $panelContent;
}

if ((count($googleContent) > 0) && (!$isWS))//ДЛЯ ВС ССЫЛКИ НА СТОРОННИЕ РЕСУРСЫ НЕ ВЫДАЕМ
{
	$notFound = false;
	$max = Configure::read('App.webLinksCount');
	foreach($googleContent as $res)
	{
		echo '<h3 style="margin-bottom:0px;"><img src="/img/blackstar.png" width="20" />  <a target="_blank" href="' . $res->url . '">' . $res->title . '</a></h3>';
		echo '<p>' . $res->content . '</p>';

		if ($max-- <= 0) break;
	}
}

if ($notFound)
{
	//echo'<h3>' . __('No results for your search', true) . '</h3>';
	echo'<h3>' . $notFoundMsg . '</h3>';
}