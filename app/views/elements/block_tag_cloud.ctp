<?php
$cloud = $tagCloud->formulateTagCloud($block_tag_cloud);

$cloud = $tagCloud->shuffleTags($cloud); 
?>
<div class="tagsCloud" >
    <h3><a href="/tags">Теги:</a></h3>
    <div style='text-align:center'>
    <?php
    foreach ((array)$cloud as $tag => $value)
        echo '<a href="/tags/view/'.urlencode($tag).'" style="font-size:'.$value['size'].'%">' . h($tag) . '</a> ';
        //pr($cloud);
    ?>
    </div>
</div>