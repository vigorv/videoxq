<div class="catalogCategories index">
<h2><?php __('CatalogCategories');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('parent_id');?></th>
	<th><?php echo $paginator->sort('title');?></th>
	<th><?php echo $paginator->sort('lft');?></th>
	<th><?php echo $paginator->sort('rght');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($catalogCategories as $catalogCategory):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $catalogCategory['CatalogCategory']['id']; ?>
		</td>
		<td>
			<?php echo $html->link($catalogCategory['CatalogCategoryParent']['title'], array('controller'=> 'catalog_categories', 'action'=>'view', $catalogCategory['CatalogCategoryParent']['id'])); ?>
		</td>
		<td>
			<?php echo $catalogCategory['CatalogCategory']['title']; ?>
		</td>
		<td>
			<?php echo $catalogCategory['CatalogCategory']['lft']; ?>
		</td>
		<td>
			<?php echo $catalogCategory['CatalogCategory']['rght']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $catalogCategory['CatalogCategory']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $catalogCategory['CatalogCategory']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $catalogCategory['CatalogCategory']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $catalogCategory['CatalogCategory']['id'])); ?>
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
		<li><?php echo $html->link(__('New CatalogCategory', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Catalog Categories', true), array('controller'=> 'catalog_categories', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Catalog Category Parent', true), array('controller'=> 'catalog_categories', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Faq Items', true), array('controller'=> 'faq_items', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Catalog Item', true), array('controller'=> 'faq_items', 'action'=>'add')); ?> </li>
	</ul>
</div>
