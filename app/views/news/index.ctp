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
			if (empty($dir_id))
				$hr = '/news#d';
			else
				$hr = '/news/index/';
			echo '<li><strong><a href="' . $hr . $d['Direction']['id'] . '">' . $c . '</a></strong></li>';
			$dirs[$dk]['Direction']['cnt'] = 0;
		}
	}
?>
</ul>
<?php
/*
<ul id="menu">
    <li class="active"><a href="/index/about">Новости партнеров</a></li>
    <li ><a href="#">СТС media</a></li>
</ul>

<div class="contentColumns">
<div id="news_ID" class="news_item">
    <div class="news_header">
        <a href="/news/view/' . $l['News']['id'] . '" class="news_title">«СТС Медиа» создает объединенную производственную компанию – Story First Production</a>
        <span class="news_date">28.07.2011</span>
        <div class="news_header_r">
            <a href="#" class="news_author"></a>
        </div>
    </div>
    <div class="news_content">
    	Москва, Россия — 28 июля 2011 года — Ведущая независимая медиакомпания России «СТС Медиа» (NASDAQ: CTCM) объявляет об объединении собственного производства на базе двух компаний холдинга – «Костафильм» и «Сохо Медиа», и создании новой производственной компании – ООО «Стори Фёрст Продакшн» (Story First Production). Генеральным директором объединенной компании назначен Василий Балашов. Он также вошел в состав Правления «СТС Медиа», заняв позицию Заместителя генерального директора по производству.
        <br /><a href="/news/view/' . $l['News']['id'] . '">Читать далее...</a>
    </div>
</div>

<div id="news_ID" class="news_item">
    <div class="news_header">
        <a href="/news/view/' . $l['News']['id'] . '" class="news_title">«СТС Медиа» мировые профессионалы телеиндустрии делятся опытом</a>
        <span class="news_date">27.07.2011</span>
        <div class="news_header_r">
            <a href="#" class="news_author"></a>
        </div>
    </div>
    <div class="news_content">
    	Москва, Россия — 27 июля 2011 года — Ведущая независимая медиа компания России «СТС Медиа» (NASDAQ: CTCM) объявляет о проведении семинара одним из ведущих мировых профессионалов в области создания и производства оригинальных сериалов – Барбарой Уолл.
        <br /><a href="/news/view/' . $l['News']['id'] . '">Читать далее...</a>
    </div>
</div>

<div id="news_ID" class="news_item">
    <div class="news_header">
        <a href="/news/view/' . $l['News']['id'] . '" class="news_title">«СТС Медиа» провела консультативный совет сети СТС</a>
        <span class="news_date">26.07.2011</span>
        <div class="news_header_r">
            <a href="#" class="news_author"></a>
        </div>
    </div>
    <div class="news_content">
    	Москва, Россия — 26 июля 2011 года — Ведущая независимая медиа компания России «СТС Медиа» (NASDAQ: CTCM) объявляет о проведении Консультативного совета Сети телеканала СТС.
        <br /><a href="/news/view/' . $l['News']['id'] . '">Читать далее...</a>
    </div>
</div>


</div>
*/
?>
<div class="contentColumns">
<?php
	if (!empty($lst))
	{
		if (!empty($dirs))
		{
			foreach($dirs as $dk => $d)
			{
				if (!empty($dir_id) && ($d['Direction']['id'] != $dir_id))
				{
					continue;
				}

				echo '<br />';
				echo '<a name="d' . $d['Direction']['id'] . '"></a><h3>' . $d['Direction']['title'] . '</h3>';

				foreach ($lst as $l)
				{
					if ($l["News"]['direction_id'] != $d['Direction']['id'])
					{
						continue;
					}


					if (empty($dir_id) && ($dirs[$dk]['Direction']['cnt']++ >= 3))
					{
						echo '<h3><a href="/news/index/' . $dirs[$dk]['Direction']['id'] .'">все новости раздела "' . $dirs[$dk]['Direction']['title'] . '" &raquo;</a></h3>';
						break;
					}

					if (!empty($l['News']['img']))
					{
						$img = '<a href="/files/news/' . $l['News']['img'] . '" rel="fancybox" onclick="return stopdivx();"><img class="news_content_img"  height="120px" src="/files/news/' . $l['News']['img'] . '"></a>';
					}
					else
						$img = '';
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