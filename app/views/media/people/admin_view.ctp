<?php 
pr($DATA);
$DATA=$DATA[$model];
?>
<div class="<?=$this->name?> view">
<h2><?php  __($this->name);?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<?php foreach($rows as $row=>$row_param):?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __($row); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $DATA[$row]; ?>
			&nbsp;
		</dd>
		<?php endforeach;?>
		
	</dl>
</div>
<div class="actions">
	<ul>
		<?php foreach ($actions as $name=> $action):?>
		<li><?php echo $html->link(__(str_replace('%',$this->name,$name), true), array('action'=>$action)); ?></li>
		<?php endforeach; ?>
	</ul>
</div>
