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
		foreach ($dirs as $d)
		{
			$c = $d['Direction']['caption'];
			if (empty($c))
			{
				$c = $d['Direction']['title'];
			}
			echo '<li><strong><a href="/news/#d' . $d['Direction']['id'] . '">' . $c . '</a></strong></li>';
		}
	}
?>
</ul>
<div class="contentColumns">
<?php
	if (!empty($lst))
	{
		if (!empty($dirs))
		{
			foreach($dirs as $d)
			{
				echo '<br />';
				$c = $d['Direction']['caption'];
				if (empty($c))
				{
					$c = $d['Direction']['title'];
				}
				echo '<a name="d' . $d['Direction']['id'] . '"></a><h3>' . $c . '</h3>';

				foreach ($lst as $l)
				{
					if (!empty($l['News']['img']))
					{
						$img = '<a href="/files/news/' . $l['News']['img'] . '" rel="fancybox" onclick="return stopdivx();"><img class="news_content_img"  height="120px" src="/files/news/' . $l['News']['img'] . '"></a>';
					}
					else
						$img = '';
			//$img = '';
						echo'
			        <div id="news_ID" class="news_item">
			            <div class="news_header">
			                <a href="/news/view/' . $l['News']['id'] . '" class="news_title">' . $l['News']['title'] . '</a>
			                <span class="news_date">' . date('d.m.Y', strtotime($l['News']['created'])) . '</span>
			                <div class="news_header_r">
			                    <a href="#" class="news_author"></a>
			                </div>
			            </div>
			            <div class="news_content">
			                ' . $img . '
			                ' . $l['News']['stxt'] . '
			                <a href="/news/view/' . $l['News']['id'] . '">Читать далее...</a>
			            </div>
			        </div>
						';
				}
			}
		}
	}
?>
</div>