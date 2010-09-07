<?php
echo $form->create('EventComment', array('id' => 'form-comment' . $parentId));
echo $form->hidden('event_id');
echo $form->hidden('parent_id');
echo $form->input('text', array('label' => false));
echo $form->end(array('label' => 'Отправить'));
?>
