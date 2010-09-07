<div class="searchWords form">
<?php echo $form->create('SearchWord');?>
    <fieldset>
         <legend><?php __('Add SearchWord');?></legend>
    <?php
        echo $form->input('words', array('class' => "mceNoEditor"));
        echo $form->input('url');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('List SearchWords', true), array('action'=>'index'));?></li>
    </ul>
</div>
