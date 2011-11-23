<style>
.pagination_nav {
    float:left;
    width:98%;
    margin-top: 20px;
    padding: 15px 0 15px 0;
    text-align: center;
    border-top: 1px solid #aaa;
}

.pagination_nav a {
    padding: 3px 5px;
    margin: 1px;
    background: #f4f4f4;
    border: 1px solid #aaa;
    text-decoration: none;
    -moz-border-radius: 5px;
    border-radius: 5px;
}
.pagination_nav a:hover{
    background: #ddd;
    color: #d00;
}
.pagination_nav strong {
    padding: 3px 5px;
    margin: 1px 5px 1px 1px;
    background: #e4e4e4;
    border: 1px solid #aaa;
    text-decoration: none;
    -moz-border-radius: 5px;
    border-radius: 5px;
}
</style>

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

<?php
    $javascript->link('calendarlite/jquery.calendarlite', false);
    $javascript->link('calendarlite/jquery.calendarlite', false);
    $javascript->link(array('slimbox2/slimbox2.js'), false);
    $html->css('global', '', '', false);
    $html->css('calendarlite', '', '', false);

	$javascript->link('jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack', false);
    $html->css('fancybox-1.3.4/jquery.fancybox-1.3.4', null, array(), false);
    $javascript->link('jquery.pngFix', false);
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
	$calendar->SetDay($dt);
	$calendar->SetCategory($dir_id);
    $calendar->_jsCode_array($dateArr);
    $calendar->ShowCalendar();

?>
<div id="middle">
				<div class="left-block">


                                                <?php
                                                if (!empty($directions_data) && $directions_data){
                                                    echo $directions->showFlatList($directions_data['list'],$directions_data['current_id'], $directions_data['level_char'], $directions_data['html_container_id']);
                                                }

/*
                                               //Старый (простой одноуровневый список) способ вывода разделов новостей

						echo '<div id="left-menu"><ul>';

								$current = '';
								if (empty($dir_id) || isset($year))
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
						echo '</ul></div>';
*/
						?>

				</div>
				<div class="center-block">
					<div id="content-main">
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
					if (!empty($lst))
					{
						foreach ($lst as $l)
						{
							if (!empty($l['News']['img']))
							{
								$img = '<a href="/files/news/' . $l['News']['img'] . '" rel="attach"><img class="news_content_img" height="120px" src="/files/news/small/' . $l['News']['img'] . '"></a>';
							}
							else
								$img = '';
							echo '
						<div class="news-block">
							<div class="news-block_date"><p>' . intval(date('d', strtotime($l['News']['created']))) . ' ' . $months[date('m', strtotime($l['News']['created']))] . ' ' . date('Y', strtotime($l['News']['created'])) . ' года</p>
							<p><a href="/news/view/' . $l['News']['id'] . '">' . $l['News']['title'] . '</a></p></div>
							<div class="news-block_text">
							' . $img . '
							' . $l['News']['stxt'] . '</div>
						</div>
							';
						}
					}
				?>



<div class="pagination_nav">
<?php
    $paginator->options(array('url'=>array('controller'=>'news', 'action'=>'index/'.$dir_id)));
    echo $this->element('paging');
?>
</div>
					</div>
				</div>
				<div class="right-block">
                <p id="calendar_nadpis">Календарь событий</p>
                <div id="calendarlite"></div>
<?php
                echo $BlockBanner->getBanner('news_right_1');
                echo $BlockBanner->getBanner('news_right_2');
?>
				</div>

			</div>
<script type="text/javascript">
<!--
$(document).ready(function() {
					$("a[rel=attach]").fancybox({
				        "zoomSpeedIn":  0,
				        "zoomSpeedOut": 0,
				        "overlayShow":  true,
				        "overlayOpacity": 0.8,
						"showCloseButton": true
					});
});
-->
</script>