<div class="<?=$this->name?> form">
<?php echo $form->create($model);?>
	<fieldset>
 		<legend><?php __("Add {$this->name}");?></legend>
	<?php
		foreach ($rows as $row =>$row_param):
		$options=array();
		if(isset($editRowsSettings[$row]['options']))$options=$editRowsSettings[$row]['options'];
		echo $form->input($row,$options)."\n";
		endforeach;
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<?php foreach ($actions as $name=> $action):?>
		<li><?php echo $html->link(__(str_replace('%',$this->name,$name), true), array('action'=>$action)); ?></li>
		<?php endforeach; ?>
	</ul>
</div>
