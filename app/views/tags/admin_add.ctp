<div class="tags form">
<?php echo $form->create('Tag');?>
	<fieldset>
 		<legend><?php __('Add Tag');?></legend>
	<?php
		echo $form->input('title');
		echo $form->input('blog_id');
		echo $form->input('Post');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Tags', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Blogs', true), array('controller'=> 'blogs', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Blog', true), array('controller'=> 'blogs', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Posts', true), array('controller'=> 'posts', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Post', true), array('controller'=> 'posts', 'action'=>'add')); ?> </li>
	</ul>
</div>
