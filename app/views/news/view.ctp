<div class="viewright"></div><ul id="menu">
<!--

    <li ><a href="/index/about">О нас</a></li>
    <li ><a href="#">Underground</a></li>
    <li ><a href="#">Наша деятельность</a></li>
    <li ><a href="#">Online-трансляции</a></li>
-->
    <li class="active"><strong><a href="/news">Наши проекты</a></strong></li>
<?php
	if (!empty($dirs))
	{
		foreach ($dirs as $dk => $d)
		{
			$c = $d['Direction']['caption'];
			if (empty($c))
			{
				$c = $d['Direction']['title'];
			}
			echo '<li><strong><a href="/news/index/' . $d['Direction']['id'] . '">' . $c . '</a></strong></li>';
		}
	}
?>
</ul>
<div class="contentColumns">
<div id="cColumn_main" class="contentColumn_69p">
    <div class="news_items">
    <div class="news_item_full">
<?php
if (!empty($info))
{
	echo '
        <div class="news_header">
                <a noref class="news_title">' . $info['News']['title'] . '</a>
                <span class="news_date">' . date('d.m.Y', strtotime($info['News']['created'])) . '</span>
                <div class="news_header_r">
                    <a href="#" class="news_author"></a>
                </div>
            </div>
            <div class="news_content_full">
	';

if (!empty($block_poll))
{
	ob_start();
	$voteMsg = '';
?>
<a name="poll"></a><br /><h2>Голосование</h2>

<table><tr valign="top">
<?php
	foreach($block_poll as $bp)
	{
		extract($bp);
		if (!empty($Poll))
		{
?>
<td width="330" style="padding-right:20px;">
<div class="polls">
<p style="text-align: center;"><strong><?php echo $Poll['title']?></strong></p>
<div class="polls-ans">
<ul class="polls-ul">


<?php

if ($main_voting_voted[$Poll['id']]
    || ($authUser['userid'] && strpos($Poll['voters'], ',' . $authUser['userid'] . ',') !== false))
{
    foreach ($Poll['data'] as $answer)
    {
    ?>
        <li>
        <?php echo $answer['answer'];?>(<?php echo $answer['percent']?>%, <?php echo $app->pluralForm($answer['voters'], array('голос', 'голоса', 'голосов'))?>)
        <div style="width: <?php echo $answer['width']?>%;" class="pollbar" /></div>
        </li>
    <?php
    }
}
else
{
	echo $form->create('Poll', array('action' => 'vote', 'id' => "formid" . $Poll['id']));
	echo $form->hidden('redirect', array('value' => $this->here . '#poll', 'id' => "redirectid" . $Poll['id']));
	echo $form->hidden('id', array('value' => $Poll['id'], 'id' => "hiddenid" . $Poll['id']));
	echo '<li>';
array_unshift($Poll['answers'], 'value to be killed');//ВСТАВЛЯЕМ ЗНАЧЕНИЕ В НАЧАЛО ЧТОБЫ ИНКРЕМЕНТИРОВАТЬ ИНДЕКСЫ ОСТАЛЬНЫХ ЭЛЕМЕНТОВ
unset($Poll['answers'][0]);
	if ($Poll["multiple"])
	{
		foreach ($Poll['answers'] as $key => $answer)
		{
			echo '<input type="checkbox" id="chk_' . $Poll['id'] . '_' . $key . '" name="data[Poll][vote][' . $key . ']" value="1" />' . $answer . '</li><li>';
//			echo $form->input($answer, array('name' => 'data[Poll][vote][' . $key . ']', 'type' => 'checkbox', 'value' => 1, 'id' => "chk_{$Poll['id']}_" . $key)) . '</li><li>';
		}
	}
	else
	{
		foreach ($Poll['answers'] as $key => $answer)
		{
			echo '<li><input type="radio" name="data[Poll][vote]" value="' . $key . '" id="radio_' . $Poll['id'] . '_' . $key . '" /> ' . $answer;
			if ($key == count($Poll['answers']) - 1)
			{
				echo '<input type="text" name="data[Poll][other]" value="" id="other_' . $Poll['id'] . '_' . $key . '" />';
			}
			echo '</li>';
		}
	}
	//	echo $form->radio('vote', $Poll['answers'], array('legend' => false, 'separator' => '</li><li>'));
	//echo $form->input('vote', array('legend' => false, 'separator' => '<br>',
	//                                'options' => $Poll['answers'], 'type' => 'radio'));
	echo $form->end('Голосовать', array('id' => 'submitid' . $Poll['id']));
}
?>
</ul>
<p style="text-align: center;">Всего проголосовало: <strong><?php echo $Poll['total_votes']?></strong></p>
</div>
</div>
</td>
<?php
		}
	}
?>
</tr></table>
<?php
	$pollContent = ob_get_clean();
}

	echo'
            	<div id="newstext">
	';
//$info["News"]['img'] = '';
	if (!empty($info["News"]['img']))
	{
		echo '<a rel="attach" href="/files/news/' . $info['News']['img'] . '"><img class="news_content_full_img" src="/files/news/small/' . $info['News']['img'] . '" /></a>';
	}

	if (strlen($info['News']['txt']) > strlen($info['News']['stxt']))
		$txt = $info['News']['txt'];
	else
		$txt = $info['News']['stxt'];

	if (!empty($pollContent))
	{
		if (strpos($txt, '{POLL_CONTENT}') === false)
			echo $pollContent . $txt;
		else
			echo str_replace('{POLL_CONTENT}', $pollContent, $txt);
	}
	else
		echo $txt;

	echo'</div>';

	$javascript->link('jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack', false);
    $html->css('fancybox-1.3.4/jquery.fancybox-1.3.4', null, array(), false);

    $javascript->link('jquery.pngFix', false);
    $matchesInfo = explode('|', $info['News']['matchesinfo']);
?>
<table cellspacing="20" cellpadding="5">
<?php
//ДЛЯ ОТОБРАЖЕНИЯ ФОТО ИЗ ТЕЛА НОВОСТИ
		$hideJS = '
					$("a[rel=attach]").fancybox({
				        "zoomSpeedIn":  0,
				        "zoomSpeedOut": 0,
				        "overlayShow":  true,
				        "overlayOpacity": 0.8,
						"showCloseButton": true
					});
			';

$dir = $dat;
if (!empty($info['News']['ftpdir']))
{
	$dir = $info['News']['ftpdir'];
}

for ($match = 1; $match < 20; $match++)
{
	if (!empty($ftpInfo[$dir][$match]))
	{
		$matchName = 'Матч ' . $match . '';
		if (!empty($matchesInfo[$match - 1]))
		{
			$matchName = $matchesInfo[$match - 1];
		}
		echo '
		<tr valign="top">
			<td><h2>' . $matchName . '</h2></td><td>
		';
		if (!empty($ftpInfo[$dir][$match]['foto']))
		{
			$hideJS .= '
					$("a[rel=foto' . $match . ']").fancybox({
				        "zoomSpeedIn":  0,
				        "zoomSpeedOut": 0,
				        "overlayShow":  true,
				        "overlayOpacity": 0.8,
						"showCloseButton": true
					});
			';
			$count=count($ftpInfo[$dir][$match]['foto']);
			echo '<h2><a rel="foto' . $match . '" href="http://' . $flowServerAddr . '/' . $ftpInfo[$dir][$match]['foto'][0] . '">Фото('.$count.')</a></h2>';
			//echo '<h2><a rel="fotodiv' . $match . '" href="#fotodiv' . $match . '">Фото(' . $count . ')</a></h2>';
			$hideContent = '<h3>Фотографии</h3>';
			foreach ($ftpInfo[$dir][$match]['foto'] as $key => $val)
			{
				if (!$key) continue; //ПЕРВУЮ УЖЕ ВЫВЕЛИ
				/*
				$hideContent .= '
					<a rel="foto' . $match . '" href="http://' . $flowServerAddr . '/' . $val . '"></a>
				';
				//*/
				$hideContent .= '
					<a rel="foto' . $match . '" href="http://' . $flowServerAddr . '/' . $val . '">' . basename($val) . '</a>
				';
			}
			//echo'<div style="display:none">' . $hideContent . '</div>';
			echo'<div style="display:none"><div id="fotodiv' . $match . '">' . $hideContent . '</div></div>';
			$hideJS .= '
					$("a[rel=fotodiv' . $match . ']").fancybox({
						"zoomSpeedIn":  0,
						"autoDimensions": false,
						"width": 450,
						"height": "auto",
				        "zoomSpeedOut": 0,
				        "overlayShow":  true,
				        "overlayOpacity": 0.8,
						"showCloseButton": true
						//"onComplete": function() { $(this.href).toggle(); return false; },
						//"onClosed": function() { $(this.href).toggle(); return false; }
					});
			';
		}
	?>
	</td><td>
	<?php
		if (!empty($ftpInfo[$dir][$match]['video']))
		{
			$fileDesc = array();
			if (!empty($ftpInfo[$dir][$match]['info']))
			{
				$infoTxt = preg_split('/[\r\n]{1,}/', $ftpInfo[$dir][$match]['info']);
				foreach ($infoTxt as $it)
				{
					$i = explode('---', $it);
					if (!empty($i[1]))
					{
						$fileDesc[$i[0]] = iconv('windows-1251', 'utf8', $i[1]);
					}
				}
			}

			echo '<h2>Видео</h2><table cellspacing="3">';
			$hideContent = '';
			foreach ($ftpInfo[$dir][$match]['video'] as $key => $val)
			{
//				echo '<li><a rel="video" href="#video' . $match . $key . '">Ролик №' . ($key + 1) . '</a></li>';
				$fn = basename($val);
				if (!empty($fileDesc[$fn]))
				{
					$fn = $fileDesc[$fn];
				}
				else
				{
					$fn = 'Скачать ролик №' . ($key + 1);
				}
				echo '<tr valign="middle"><td><a href="http://' . $flowServerAddr . '/' . $val . '">' . $fn . '</a></td><td><a rel="video" href="#video' . $match . $key . '" title="' . $fn . '"><img src="/img/play.gif" width="19" title="' . $fn . ' - ' . __('Watch online', true) . '" alt="' . $fn . ' - ' . __('Watch online', true) . '"></a></td></tr>';
				$hideContent .= '
		 <div id="video' . $match . $key . '"><a style="width:640px; height:480px; display:block" id="ipad' . $match.$key . '" onclick="return addVideo(' . $match.$key . ', \'http://' . $flowServerAddrPort . '/' . $val . '\');"></a></div>
				';
			}
			echo '</table>
	<div style="display: none">
		' . $hideContent . '
	</div>
			';
		}
		echo '</td></tr>';
	}
}

if (!empty($ftpInfo[$dir]['foto']) || !empty($ftpInfo[$dir]['video']))
{
	$otherName = 'Другое';
	if (!empty($matchesInfo[count($matchesInfo) - 1]))
	{
		$otherName = $matchesInfo[count($matchesInfo) - 1];
	}
	echo '
	<tr valign="top">
		<td><h2>' . $otherName . '</h2></td><td>
	';
	if (!empty($ftpInfo[$dir]['foto']))
	{
		//echo '<h2><a rel="foto" href="http://' . $flowServerAddr . '/' . $ftpInfo[$dir]['foto'][0] . '">Фото(' . count($ftpInfo[$dir]['foto']) . ')</a></h2>';
		echo '<h2><a rel="fotodiv" href="#fotodiv">Фото(' . count($ftpInfo[$dir]['foto']) . ')</a></h2>';
		$hideContent = '<h3>Фотографии</h3>';
		foreach ($ftpInfo[$dir]['foto'] as $key => $val)
		{
			/*
			if (!$key) continue; //ПЕРВУЮ УЖЕ ВЫВЕЛИ
			$hideContent .= '
				<a rel="foto" href="http://' . $flowServerAddr . '/' . $val . '"></a>
			';
			*/
			$hideContent .= '
				<a rel="foto" href="http://' . $flowServerAddr . '/' . $val . '">' . basename($val) . '</a>
			';
		}
		$hideJS .= '
				$("a[rel=fotodiv]").fancybox({
					"zoomSpeedIn":  0,
					"autoDimensions": false,
					"width": 450,
					"height": "auto",
			        "zoomSpeedOut": 0,
			        "overlayShow":  true,
			        "overlayOpacity": 0.8,
					"showCloseButton": true
				});
		';
		//echo'<div style="display:none">' . $hideContent . '</div>';
		echo'<div style="display:none"><div id="fotodiv">' . $hideContent . '</div></div>';
	}
?>
</td><td>
<?php
	if (!empty($ftpInfo[$dir]['video']))
	{
		$fileDesc = array();
		if (!empty($ftpInfo[$dir]['info']))
		{
			$infoTxt = preg_split('/[\r\n]{1,}/', $ftpInfo[$dir]['info']);
			foreach ($infoTxt as $it)
			{
				$i = explode('---', $it);
				if (!empty($i[1]))
				{
					$fileDesc[$i[0]] = iconv('windows-1251', 'utf8', $i[1]);
				}
			}
		}

		echo '<h2>Видео</h2><table cellspacing="3">';
		$hideContent = '';
		foreach ($ftpInfo[$dir]['video'] as $key => $val)
		{
			$fn = basename($val);
			if (!empty($fileDesc[$fn]))
			{
				$fn = $fileDesc[$fn];
			}
			else
			{
				$fn = 'Скачать ролик №' . ($key + 1);
			}
			echo '<tr valign="middle"><td><a href="http://' . $flowServerAddr . '/' . $val . '">' . $fn . '</a></td><td><a rel="video" href="#video12345' . $key . '" title="' . $fn . '"><img src="/img/play.gif" width="19" title="' . $fn . ' - ' . __('Watch online', true) . '" alt="' . $fn . ' - ' . __('Watch online', true) . '"></a></td></tr>';
			$hideContent .= '
	 <div id="video12345' . $key . '"><a style="width:640px; height:480px; display:block" id="ipad12345' . $key . '" onclick="return addVideo(12345' . $key . ', \'http://' . $flowServerAddrPort . '/' . $val . '\');"></a></div>
			';
		}
/*
echo '<li><a rel="video" href="#video12345">Ролик</a></li>';
$hideContent .= '
	 <div id="video12345"><a style="width:640px; height:480px; display:block" id="ipad12345" onclick="return addVideo(12345, \'http://92.63.196.82:82/a/1.mp4\');"></a></div>
';
//*/
		echo '</table>
<div style="display: none">
	' . $hideContent . '
</div>
		';
	}
	echo '</td></tr>';
}
?>
</table>
<script type="text/javascript">
<!--
$(document).ready(function() {
	$("a[rel=video]").fancybox({
        'zoomSpeedIn':  0,
        'zoomSpeedOut': 0,
        'overlayShow':  true,
        'overlayOpacity': 0.8,
        'showNavArrows': false,
		'onComplete': function() { $(this.href + " a").trigger('click'); return false; }
	});
<?php
echo $hideJS;
?>
	$("a[rel=foto]").fancybox({
        'zoomSpeedIn':  0,
        'zoomSpeedOut': 0,
        'overlayShow':  true,
        'overlayOpacity': 0.8,
		'showCloseButton': true
	});

});

		function addVideo(num, path) {
			document.getElementById("ipad" + num).href=path;
			document.getElementById("video" + num).style.display="";
			$f("ipad" + num, "/js/flowplayer/flowplayer-3.2.5.swf",
								{plugins: {
									h264streaming: {
										url: "/js/flowplayer/flowplayer.pseudostreaming-3.2.5.swf"
												 }
	                             },
								clip: {
									provider: "h264streaming",
									autoPlay: true,
									scaling: "fit",
									autoBuffering: true,
									scrubber: true
								},
								canvas: {
									backgroundGradient: "none",
									backgroundColor: "#000000"
								}
					}
						).ipad();
			return false;
		}

-->
</script>
<script type="text/javascript" src="/js/flowplayer/flowplayer-3.2.4.min.js"></script>
<script type="text/javascript" src="/js/flowplayer/flowplayer.ipad-3.2.1.js"></script>
<?php
}
?>

</div>
</div>
</div>
</div>
</div>
