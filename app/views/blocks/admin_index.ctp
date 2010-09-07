<div class="blocks index">
<h2><?php __('Blocks');?></h2>
<p>

<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
        <?php echo $html->link('index', array('action'=>"index/FIND_IN_SET('index', `enabled_controller`)")); ?></li>
        <?php echo $html->link('!index', array('action'=>"index/FIND_IN_SET('index', `disabled_controller`)")); ?></li>



<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php echo $paginator->sort('id');?></th>
    <th><?php echo $paginator->sort('title');?></th>
    <th><?php echo $paginator->sort('type');?></th>
    <th><?php echo $paginator->sort('position');?></th>
    <th><?php echo $paginator->sort('order');?></th>
    <th><?php echo $paginator->sort('enabled');?></th>
    <th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($blocks as $block):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $block['Block']['id']; ?>
        </td>
        <td>
            <?php echo $block['Block']['title']; ?>
        </td>
        <td>
            <?php echo $block['Block']['type']; ?>
        </td>
        <td>
            <?php echo $block['Block']['position']; ?>
        </td>
        <td>
            <?php echo $block['Block']['order']; ?>
        </td>
        <td>
            <?php echo $block['Block']['enabled']; ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('View', true), array('action'=>'view', $block['Block']['id'])); ?>
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $block['Block']['id'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'delete', $block['Block']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $block['Block']['id'])); ?>
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
        <li><?php echo $html->link(__('New Block', true), array('action'=>'add')); ?></li>
    </ul>
</div>
