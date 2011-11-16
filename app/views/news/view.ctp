<script type="text/javascript">
<!--
$(document).ready(function() {
    equalHeight($('.left-block, .center-block, .right-block'));
});
function equalHeight(group) {
  tallest = 0;
  group.each(function() {
    thisHeight = $(this).height();
    if(thisHeight > tallest) {
      tallest = thisHeight;
    }
  });
  group.height(tallest);
}
-->
</script>
<?php
//НАЧАЛО ОБРАБОТКИ ВЫВОДА ГОЛОСОВАЛКИ
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
//КОНЕЦ ОБРАБОТКИ ВЫВОДА ГОЛОСОВАЛКИ


//	$javascript->link(array('slimbox2/slimbox2.js'), false);
//	$html->css('../js/slimbox2/slimbox2.css', '', '', false);
	$html->css('global', '', '', false);
?>
			<div id="middle">
				<div class="left-block">
							<?php
                                $dir_id = $info['News']['direction_id'];
                                                if (!empty($directions_data) && $directions_data){
                                                    echo $directions->showFlatList($directions_data['list'],$directions_data['current_id'], $directions_data['level_char'], $directions_data['html_container_id']);
                                                }

                                //ПРОСТАЯ НАВИГАЦИЯ
/*
					<div id="left-menu">
						<ul>
                                $dir_id = $info['News']['direction_id'];

								$current = '';
								if (empty($dir_id))
									$current = 'class="active"';
								echo '<li ' . $current . '><a ' . $current . ' href="/news">Все категории</a></li>';
								foreach ($dirs as $d)
								{
									if (empty($d['Direction']['caption']))
										continue;
									$current = '';
									if (!empty($dir_id) && ($dir_id == $d['Direction']['id']))
										$current = 'class="active"';
									echo '<li ' . $current . '><a ' . $current . ' href="/news/index/' . $d['Direction']['id'] . '">' . $d['Direction']['caption'] . '</a></li>';
								}
						</ul>
					</div>
//*/
							?>
				</div>
				<div class="center-block">
					<div id="content-main">
					<div id="content-news-read">
				<?php
				$months = array(
						'01' => 'января',
						'02' => 'февраля',
						'03' => 'марта',
						'04' => 'апреля',
						'05' => 'мая',
						'06' => 'июня',
						'07' => 'июля',
						'08' => 'августа',
						'09' => 'сентября',
						'10' => 'октября',
						'11' => 'ноября',
						'12' => 'декабря',
					);
				echo '
				<p>' . intval(date('d', strtotime($info['News']['created']))) . ' ' . $months[date('m', strtotime($info['News']['created']))] . ' ' . date('Y', strtotime($info['News']['created'])) . ' года</p>
				<h1>' . $info['News']['title'] . '</h1>
				';

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

				echo'
					</div>
				';





	$javascript->link('jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack', false);
    $html->css('fancybox-1.3.4/jquery.fancybox-1.3.4', null, array(), false);
   
    
    $javascript->link('calendarlite/jquery.calendarlite', false);
    $javascript->link('jquery.ad-gallery', false);
    $javascript->link(array('slimbox2/slimbox2.js'), false);
    $html->css('global', '', '', false);
    $html->css('calendarlite', '', '', false);
    $html->css('jquery.ad-gallery', '', '', false);

    $javascript->link('jquery.pngFix', false);
    $matchesInfo = explode('|', $info['News']['matchesinfo']);
