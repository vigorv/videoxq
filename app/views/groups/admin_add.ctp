<div class="groups form">
<?php echo $form->create('Group');?>
	<fieldset>
 		<legend><?php __('Add Group');?></legend>
	<?php
		echo $form->input('active');
		echo $form->input('title');
		echo $form->input('parent_id');
		echo $form->input('Vbgroup');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Groups', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Vbgroups', true), array('controller'=> 'vbgroups', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Vbgroup', true), array('controller'=> 'vbgroups', 'action'=>'add')); ?> </li>
	</ul>
</div>
