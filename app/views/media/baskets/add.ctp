<div class="bookmarks form">
<?php echo $form->create('Bookmark');?>
    <fieldset>
         <legend><?php __('Add Bookmark');?></legend>
    <?php
        echo $form->input('url');
        echo $form->input('title');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('List Bookmarks', true), array('action'=>'index'));?></li>
    </ul>
</div>