?>
<table cellspacing="20" cellpadding="5">
<script type="text/javascript">
  $(function() {
    $('img.image1').data('ad-desc', 'Whoa! This description is set through elm.data("ad-desc") instead of using the longdesc attribute.<br>And it contains <strong>H</strong>ow <strong>T</strong>o <strong>M</strong>eet <strong>L</strong>adies... <em>What?</em> That aint what HTML stands for? Man...');
    $('img.image1').data('ad-title', 'Title through $.data');
    $('img.image4').data('ad-desc', 'This image is wider than the wrapper, so it has been scaled down');
    $('img.image5').data('ad-desc', 'This image is higher than the wrapper, so it has been scaled down');
    var galleries = $('.ad-gallery').adGallery();
    $('#switch-effect').change(
      function() {
        galleries[0].settings.effect = $(this).val();
        return false;
      }
    );
    $('#toggle-slideshow').click(
      function() {
        galleries[0].slideshow.toggle();
        return false;
      }
    );
    $('#toggle-description').click(
      function() {
        if(!galleries[0].settings.description_wrapper) {
          galleries[0].settings.description_wrapper = $('#descriptions');
        } else {
          galleries[0].settings.description_wrapper = false;
        }
        
        return false;
      }
    );
  });
  </script>
  <style type="text/css">
  
  ul.ad-thumb-list {
    list-style-image:url(list-style.gif);
  }
   .ad-gallery {
    padding: 30px;
    background: #e1eef5;
  }
    #spisok_show{
        visibility: hidden;
         position: absolute;
    width:660px;
    border:solid #ccc 2px;
      z-index: 10;
    overflow: hidden;
    background-color:#ccc;
    opacity:0.95;
    color:#fff;
    text-align:center;
    padding:10px;
    position:fixed;
    top:2%;
    left:50%;
    
    margin-left:-300px;
    }
 
  </style>
  <script>function spisok()
{
 var obj=document.getElementById('spisok_show');
 if(obj.style.visibility=='visible')
  obj.style.visibility='hidden';
 else
  obj.style.visibility='visible';
}</script>
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
			
			//echo '<h2><a rel="fotodiv' . $match . '" href="#fotodiv' . $match . '">Фото(' . $count . ')</a></h2>';
			
            $hideContent_down = '';
            $hideContent = '';
                 
            
            //норм вывод
             $i=1;
			foreach ($ftpInfo[$dir][$match]['foto'] as $key => $val)
			{
				 //ПЕРВУЮ УЖЕ ВЫВЕЛИ
				/*
				$hideContent .= '
					<a rel="foto' . $match . '" href="http://' . $downServerAddrPort . '/' . $val . '"></a>
				';
				//*/
                
                $mass = explode('/',$val);
               
                if ((basename($val) == 'original') or (basename($val) == 'thumbs'))
                {
                    unset($ftpInfo[$dir][$match]['foto'][$key]);
                    continue;
                }
                //проверка на существование превью.
                if (!empty($ftpInfo[$dir][$match]['thumbs']))
                {
				$hideContent .= '
                    
					<li><a href="http://' . $downServerAddrPort . '/' . $val . '"><img src="http://' . $downServerAddrPort . '/'.$mass[0].'/'.$mass[1].'/'.$mass[2].'/thumbs/'.$mass[3].'" />Фото '.$i.'</a></li>
                    ';
                }
                else
                {
				$hideContent .= '
                    
					<li><a href="http://' . $downServerAddrPort . '/' . $val . '"><img src="/img/preview.png" />Фото '.$i.'</a></li>
                    ';
                }
                $i++;
            }
            $count=count($ftpInfo[$dir][$match]['foto']);
			echo '<h2><a  href="javascript:void(0)" onclick="spisok();return false;">Фото ('. $count .')</a></h2>';
                        
			//echo'<div style="display:none">' . $hideContent . '</div>';
            //если разрешение экрана больше то
            echo "<script>
            function go_to_img(a) { $(document).ready(function() { 
            var srcs = $('.ad-image img').attr('src');
            var newsrc = srcs.replace('/foto/', '/foto/original/');
            a.href = newsrc;return true;});}</script>";
            //проверка на существование оригинал пикс
            if (!empty($ftpInfo[$dir][$match]['original']))
            {
			echo'<div id="spisok_show">
            <p><a href="javascript:void(0)" onclick="spisok();return false;">Закрыть</a></p>
            
            <div id="gallery" class="ad-gallery">
            <div class="ad-image-wrapper">
            </div>
            <div class="ad-controls">
            </div>
            <div class="ad-nav">
            <div class="ad-thumbs">
            <ul class="ad-thumb-list">' .$hideContent.'</ul>
            </div>
            </div>
            </div>
            </div>';
            }
            else
            {
                echo'<div id="spisok_show">
            <p><a href="javascript:void(0)" onclick="spisok();return false;">Закрыть</a></p>
            <div id="gallery" class="ad-gallery">
            <div class="ad-image-wrapper">
            </div>
            <div class="ad-controls">
            </div>
            <div class="ad-nav">
            <div class="ad-thumbs">
            <ul class="ad-thumb-list">' . $hideContent . '</ul>
            </div>
            </div>
            </div>
            </div>';
            }
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
						$fileDesc[$i[0]] = iconv('windows-1251', 'utf-8', $i[1]);
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
				echo '<tr valign="middle"><td><a href="http://' . $downServerAddrPort . '/' . $val . '">' . $fn . '</a></td><td><a rel="video" href="#video' . $match . $key . '" title="' . $fn . '"><img src="/img/play.gif" width="19" title="' . $fn . ' - ' . __('Watch online', true) . '" alt="' . $fn . ' - ' . __('Watch online', true) . '"></a></td></tr>';
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
		//echo '<h2><a rel="foto" href="http://' . $downServerAddrPort . '/' . $ftpInfo[$dir]['foto'][0] . '">Фото(' . count($ftpInfo[$dir]['foto']) . ')</a></h2>';
		echo '<h2><a rel="fotodiv" href="#fotodiv">Фото(' . count($ftpInfo[$dir]['foto']) . ')</a></h2>';
		$hideContent = '<h3>Фотографии</h3>';
		foreach ($ftpInfo[$dir]['foto'] as $key => $val)
		{
			/*
			if (!$key) continue; //ПЕРВУЮ УЖЕ ВЫВЕЛИ
			$hideContent .= '
				<a rel="foto" href="http://' . $downServerAddrPort . '/' . $val . '"></a>
			';
			*/
			$hideContent .= '
				<a rel="foto" href="http://' . $downServerAddrPort . '/' . $val . '">' . basename($val) . '</a>
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
			echo '<tr valign="middle"><td><a href="http://' . $downServerAddrPort . '/' . $val . '">' . $fn . '</a></td><td><a rel="video" href="#video12345' . $key . '" title="' . $fn . '"><img src="/img/play.gif" width="19" title="' . $fn . ' - ' . __('Watch online', true) . '" alt="' . $fn . ' - ' . __('Watch online', true) . '"></a></td></tr>';
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
<!--
</div>
</div>
</div>
</div>
</div>
-->


					</div>
				</div>
				<div class="right-block">
<?php
    $dateArr = array();
    if (!empty($dates))
    {
    	foreach ($dates as $l)
    	{
    		$d = explode(' ', $l['News']['created']);
    		$dateArr[] = $d[0];
    	}
    }
//pr($dateArr);
	$calendar->SetDay(date('Y-m-d', strtotime($info['News']['created'])));
	$calendar->SetCategory($dir_id);
    $calendar->_jsCode_array($dateArr);
    $calendar->ShowCalendar();
?>
                <div id="calendarlite"></div>
<?php
                echo $BlockBanner->getBanner('news_right_1');
                echo $BlockBanner->getBanner('news_right_2');
?>
				</div>
			</div>
