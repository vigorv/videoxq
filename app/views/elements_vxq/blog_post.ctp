<?php
extract($post);
$postPreview = $app->truncateText($Post['text']);
?>
    <div class="post">
        <h2><a href="/posts/view/<?= $Post['id'] ?>"><?= h($Post['title'] ? $Post['title'] : '(без названия)') ?></a><span>&nbsp;&nbsp;&nbsp;&#8592;&nbsp;&nbsp;&nbsp;<a href="<?= $app->getUserProfileUrl($User['userid']) ?>" class="user" <!--style="background-image:url(img/avatars/k/keitaro/20.jpg)"-->><?= h($User['username']) ?></a></span></h2>
        <p><?= $app->purifyHtml($postPreview); ?></p>
        <?php
        if ($postPreview != $Post['text']):
         ?>
        <strong class="textCut"><a href="/posts/view/<?= $Post['id'] ?>">Читать дальше &#8594;</a></strong>
        <?php
        endif;
        if (!empty($Tag)):
        ?>
        <p class="tags">Теги: <?php
        $tags = array();
        foreach ($Tag as $postTag)
        {
            $tags[] = '<a href="/tags/view/'.urlencode($postTag).'">' . h($postTag) . '</a>';
        }
        echo implode(', ', $tags) . '</p>';
        endif;
        ?>
        <div class="contolsBar">
            <a href="/posts/view/<?= $Post['id'] ?>#comments"><?= $app->pluralForm($post[0]['commentCount'], array('комментарий', 'комментария', 'комментариев')) ?></a>
            <?php if ($Post['user_id'] == $authUser['userid']): ?>
            <a href="/posts/delete/<?= $Post['id'] ?>" class="delete" onclick="return confirm('Вы точно хотите удалить этот пост?');">Удалить</a>
            <a href="/posts/edit/<?= $Post['id'] ?>" class="edit">Редактировать</a>
            <?php endif; ?>
            <em><?= $app->timeShort($Post['created'], '<br>') ?></em>
            <a href="#" class="fav">В избранное</a>
            <a href="#" class="plus">Плюсануть</a>
            <a href="#" class="minus">Минуснуть</a>
        </div>
    </div>
