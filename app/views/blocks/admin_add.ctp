<div class="blocks form">
<?php echo $form->create('Block');?>
    <fieldset>
         <legend><?php __('Add Block');?></legend>
    <?php

        echo $form->input('title');
        echo $form->input('controller');
        echo $form->input('method');
        echo $form->input('element');
        echo $form->input('arguments');
        echo $form->input('content', array('class' => "mceNoEditor"));
        echo $form->input('type');
        echo $form->input('position');
        echo $form->input('order');
        echo $form->input('enabled');
        echo $form->input('enabled_controller');
        echo $form->input('enabled_action');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('List Blocks', true), array('action'=>'index'));?></li>
    </ul>
</div>
