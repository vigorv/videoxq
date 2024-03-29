<div class="blogs index">
<h2><?php __('Blogs');?></h2>
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
	<th><?php echo $paginator->sort('title');?></th>
	<th><?php echo $paginator->sort('description');?></th>
	<th><?php echo $paginator->sort('created');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($blogs as $blog):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $blog['Blog']['id']; ?>
		</td>
		<td>
			<?php echo $html->link($blog['User']['username'], array('controller'=> 'users', 'action'=>'view', $blog['User']['userid'])); ?>
		</td>
		<td>
			<?php echo $blog['Blog']['title']; ?>
		</td>
		<td>
			<?php echo $blog['Blog']['description']; ?>
		</td>
		<td>
			<?php echo $blog['Blog']['created']; ?>
		</td>
		<td>
			<?php echo $blog['Blog']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $blog['Blog']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $blog['Blog']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $blog['Blog']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $blog['Blog']['id'])); ?>
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
		<li><?php echo $html->link(__('New Blog', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Blog Groups', true), array('controller'=> 'blog_groups', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Blog Group', true), array('controller'=> 'blog_groups', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Posts', true), array('controller'=> 'posts', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Post', true), array('controller'=> 'posts', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Tags', true), array('controller'=> 'tags', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Tag', true), array('controller'=> 'tags', 'action'=>'add')); ?> </li>
	</ul>
</div>
