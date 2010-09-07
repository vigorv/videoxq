<div class="faqCategories form">
<?php echo $form->create('EventCategory');?>
    <fieldset>
         <legend><?php __('Add NewsCategory');?></legend>
    <?php
        echo $form->input('parent_id');
        echo $form->input('title');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('List NewsCategories', true), array('action'=>'index'));?></li>
        <li><?php echo $html->link(__('List News Items', true), array('controller'=> 'events', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New News Item', true), array('controller'=> 'events', 'action'=>'add')); ?> </li>
    </ul>
</div>
