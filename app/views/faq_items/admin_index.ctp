<div class="faqItems index">
<h2><?php __('FaqItems');?></h2>
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
foreach ($faqItems as $faqItem):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $faqItem['FaqItem']['id']; ?>
		</td>
		<td>
			<?php echo $html->link($faqItem['FaqCategory']['title'], array('controller'=> 'faq_categories', 'action'=>'view', $faqItem['FaqCategory']['id'])); ?>
		</td>
		<td>
			<?php echo $faqItem['FaqItem']['title']; ?>
		</td>
		<td>
			<?php echo $faqItem['FaqItem']['text']; ?>
		</td>
		<td>
			<?php echo $faqItem['FaqItem']['num_comments']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $faqItem['FaqItem']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $faqItem['FaqItem']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $faqItem['FaqItem']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $faqItem['FaqItem']['id'])); ?>
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
		<li><?php echo $html->link(__('New FaqItem', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Faq Categories', true), array('controller'=> 'faq_categories', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Faq Category', true), array('controller'=> 'faq_categories', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Faq Comments', true), array('controller'=> 'faq_comments', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Faq Comment', true), array('controller'=> 'faq_comments', 'action'=>'add')); ?> </li>
	</ul>
</div>
