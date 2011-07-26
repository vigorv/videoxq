<?php
if (!empty($news)) :
    if (!empty($news['News']['img']))
        $img = '<a href="/files/news/' . $news['News']['img'] . '" rel="fancybox" onclick="return stopdivx();"><img   height="120px" src="/files/news/' . $news['News']['img'] . '"></a>';
    else
        $img = '';
?>
    <li>
        <div class="news_header">
            <a href="/mobile/news/<?= $news['News']['id']; ?> " class="news_title"><?= $news['News']['title']; ?></a>
            <span class="news_date"><?= date('d.m.Y', strtotime($news['News']['created'])); ?></span>
            <div class="news_header_r">
                <a href="#" class="news_author"></a>
            </div>
        </div>
        <div class="news_content">
        <?= $img; ?>
        <?= $news['News']['txt']; ?>

    </div>
</li>

<? endif; ?>