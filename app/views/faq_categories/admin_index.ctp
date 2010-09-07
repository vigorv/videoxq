<div class="faqCategories index">
<h2><?php __('FaqCategories');?></h2>
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
    <th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($faqCategories as $faqCategory):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $faqCategory['FaqCategory']['id']; ?>
        </td>
        <td>
            <?php echo $html->link($faqCategory['FaqCategoryParent']['title'], array('controller'=> 'faq_categories', 'action'=>'view', $faqCategory['FaqCategoryParent']['id'])); ?>
        </td>
        <td>
            <?php echo $faqCategory['FaqCategory']['title']; ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('View', true), array('action'=>'view', $faqCategory['FaqCategory']['id'])); ?>
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $faqCategory['FaqCategory']['id'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'delete', $faqCategory['FaqCategory']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $faqCategory['FaqCategory']['id'])); ?>
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
        <li><?php echo $html->link(__('New FaqCategory', true), array('action'=>'add')); ?></li>
        <li><?php echo $html->link(__('List Faq Items', true), array('controller'=> 'faq_items', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Faq Item', true), array('controller'=> 'faq_items', 'action'=>'add')); ?> </li>
    </ul>
</div>
