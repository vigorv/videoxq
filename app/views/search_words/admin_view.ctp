<div class="searchWords view">
<h2><?php  __('SearchWord');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $searchWord['SearchWord']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Words'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $searchWord['SearchWord']['words']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Url'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $searchWord['SearchWord']['url']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $searchWord['SearchWord']['created']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit SearchWord', true), array('action'=>'edit', $searchWord['SearchWord']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete SearchWord', true), array('action'=>'delete', $searchWord['SearchWord']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $searchWord['SearchWord']['id'])); ?> </li>
		<li><?php echo $html->link(__('List SearchWords', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New SearchWord', true), array('action'=>'add')); ?> </li>
	</ul>
</div>
