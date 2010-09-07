<div class="faqItems view">
<h2><?php  __('FaqItem');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $faqItem['FaqItem']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Faq Category'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($faqItem['FaqCategory']['title'], array('controller'=> 'faq_categories', 'action'=>'view', $faqItem['FaqCategory']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $faqItem['FaqItem']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Text'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $faqItem['FaqItem']['text']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Num Comments'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $faqItem['FaqItem']['num_comments']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit FaqItem', true), array('action'=>'edit', $faqItem['FaqItem']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete FaqItem', true), array('action'=>'delete', $faqItem['FaqItem']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $faqItem['FaqItem']['id'])); ?> </li>
		<li><?php echo $html->link(__('List FaqItems', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New FaqItem', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Faq Categories', true), array('controller'=> 'faq_categories', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Faq Category', true), array('controller'=> 'faq_categories', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Faq Comments', true), array('controller'=> 'faq_comments', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Faq Comment', true), array('controller'=> 'faq_comments', 'action'=>'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Faq Comments');?></h3>
	<?php if (!empty($faqItem['FaqComment'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Faq Item Id'); ?></th>
		<th><?php __('User Userid'); ?></th>
		<th><?php __('Text'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($faqItem['FaqComment'] as $faqComment):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $faqComment['id'];?></td>
			<td><?php echo $faqComment['faq_item_id'];?></td>
			<td><?php echo $faqComment['user_userid'];?></td>
			<td><?php echo $faqComment['text'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'faq_comments', 'action'=>'view', $faqComment['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'faq_comments', 'action'=>'edit', $faqComment['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'faq_comments', 'action'=>'delete', $faqComment['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $faqComment['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Faq Comment', true), array('controller'=> 'faq_comments', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
