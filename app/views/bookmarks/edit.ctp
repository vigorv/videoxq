<div class="bookmarks form">
<?php echo $form->create('Bookmark');?>
    <fieldset>
         <legend><?php __('Edit Bookmark');?></legend>
    <?php
        echo $form->input('id');
        echo $form->input('url');
        echo $form->input('title');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Bookmark.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Bookmark.id'))); ?></li>
        <li><?php echo $html->link(__('List Bookmarks', true), array('action'=>'index'));?></li>
    </ul>
</div>
