<div class="comments index">
<h2><?php __('Comments');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('post_id');?></th>
	<th><?php echo $paginator->sort('user_id');?></th>
	<th><?php echo $paginator->sort('user_picture_id');?></th>
	<th><?php echo $paginator->sort('parent_id');?></th>
	<th><?php echo $paginator->sort('text');?></th>
	<th><?php echo $paginator->sort('lft');?></th>
	<th><?php echo $paginator->sort('rght');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($comments as $comment):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $comment['Comment']['id']; ?>
		</td>
		<td>
			<?php echo $html->link($comment['Post']['title'], array('controller'=> 'posts', 'action'=>'view', $comment['Post']['id'])); ?>
		</td>
		<td>
			<?php echo $html->link($comment['User']['username'], array('controller'=> 'users', 'action'=>'view', $comment['User']['userid'])); ?>
		</td>
		<td>
			<?php echo $html->link($comment['UserPicture']['id'], array('controller'=> 'user_pictures', 'action'=>'view', $comment['UserPicture']['id'])); ?>
		</td>
		<td>
			<?php echo $html->link($comment['Parent']['id'], array('controller'=> 'comments', 'action'=>'view', $comment['Parent']['id'])); ?>
		</td>
		<td>
			<?php echo $comment['Comment']['text']; ?>
		</td>
		<td>
			<?php echo $comment['Comment']['lft']; ?>
		</td>
		<td>
			<?php echo $comment['Comment']['rght']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $comment['Comment']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $comment['Comment']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $comment['Comment']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $comment['Comment']['id'])); ?>
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
		<li><?php echo $html->link(__('New Comment', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Posts', true), array('controller'=> 'posts', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Post', true), array('controller'=> 'posts', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List User Pictures', true), array('controller'=> 'user_pictures', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User Picture', true), array('controller'=> 'user_pictures', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Comments', true), array('controller'=> 'comments', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Parent', true), array('controller'=> 'comments', 'action'=>'add')); ?> </li>
	</ul>
</div>
