<?php
//pr($post);
extract($post); ?>
<div class="contentCol">
    <div class="post">
        <h2><a href="/posts/view/<?= $Post['id'] ?>"><?= h($Post['title']) ?></a><span>&nbsp;&nbsp;&nbsp;&#8592;&nbsp;&nbsp;&nbsp;<a href="<?= $app->getUserProfileUrl($User['userid']) ?>" class="user" style="background-image:url(<?= Configure::read('App.userPicsUrl') . $Post['user_id'] . '/' . $UserPicture['file_name']?>)"><?= h($User['username']) ?></a></span></h2>
        <p><p><?= $app->purifyHtml($Post['text']) ?></p></p>
        <p class="tags">Теги:
        <?php
        $tags = array();
        foreach ($Tag as $tagVal)
        {
            $tags[] = '<a href="/tags/view/'.urlencode($tagVal['title']).'">' . h($tagVal['title']) . '</a>';
        }
        echo implode(', ', $tags);
        ?>
        </p>
        <div class="contolsBar">
        <?php
        $numComments = count($Comment);
        echo $app->pluralForm($numComments, array('комментарий', 'комментария', 'комментариев'))
        ?>
            &nbsp;/&nbsp; <a href="#answerPost" class="addComment" onClick="showCommentBox(document.getElementById('comment'));">добавить</a>
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

    <?php
    echo $this->element('tree_comments', array('Comment' => $Comment, 'Post' => $Post, 'comController' => 'comments'));
    ?>
    <?php if (!empty($neighbors['prev']))
        echo '<strong class="navLeft"><a href="/posts/view/'.$neighbors['prev']['Post']['id'].'">&#8592; '.h($neighbors['prev']['Post']['title']).'</a></strong>';
    ?>
    <?php if (!empty($neighbors['next']))
        echo '<strong class="navRight"><a href="/posts/view/'.$neighbors['next']['Post']['id'].'">'.h($neighbors['next']['Post']['title']).' &#8594;</a></strong>';
    ?>
</div>
