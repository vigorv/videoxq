<div class="EventCategories index">
<h2><?php __('EventCategories');?></h2>
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
foreach ($EventCategories as $EventCategory):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $EventCategory['EventCategory']['id']; ?>
        </td>
        <td>
            <?php echo $html->link($EventCategory['EventCategoryParent']['title'], array('controller'=> 'faq_categories', 'action'=>'view', $EventCategory['EventCategoryParent']['id'])); ?>
        </td>
        <td>
            <?php echo $EventCategory['EventCategory']['title']; ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('View', true), array('action'=>'view', $EventCategory['EventCategory']['id'])); ?>
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $EventCategory['EventCategory']['id'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'delete', $EventCategory['EventCategory']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $EventCategory['EventCategory']['id'])); ?>
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
        <li><?php echo $html->link(__('New EventCategory', true), array('action'=>'add')); ?></li>
        <li><?php echo $html->link(__('List Event Items', true), array('controller'=> 'events', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Event Item', true), array('controller'=> 'events', 'action'=>'add')); ?> </li>
    </ul>
</div>
