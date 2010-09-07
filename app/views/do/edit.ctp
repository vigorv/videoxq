<div class="doCategories form">
<?php echo $form->create('DoCategory');?>
    <fieldset>
         <legend><?php __('Edit DoCategory');?></legend>
    <?php
        echo $form->input('id');
        echo $form->input('parent_id');
        echo $form->input('title');
        echo $form->input('url');
        echo $form->input('lft');
        echo $form->input('rght');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('DoCategory.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('DoCategory.id'))); ?></li>
        <li><?php echo $html->link(__('List DoCategories', true), array('action'=>'index'));?></li>
        <li><?php echo $html->link(__('List Do Categories', true), array('controller'=> 'do', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Parent', true), array('controller'=> 'do', 'action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Adverts', true), array('controller'=> 'adverts', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Advert', true), array('controller'=> 'adverts', 'action'=>'add')); ?> </li>
    </ul>
</div>
