<?php echo $form->create('Comment', array('id' => 'form-comment' . $parentId));?>
    <?php
        echo $form->hidden('post_id');
        echo $form->hidden('user_picture_id');
        echo $form->hidden('parent_id');
        echo $form->input('text', array('label' => false));
    ?>
<?php echo $form->end(array('onclick' => 'addComment('.$parentId.')', 'label' => 'Отправить'));?>
