<div class="viewright"></div><ul id="menu">
<!--

    <li ><a href="/index/about">О нас</a></li>
    <li ><a href="#">Underground</a></li>
    <li ><a href="#">Наша деятельность</a></li>
    <li ><a href="#">Online-трансляции</a></li>
-->
    <li class="active"><strong><a href="/news">Новости</a></strong></li>
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
            	<div id="newstext">
	';
//$info["News"]['img'] = '';
	if (!empty($info["News"]['img']))
	{
		echo '<img class="news_content_full_img" src="/files/news/small/' . $info['News']['img'] . '" />';
	}

	if (!strlen($info['News']['txt']) > strlen($info['News']['stxt']))
		echo $info['News']['txt'];
	else
		echo $info['News']['stxt'];
	echo'
		</div>
	';
	$javascript->link('jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack', false);
    $html->css('fancybox-1.3.4/jquery.fancybox-1.3.4', null, array(), false);

    $javascript->link('jquery.pngFix', false);
    $matchesInfo = explode('|', $info['News']['matchesinfo']);
?>
<table cellspacing="20" cellpadding="5">
<?php
$hideJS = '';

for ($match = 1; $match < 20; $match++)
{
	if (!empty($ftpInfo[$dat][$match]))
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
		if (!empty($ftpInfo[$dat][$match]['foto']))
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
			$count=count($ftpInfo[$dat][$match]['foto']);
			echo '<h2><a rel="foto' . $match . '" href="http://' . $flowServerAddr . '/' . $ftpInfo[$dat][$match]['foto'][0] . '">Фото('.$count.')</a></h2>';
			$hideContent = '';
			foreach ($ftpInfo[$dat][$match]['foto'] as $key => $val)
			{
				if (!$key) continue; //ПЕРВУЮ УЖЕ ВЫВЕЛИ
				$hideContent .= '
					<a rel="foto' . $match . '" href="http://' . $flowServerAddr . '/' . $val . '"></a>
				';
			}
			echo'<div style="display:none">' . $hideContent . '</div>';
		}
	?>
	</td><td>
	<?php
		if (!empty($ftpInfo[$dat][$match]['video']))
		{
			$fileDesc = array();
			if (!empty($ftpInfo[$dat][$match]['info']))
			{
				$infoTxt = preg_split('/[\r\n]{1,}/', $ftpInfo[$dat][$match]['info']);
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
			foreach ($ftpInfo[$dat][$match]['video'] as $key => $val)
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

if (!empty($ftpInfo[$dat]['foto']) || !empty($ftpInfo[$dat]['video']))
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
	if (!empty($ftpInfo[$dat]['foto']))
	{
		echo '<h2><a rel="foto" href="http://' . $flowServerAddr . '/' . $ftpInfo[$dat]['foto'][0] . '">Фото(' . count($ftpInfo[$dat]['foto']) . ')</a></h2>';
		$hideContent = '';
		foreach ($ftpInfo[$dat]['foto'] as $key => $val)
		{
			if (!$key) continue; //ПЕРВУЮ УЖЕ ВЫВЕЛИ
			$hideContent .= '
				<a rel="foto" href="http://' . $flowServerAddr . '/' . $val . '"></a>
			';
		}
		echo'<div style="display:none">' . $hideContent . '</div>';
	}
?>
</td><td>
<?php
	if (!empty($ftpInfo[$dat]['video']))
	{
		$fileDesc = array();
		if (!empty($ftpInfo[$dat]['info']))
		{
			$infoTxt = preg_split('/[\r\n]{1,}/', $ftpInfo[$dat]['info']);
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
		foreach ($ftpInfo[$dat]['video'] as $key => $val)
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
