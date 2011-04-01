<?php
//$cloud = $tagCloud->formulateTagCloud($block_activity);
//pr($block_activity);
?>
<div class="activity">
        <h3>Активность</h3>
        <?php
        foreach ($block_activity as $post)
        {
            echo '<p><a href="/posts/view/' . $post['Post']['id'] . '">'
                 . h($post['Post']['title'] ? $post['Post']['title'] : '(без названия)') . '</a><br />'
                 . $app->timeAgoInWords($post[0]['created']) . '</p>';
        }

        ?>
</div>