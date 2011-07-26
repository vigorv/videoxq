<?php if (!empty($news)) : ?>
    <? if (!empty($dir) && ($dir['title'] != '')): ?>
        <li>
            <h2><?= $dir['title']; ?></h2>
        </li>
    <? endif; ?>
    <li>
        <?
        foreach ($news as $news_body):
            if (!empty($news_body['News']['img']))
                $img = '<a href="/files/news/' . $news_body['News']['img'] . '" rel="fancybox" onclick="return stopdivx();"><img class="news_content_img"  height="120px" src="/files/news/' . $news_body['News']['img'] . '"></a>';
            else
                $img = '';
            ?>
        <li>
            <div class="news_header">
                <a href="/mobile/news/<?= $news_body['News']['id']; ?> " class="news_title"><?= $news_body['News']['title']; ?></a>
                <span class="news_date"><?= date('d.m.Y', strtotime($news_body['News']['created'])); ?></span>
                <div class="news_header_r">
                    <a href="#" class="news_author"></a>
                </div>
            </div>
            <div class="news_content">
                <?= $img; ?>
                <?= $news_body['News']['stxt']; ?>
                <a href="/mobile/news/<?= $news_body['News']['id']; ?>">Читать далее...</a>
            </div>
        </li>
    <? endforeach; ?>
    </li>
<? endif; ?>