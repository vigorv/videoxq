<div class="blogs view">
<h2><?php  __('Blog');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blog['Blog']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($blog['User']['username'], array('controller'=> 'users', 'action'=>'view', $blog['User']['userid'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blog['Blog']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blog['Blog']['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blog['Blog']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blog['Blog']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Blog', true), array('action'=>'edit', $blog['Blog']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Blog', true), array('action'=>'delete', $blog['Blog']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $blog['Blog']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Blogs', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Blog', true), array('action'=>'add')); ?> </li>
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
<div class="related">
	<h3><?php __('Related Blog Groups');?></h3>
	<?php if (!empty($blog['BlogGroup'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Blog Id'); ?></th>
		<th><?php __('Title'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($blog['BlogGroup'] as $blogGroup):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $blogGroup['id'];?></td>
			<td><?php echo $blogGroup['blog_id'];?></td>
			<td><?php echo $blogGroup['title'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'blog_groups', 'action'=>'view', $blogGroup['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'blog_groups', 'action'=>'edit', $blogGroup['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'blog_groups', 'action'=>'delete', $blogGroup['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $blogGroup['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Blog Group', true), array('controller'=> 'blog_groups', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Posts');?></h3>
	<?php if (!empty($blog['Post'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Blog Id'); ?></th>
		<th><?php __('User Picture Id'); ?></th>
		<th><?php __('Title'); ?></th>
		<th><?php __('Text'); ?></th>
		<th><?php __('Hits'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($blog['Post'] as $post):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $post['id'];?></td>
			<td><?php echo $post['user_id'];?></td>
			<td><?php echo $post['blog_id'];?></td>
			<td><?php echo $post['user_picture_id'];?></td>
			<td><?php echo $post['title'];?></td>
			<td><?php echo $post['text'];?></td>
			<td><?php echo $post['hits'];?></td>
			<td><?php echo $post['created'];?></td>
			<td><?php echo $post['modified'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'posts', 'action'=>'view', $post['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'posts', 'action'=>'edit', $post['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'posts', 'action'=>'delete', $post['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $post['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Post', true), array('controller'=> 'posts', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Tags');?></h3>
	<?php if (!empty($blog['Tag'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Title'); ?></th>
		<th><?php __('Blog Id'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($blog['Tag'] as $tag):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $tag['id'];?></td>
			<td><?php echo $tag['title'];?></td>
			<td><?php echo $tag['blog_id'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'tags', 'action'=>'view', $tag['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'tags', 'action'=>'edit', $tag['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'tags', 'action'=>'delete', $tag['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $tag['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Tag', true), array('controller'=> 'tags', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
