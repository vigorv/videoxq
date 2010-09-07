<div class="articleCategories form">
<?php echo $form->create('ArticleCategory');?>
    <fieldset>
         <legend><?php __('Add ArticleCategory');?></legend>
    <?php
        echo $form->input('parent_id');
        echo $form->input('title');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('List ArticleCategories', true), array('action'=>'index'));?></li>
        <li><?php echo $html->link(__('List Article Categories', true), array('controller'=> 'article_categories', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Article Category Parent', true), array('controller'=> 'article_categories', 'action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Articles', true), array('controller'=> 'articles', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Article', true), array('controller'=> 'articles', 'action'=>'add')); ?> </li>
    </ul>
</div>
