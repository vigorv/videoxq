<div class="bookmarks form">
<?php echo $form->create('Basket');?>
    <fieldset>
         <legend><?php __('Add Bookmark');?></legend>
    <?php
        echo $form->input('user_id');
        echo $form->input('url');
        echo $form->input('title');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('List Bookmarks', true), array('action'=>'index'));?></li>
        <li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
    </ul>
</div>
