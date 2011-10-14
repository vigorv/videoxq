<?php
//	$javascript->link(array('slimbox2/slimbox2.js'), false);
//	$html->css('../js/slimbox2/slimbox2.css', '', '', false);
	$html->css('global', '', '', false);
?>
			<div id="middle">
				<div class="left-block">
					<div id="left-menu">
						<ul>
							<?php
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
							?>
						</ul>
					</div>
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
							' . $img . '
							<p>' . intval(date('d', strtotime($l['News']['created']))) . ' ' . $months[date('m', strtotime($l['News']['created']))] . ' ' . date('Y', strtotime($l['News']['created'])) . ' года</p>
							<p><a href="/news/view/' . $l['News']['id'] . '">' . $l['News']['title'] . '</a></p>
							<p class="short">' . $l['News']['stxt'] . '</p>
						</div>
							';
						}
					}
				?>
					</div>
				</div>
				<div class="right-block">
				</div>
			</div>
