<div class="viewright"></div><ul id="menu">
    <li ><a href="/index/about">О нас</a></li>
<!--
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
                <a noref="noref" class="news_title">' . $info['News']['title'] . '</a>
                <span class="news_date">' . date('d.m.Y', strtotime($info['News']['created'])) . '</span>
                <div class="news_header_r">
                    <a href="#" class="news_author"></a>
                </div>
            </div>
            <div class="news_content_full">
	';
	if (!empty($info["News"]['img']))
	{
		echo '<center><img class="news_content_full_img" src="/files/news/small/' . $info['News']['img'] . '"/></center>';
	}
	if (!empty($info['News']['txt']))
		echo $info['News']['txt'];
	else
		echo $info['News']['stxt'];

	$javascript->link('jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack', false);
    $html->css('fancybox-1.3.4/jquery.fancybox-1.3.4', null, array(), false);

    $javascript->link('jquery.pngFix', false);
?>
<table cellspacing="20" cellpadding="5">
<?php
$hideJS = '';

for ($match = 1; $match < 20; $match++)
{
	if (!empty($ftpInfo[$dat][$match]))
	{
		echo '
		<tr valign="top">
			<td><h2>Матч ' . $match . '</h2></td><td>
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
			echo '<h2><a rel="foto' . $match . '" href="http://' . $flowServerAddr . '/' . $ftpInfo[$dat][$match]['foto'][0] . '">Фото</a></h2>';
			$hideContent = '';
			foreach ($ftpInfo[$dat][$match]['foto'] as $key => $val)
			{
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
			echo '<h2>Видео</h2><ul>';
			$hideContent = '';
			foreach ($ftpInfo[$dat][$match]['video'] as $key => $val)
			{
				echo '<li><a rel="video" href="#video' . $match . $key . '">Ролик №' . ($key + 1) . '</a></li>';
				$hideContent .= '
		 <div id="video' . $match . $key . '"><a style="width:640px; height:480px; display:block" id="ipad' . $match.$key . '" onclick="return addVideo(' . $match.$key . ', \'http://' . $flowServerAddrPort . '/' . $val . '\');"></a></div>
				';
			}
			echo '
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
	echo '
	<tr valign="top">
		<td><h2>Другое</h2></td><td>
	';
	if (!empty($ftpInfo[$dat]['foto']))
	{
		echo '<h2><a rel="foto" href="http://' . $flowServerAddr . '/' . $ftpInfo[$dat]['foto'][0] . '">Фото</a></h2>';
		$hideContent = '';
		foreach ($ftpInfo[$dat]['foto'] as $key => $val)
		{
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
		echo '<h2>Видео</h2><ul>';
		$hideContent = '';
		foreach ($ftpInfo[$dat]['video'] as $key => $val)
		{
			echo '<li><a rel="video" href="#video12345' . $key . '">Ролик №' . ($key + 1) . '</a></li>';
			$hideContent .= '
	 <div id="video12345' . $key . '"><a style="width:640px; height:480px; display:block" id="ipad12345' . $key . '" onclick="return addVideo(12345' . $key . ', \'http://' . $flowServerAddrPort . '/' . $val . '\');"></a></div>
			';
		}
		echo '
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
