<div class="galleryImageComments form">
<?php echo $form->create('GalleryImageComment');?>
	<fieldset>
 		<legend><?php __('Add GalleryImageComment');?></legend>
	<?php
		echo $form->input('gallery_image_id');
		echo $form->input('user_id');
		echo $form->input('text');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List GalleryImageComments', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Gallery Images', true), array('controller'=> 'gallery_images', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Gallery Image', true), array('controller'=> 'gallery_images', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
	</ul>
</div>
