<?php
 if (!empty($news)) :?>
    <? if ((!empty($dir))):?>
<li>
    <h2><?=$dir['title'];?></h2>
</li>
    <?endif;?>
<li>
    <?  foreach ($news as $l):
                if (!empty($l['News']['img']))
				$img = '<a href="/files/news/' . $l['News']['img'] . '" rel="fancybox" onclick="return stopdivx();"><img class="news_content_img"  height="120px" src="/files/news/' . $l['News']['img'] . '"></a>';
			else
				$img = '';
     ?>
    <li>
            <div class="news_header">
                <a href="/news/view/<?= $l['News']['id'];?> " class="news_title"><?= $l['News']['title'];?></a>
                <span class="news_date"><?=date('d.m.Y', strtotime($l['News']['created']));?></span>
                <div class="news_header_r">
                    <a href="#" class="news_author"></a>
                </div>
            </div>
            <div class="news_content">
                <?=$img;?>
                <?=$l['News']['stxt'];?>
                <a href="/news/view/<?=$l['News']['id']; ?>">Читать далее...</a>
            </div>
    </li>
<?endforeach;?>
    </li>
<?endif;?>