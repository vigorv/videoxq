<div class="searchWords index">
<h2><?php __('SearchWords');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php echo $paginator->sort('words');?></th>
    <th><?php echo $paginator->sort('url');?></th>
    <th><?php echo $paginator->sort('created');?></th>
    <th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($searchWords as $searchWord):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $searchWord['SearchWord']['words']; ?>
        </td>
        <td>
            <?php echo $searchWord['SearchWord']['url']; ?>
        </td>
        <td>
            <?php echo $searchWord['SearchWord']['created']; ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('View', true), array('action'=>'view', $searchWord['SearchWord']['id'])); ?>
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $searchWord['SearchWord']['id'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'delete', $searchWord['SearchWord']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $searchWord['SearchWord']['id'])); ?>
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
        <li><?php echo $html->link(__('New SearchWord', true), array('action'=>'add')); ?></li>
    </ul>
</div>
