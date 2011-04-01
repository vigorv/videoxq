<ul id="genres">
        <h3>Топ 10</h3>
        <?php
        foreach ($block_top_films as $post)
        {
            echo '<p><a href="/media/view/' . $post['Film']['id'] . '">'
                 . h($post['Film']['title']) . '</a></p>';
        }

        ?>
</ul>