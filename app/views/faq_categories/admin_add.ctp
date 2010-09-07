<div class="faqCategories form">
<?php echo $form->create('FaqCategory');?>
    <fieldset>
         <legend><?php __('Add FaqCategory');?></legend>
    <?php
        echo $form->input('parent_id');
        echo $form->input('title');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('List FaqCategories', true), array('action'=>'index'));?></li>
        <li><?php echo $html->link(__('List Faq Items', true), array('controller'=> 'faq_items', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Faq Item', true), array('controller'=> 'faq_items', 'action'=>'add')); ?> </li>
    </ul>
</div>
