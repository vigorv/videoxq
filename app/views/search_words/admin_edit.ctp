<div class="searchWords form">
<?php echo $form->create('SearchWord');?>
    <fieldset>
         <legend><?php __('Edit SearchWord');?></legend>
    <?php
        echo $form->input('id');
        echo $form->input('words', array('class' => "mceNoEditor"));
        echo $form->input('url');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('SearchWord.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('SearchWord.id'))); ?></li>
        <li><?php echo $html->link(__('List SearchWords', true), array('action'=>'index'));?></li>
    </ul>
</div>
