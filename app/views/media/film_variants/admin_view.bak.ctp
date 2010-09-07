<div class="filmvarint view">
<h2><?php  __('FilmVarint');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $FilmVarint['FilmVarint']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($FilmVarint['User']['userid'], array('controller'=> 'users', 'action'=>'view', $FilmVarint['User']['userid'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Url'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $FilmVarint['FilmVariant']['url']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $FilmVarint['FilmVariant']['title']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Bookmark', true), array('action'=>'edit', $FilmVarint['FilmVariant']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Bookmark', true), array('action'=>'delete', $FilmVarint['FilmVariant']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $FilmVarint['FilmVariant']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Bookmarks', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Bookmark', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
	</ul>
</div>
