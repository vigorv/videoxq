<div class="galleryImageComments index">
<h2><?php __('GalleryImageComments');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('gallery_image_id');?></th>
	<th><?php echo $paginator->sort('user_id');?></th>
	<th><?php echo $paginator->sort('text');?></th>
	<th><?php echo $paginator->sort('created');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($galleryImageComments as $galleryImageComment):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $galleryImageComment['GalleryImageComment']['id']; ?>
		</td>
		<td>
			<?php echo $html->link($galleryImageComment['GalleryImage']['title'], array('controller'=> 'gallery_images', 'action'=>'view', $galleryImageComment['GalleryImage']['id'])); ?>
		</td>
		<td>
			<?php echo $html->link($galleryImageComment['User']['userid'], array('controller'=> 'users', 'action'=>'view', $galleryImageComment['User']['userid'])); ?>
		</td>
		<td>
			<?php echo $galleryImageComment['GalleryImageComment']['text']; ?>
		</td>
		<td>
			<?php echo $galleryImageComment['GalleryImageComment']['created']; ?>
		</td>
		<td>
			<?php echo $galleryImageComment['GalleryImageComment']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $galleryImageComment['GalleryImageComment']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $galleryImageComment['GalleryImageComment']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $galleryImageComment['GalleryImageComment']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $galleryImageComment['GalleryImageComment']['id'])); ?>
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
		<li><?php echo $html->link(__('New GalleryImageComment', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Gallery Images', true), array('controller'=> 'gallery_images', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Gallery Image', true), array('controller'=> 'gallery_images', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
	</ul>
</div>
