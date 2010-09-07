<div class="faqComments form">
<?php echo $form->create('FaqComment');?>
    <fieldset>
         <legend><?php __('Add FaqComment');?></legend>
    <?php
        echo $form->hidden('faq_item_id');
        echo $form->input('text');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('List FaqComments', true), array('action'=>'index'));?></li>
        <li><?php echo $html->link(__('List Faq Items', true), array('controller'=> 'faq_items', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Faq Item', true), array('controller'=> 'faq_items', 'action'=>'add')); ?> </li>
    </ul>
</div>
