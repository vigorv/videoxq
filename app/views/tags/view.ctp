<div class="contentCol">
<h1>Посты с тегом &laquo;<?php echo h($tag['Tag']['title']); ?>&raquo;</h1>
<?php
foreach ($posts as $post):
echo $this->element('blog_post', array('post' => $post));
endforeach;
?>
    <div class="pages">
      <?php echo $this->element('paging'); ?>
    </div>
</div>

