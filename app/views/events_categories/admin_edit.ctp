<div class="EventCategories form">
<?php echo $form->create('EventCategory');?>
    <fieldset>
         <legend><?php __('Edit eventCategory');?></legend>
    <?php
        echo $form->input('id');
        echo $form->input('parent_id');
        echo $form->input('title');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('EventCategory.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('EventCategory.id'))); ?></li>
        <li><?php echo $html->link(__('List EventCategories', true), array('action'=>'index'));?></li>
        <li><?php echo $html->link(__('List Event Items', true), array('controller'=> 'events', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Event Item', true), array('controller'=> 'events', 'action'=>'add')); ?> </li>
    </ul>
</div>
