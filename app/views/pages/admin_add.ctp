<div class="pages form">
<?php echo $form->create('Page');?>
    <fieldset>
         <legend><?php __('Add Page');?></legend>
    <?php
        echo $form->input('title');
        echo $form->input('text', array('rows' => 15));
        echo $form->input('layout');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('List Pages', true), array('action'=>'index'));?></li>
    </ul>
</div>
