<div class="bookmarks view">
<h2><?php  __('Bookmark');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $bookmark['Bookmark']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($bookmark['User']['userid'], array('controller'=> 'users', 'action'=>'view', $bookmark['User']['userid'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Url'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $bookmark['Bookmark']['url']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $bookmark['Bookmark']['title']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Bookmark', true), array('action'=>'edit', $bookmark['Bookmark']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Bookmark', true), array('action'=>'delete', $bookmark['Bookmark']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $bookmark['Bookmark']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Bookmarks', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Bookmark', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
	</ul>
</div>
