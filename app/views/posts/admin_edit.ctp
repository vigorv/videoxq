<div class="posts form">
<?php echo $form->create('Post');?>
	<fieldset>
 		<legend><?php __('Edit Post');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('user_id');
		echo $form->input('blog_id');
		echo $form->input('user_picture_id');
		echo $form->input('title');
		echo $form->input('text');
		echo $form->input('hits');
		echo $form->input('Tag');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Post.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Post.id'))); ?></li>
		<li><?php echo $html->link(__('List Posts', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Blogs', true), array('controller'=> 'blogs', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Blog', true), array('controller'=> 'blogs', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List User Pictures', true), array('controller'=> 'user_pictures', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User Picture', true), array('controller'=> 'user_pictures', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Comments', true), array('controller'=> 'comments', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Comment', true), array('controller'=> 'comments', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Tags', true), array('controller'=> 'tags', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Tag', true), array('controller'=> 'tags', 'action'=>'add')); ?> </li>
	</ul>
</div>
