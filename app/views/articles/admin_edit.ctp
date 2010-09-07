<div class="articles form">
<?php echo $form->create('Article');?>
	<fieldset>
 		<legend><?php __('Edit Article');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('user_id');
		echo $form->input('article_category_id');
		echo $form->input('title');
		echo $form->input('text');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Article.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Article.id'))); ?></li>
		<li><?php echo $html->link(__('List Articles', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Article Categories', true), array('controller'=> 'article_categories', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Article Category', true), array('controller'=> 'article_categories', 'action'=>'add')); ?> </li>
	</ul>
</div>
