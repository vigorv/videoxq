<div class="posts index">
<h2><?php __('Posts');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('user_id');?></th>
	<th><?php echo $paginator->sort('blog_id');?></th>
	<th><?php echo $paginator->sort('user_picture_id');?></th>
	<th><?php echo $paginator->sort('title');?></th>
	<th><?php echo $paginator->sort('text');?></th>
	<th><?php echo $paginator->sort('hits');?></th>
	<th><?php echo $paginator->sort('created');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($posts as $post):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $post['Post']['id']; ?>
		</td>
		<td>
			<?php echo $html->link($post['User']['username'], array('controller'=> 'users', 'action'=>'view', $post['User']['userid'])); ?>
		</td>
		<td>
			<?php echo $html->link($post['Blog']['title'], array('controller'=> 'blogs', 'action'=>'view', $post['Blog']['id'])); ?>
		</td>
		<td>
			<?php echo $html->link($post['UserPicture']['id'], array('controller'=> 'user_pictures', 'action'=>'view', $post['UserPicture']['id'])); ?>
		</td>
		<td>
			<?php echo $post['Post']['title']; ?>
		</td>
		<td>
			<?php echo $post['Post']['text']; ?>
		</td>
		<td>
			<?php echo $post['Post']['hits']; ?>
		</td>
		<td>
			<?php echo $post['Post']['created']; ?>
		</td>
		<td>
			<?php echo $post['Post']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $post['Post']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $post['Post']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $post['Post']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $post['Post']['id'])); ?>
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
		<li><?php echo $html->link(__('New Post', true), array('action'=>'add')); ?></li>
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
