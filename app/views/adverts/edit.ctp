<div class="adverts form">
<?php echo $form->create('Advert');?>
    <fieldset>
         <legend><?php __('Edit Advert');?></legend>
    <?php
        echo $form->input('id');
        echo $form->input('user_id');
        echo $form->input('do_category_id');
        echo $form->input('username');
        echo $form->input('title');
        echo $form->input('text');
        echo $form->input('phone');
        echo $form->input('email');
        echo $form->input('icq');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Advert.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Advert.id'))); ?></li>
        <li><?php echo $html->link(__('List Adverts', true), array('action'=>'index'));?></li>
        <li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Do Categories', true), array('controller'=> 'do', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Do Category', true), array('controller'=> 'do', 'action'=>'add')); ?> </li>
    </ul>
</div>
