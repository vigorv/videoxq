<div class="feedbacks form">
<?php echo $form->create('Feedback');?>
	<fieldset>
 		<legend><?php __('Add Feedback');?></legend>
	<?php
		echo $form->input('email');
		echo $form->input('film');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Feedbacks', true), array('action'=>'index'));?></li>
	</ul>
</div>
