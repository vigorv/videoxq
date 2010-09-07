<div class="catalogItems index">
<h2><?php __('CatalogItems');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('faq_category_id');?></th>
	<th><?php echo $paginator->sort('title');?></th>
	<th><?php echo $paginator->sort('text');?></th>
	<th><?php echo $paginator->sort('num_comments');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($catalogItems as $catalogItem):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $catalogItem['CatalogItem']['id']; ?>
		</td>
		<td>
			<?php echo $html->link($catalogItem['FaqCategory']['title'], array('controller'=> 'faq_categories', 'action'=>'view', $catalogItem['FaqCategory']['id'])); ?>
		</td>
		<td>
			<?php echo $catalogItem['CatalogItem']['title']; ?>
		</td>
		<td>
			<?php echo $catalogItem['CatalogItem']['text']; ?>
		</td>
		<td>
			<?php echo $catalogItem['CatalogItem']['num_comments']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $catalogItem['CatalogItem']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $catalogItem['CatalogItem']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $catalogItem['CatalogItem']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $catalogItem['CatalogItem']['id'])); ?>
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
		<li><?php echo $html->link(__('New CatalogItem', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Faq Categories', true), array('controller'=> 'faq_categories', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Faq Category', true), array('controller'=> 'faq_categories', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Faq Comments', true), array('controller'=> 'faq_comments', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Faq Comment', true), array('controller'=> 'faq_comments', 'action'=>'add')); ?> </li>
	</ul>
</div>
