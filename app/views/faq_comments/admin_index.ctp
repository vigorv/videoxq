<?php 
       //pr($DATA);
//        die();
?>
<div class="<?=$this->name?> index">
<h2><?php __($this->name);?></h2>
<p>
<?php
///die();
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<?php foreach ($rows as $row =>$row_param):?>
	<th><?php echo $paginator->sort($row);?></th>
	<?php endforeach;?>
	
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;

pr($usedModels);

foreach ($DATA as $data):
	$_data=$data;
	$data=$data[$model];
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<?php foreach ($data as $name=> $row):?>
		<td>
		<?php 
		//echo $usedModels[$name];
		if(isset($usedModels[$name]))
		{ $useModel=$_data[$usedModels[$name]];
		  //$thisModel=$_data[$useModel]['title'];
		  	//pr($useModel['title']);
			//pr($usedModels[$name]);
		  	$useController=(Inflector::pluralize($usedModels[$name]));
			echo $html->link($useModel['title'], array('controller'=>$useController, 'action'=>'view', $row));
			//echo $html->link("!!", array('controller'=>'galleries', 'action'=>'view', $row));
		}
		else echo $row;
		?>
		</td>
		<? endforeach;?>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $data['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $data['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $data['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $data['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="actions">
	<ul>
		<?php foreach ($actions as $name=> $action):?>
		<li><?php echo $html->link(__(str_replace('%',$this->name,$name), true), array('action'=>$action)); ?></li>
		<?php endforeach; ?>
		
	</ul>
</div>
