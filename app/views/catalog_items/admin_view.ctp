<div class="catalogItems view">
<h2><?php  __('CatalogItem');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $catalogItem['CatalogItem']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Faq Category'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($catalogItem['FaqCategory']['title'], array('controller'=> 'faq_categories', 'action'=>'view', $catalogItem['FaqCategory']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $catalogItem['CatalogItem']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Text'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $catalogItem['CatalogItem']['text']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Num Comments'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $catalogItem['CatalogItem']['num_comments']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit CatalogItem', true), array('action'=>'edit', $catalogItem['CatalogItem']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete CatalogItem', true), array('action'=>'delete', $catalogItem['CatalogItem']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $catalogItem['CatalogItem']['id'])); ?> </li>
		<li><?php echo $html->link(__('List CatalogItems', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New CatalogItem', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Faq Categories', true), array('controller'=> 'faq_categories', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Faq Category', true), array('controller'=> 'faq_categories', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Faq Comments', true), array('controller'=> 'faq_comments', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Faq Comment', true), array('controller'=> 'faq_comments', 'action'=>'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Faq Comments');?></h3>
	<?php if (!empty($catalogItem['FaqComment'])):?>
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
		foreach ($catalogItem['FaqComment'] as $faqComment):
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
