<div class="contentCol">
<?php
foreach ($posts as $post):
echo $this->element('blog_post', array('post' => $post));
endforeach;
?>
    <div class="pages">
      <?php echo $this->element('paging'); ?>
    </div>
</div>

