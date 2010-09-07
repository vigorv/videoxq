<?php
$cloud = $tagCloud->formulateTagCloud($tags);
$cloud = $tagCloud->shuffleTags($cloud);
?>
<div class="contentCol">
    <h1>Все Теги:</h1>
    <?php
    foreach ($cloud as $tag => $value)
        echo '<a href="/tags/view/'.urlencode($tag).'" style="font-size:'.$value['size'].'%">' . h($tag) . '</a> ';
    ?>
</div>