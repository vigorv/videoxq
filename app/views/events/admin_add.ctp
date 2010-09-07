<div class="events form">
<?php
//echo $validation->rules('Event');
echo $form->create('Event', array('type' => 'file'));?>
	<fieldset>
 		<legend><?php __('Add Event');?></legend>
	<?php
		echo $form->input('title');
		echo $form->input('event_category_id');
		echo $form->input('notice');
		echo $form->input('text');
		echo $form->input('access',array('options' => array('public','private')));
		echo $form->input('tags');
        echo $form->input('filename', array('class' => 'textInput', 'label' => false, 'type' => 'file', 'id' => 'ImageUpload',
                                 'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));
		
		?>
	</fieldset>
<?php echo $form->end('Готово');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Events', true), array('action'=>'index'));?></li>
	</ul>
</div>
