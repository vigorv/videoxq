<div class="doCategories form">
<?php echo $form->create('FilmComment');?>
    <fieldset>
         <legend><?php __('Edit Comment');?></legend>
    <?php
        echo $form->input('id');
        echo $form->input('user_id');
        echo $form->input('film_id');
        echo $form->input('username');
        echo $form->input('email');
        echo $form->input('hidden');
        echo $form->input('text', array('class' => "mceNoEditor"));
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('FilmComment.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('FilmComment.id'))); ?></li>
        <li><?php echo $html->link(__('List Comments', true), array('action'=>'index'));?></li>
    </ul>
</div>
