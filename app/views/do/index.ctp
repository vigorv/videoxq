<div class="doCategories index">
<h2><?php __('DoCategories');?></h2>
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
    <th><?php echo $paginator->sort('url');?></th>
    <th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($doCategories as $doCategory):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $doCategory['DoCategory']['id']; ?>
        </td>
        <td>
            <?php echo $html->link($doCategory['Parent']['title'], array('controller'=> 'do', 'action'=>'view', $doCategory['Parent']['id'])); ?>
        </td>
        <td>
            <?php echo $doCategory['DoCategory']['title']; ?>
        </td>
        <td>
            <?php echo $doCategory['DoCategory']['url']; ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('View', true), array('action'=>'view', $doCategory['DoCategory']['id'])); ?>
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $doCategory['DoCategory']['id'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'delete', $doCategory['DoCategory']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $doCategory['DoCategory']['id'])); ?>
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
        <li><?php echo $html->link(__('New DoCategory', true), array('action'=>'add')); ?></li>
        <li><?php echo $html->link(__('List Do Categories', true), array('controller'=> 'do', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Parent', true), array('controller'=> 'do', 'action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Adverts', true), array('controller'=> 'adverts', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Advert', true), array('controller'=> 'adverts', 'action'=>'add')); ?> </li>
    </ul>
</div>
