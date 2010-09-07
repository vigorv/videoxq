<div class="galleryImageComments form">
<?php echo $form->create('EventComment');?>
    <fieldset>
         <legend><?php __('Add EventComment');?></legend>
    <?php
        echo $form->hidden('event_id');
        echo $form->inpu('parent_id');
        echo $form->input('text');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
