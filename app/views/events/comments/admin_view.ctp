<div class="galleryImageComments view">
<h2><?php  __('GalleryImageComment');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $galleryImageComment['GalleryImageComment']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Gallery Image'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($galleryImageComment['GalleryImage']['title'], array('controller'=> 'gallery_images', 'action'=>'view', $galleryImageComment['GalleryImage']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($galleryImageComment['User']['userid'], array('controller'=> 'users', 'action'=>'view', $galleryImageComment['User']['userid'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Text'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $galleryImageComment['GalleryImageComment']['text']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $galleryImageComment['GalleryImageComment']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $galleryImageComment['GalleryImageComment']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit GalleryImageComment', true), array('action'=>'edit', $galleryImageComment['GalleryImageComment']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete GalleryImageComment', true), array('action'=>'delete', $galleryImageComment['GalleryImageComment']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $galleryImageComment['GalleryImageComment']['id'])); ?> </li>
		<li><?php echo $html->link(__('List GalleryImageComments', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New GalleryImageComment', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Gallery Images', true), array('controller'=> 'gallery_images', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Gallery Image', true), array('controller'=> 'gallery_images', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
	</ul>
</div>
