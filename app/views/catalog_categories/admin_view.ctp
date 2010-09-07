<div class="catalogCategories view">
<h2><?php  __('CatalogCategory');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $catalogCategory['CatalogCategory']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Catalog Category Parent'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($catalogCategory['CatalogCategoryParent']['title'], array('controller'=> 'catalog_categories', 'action'=>'view', $catalogCategory['CatalogCategoryParent']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $catalogCategory['CatalogCategory']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Lft'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $catalogCategory['CatalogCategory']['lft']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rght'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $catalogCategory['CatalogCategory']['rght']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit CatalogCategory', true), array('action'=>'edit', $catalogCategory['CatalogCategory']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete CatalogCategory', true), array('action'=>'delete', $catalogCategory['CatalogCategory']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $catalogCategory['CatalogCategory']['id'])); ?> </li>
		<li><?php echo $html->link(__('List CatalogCategories', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New CatalogCategory', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Catalog Categories', true), array('controller'=> 'catalog_categories', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Catalog Category Parent', true), array('controller'=> 'catalog_categories', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Faq Items', true), array('controller'=> 'faq_items', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Catalog Item', true), array('controller'=> 'faq_items', 'action'=>'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Faq Items');?></h3>
	<?php if (!empty($catalogCategory['CatalogItem'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Faq Category Id'); ?></th>
		<th><?php __('Title'); ?></th>
		<th><?php __('Text'); ?></th>
		<th><?php __('Num Comments'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($catalogCategory['CatalogItem'] as $catalogItem):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $catalogItem['id'];?></td>
			<td><?php echo $catalogItem['faq_category_id'];?></td>
			<td><?php echo $catalogItem['title'];?></td>
			<td><?php echo $catalogItem['text'];?></td>
			<td><?php echo $catalogItem['num_comments'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'faq_items', 'action'=>'view', $catalogItem['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'faq_items', 'action'=>'edit', $catalogItem['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'faq_items', 'action'=>'delete', $catalogItem['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $catalogItem['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Catalog Item', true), array('controller'=> 'faq_items', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
