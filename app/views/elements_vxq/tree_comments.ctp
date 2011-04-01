<div class="comments" id="comments">
<?php
    $dates = Set::extract('/created', $Comment);
    sort($dates);
    $dates = array_flip($dates);

    foreach ($Comment as $key => $commentVal)
    {
        $key++;
        $commClass = ' cNew';
        if ($commentVal['user_id'] == $authUser['userid'] && $authUser['userid'] != 0)
            $commClass = ' cUser';
        if ($commentVal['user_id'] == $Post['user_id'])
            $commClass = ' cAuthor';
        ?>
        <div class="comment<?php echo $commClass ?>" id="comm<?php echo $commentVal['id'] ?>">
            <div class="commentBar<?php echo $commClass ?>">
                <a href="<?= $app->getUserProfileUrl($commentVal['User']['userid']) ?>" class="user" <?php // style="background-image:url(<?= Configure::read('App.userPicsUrl') . $commentVal['User']['userid'] . '/' . $commentVal['UserPicture']['file_name'])" ?>><?= h($commentVal['User']['username']) ?></a>
                <em><?= $app->timeShort($commentVal['created'], '<br>') ?></em>
                <a href="#comm<?php echo $commentVal['id'] ?>" class="nu">#<?php echo $dates[$commentVal['created']]+1; ?></a>
                <?php if ($commentVal['user_id'] == $authUser['userid'] || $Post['user_id'] == $authUser['userid']): ?>
                <a href="/<?php echo $comController; ?>/delete/<?= $commentVal['id'] ?>" class="delete" onclick="return confirm('Вы точно хотите удалить этот коммент?');">Удалить</a>
                <?php endif;?>
            </div>
            <p><?php echo h($commentVal['text']) ?></p>
            <?php if ($authUser['userid'] != 0):?>
            <a id="comment<?php echo $commentVal['id']?>" href="/<?php echo $comController; ?>/add/<?= $Post['id'] ?>/<?= $commentVal['id'] ?>" class="answerLink" onClick="showCommentBox(this);return false;">ответить</a><br>
            <?php endif;?>
<?php

        if ((isset($Comment[$key]) && $Comment[$key]['level'] == $commentVal['level'])
            || (!isset($Comment[$key]) && empty($commentVal['level'])))
        {
            //pr($commentVal);
            echo '</div>';
            //echo 'close';
        }
        if ((isset($Comment[$key]) && $Comment[$key]['level'] < $commentVal['level'])
            || (!isset($Comment[$key]) && !empty($commentVal['level'])))
        {
            echo '</div>';
            if (!isset($Comment[$key]))
                $Comment[$key]['level'] = 0;
            echo str_repeat('</div>', $commentVal['level'] - $Comment[$key]['level']);
            //echo 'close parent';
        }
    }

?>
</div>
    <?php if ($authUser['userid'] != 0):?>
    <div class="answerPost">
        <a name="answerPost"></a>
        <a id="comment" href="/<?php echo $comController; ?>/add/<?= $Post['id'] ?>" onClick="showCommentBox(this);return false;">Ответить</a>
    </div>
    <?php endif;?>
