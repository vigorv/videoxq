<div class="pages index">
<h2><?php __('News');?></h2>
<?php
	if (!empty($dirs))
	{
		echo '<div style="text-align: left"><ul>' . __('News', true) . ' по категориям';
		foreach ($dirs as $d)
		{
			echo '<li><a href="/admin/news/index/' . $d['Direction']['id'] . '">' . (empty($d['Direction']['caption']) ? $d['Direction']['title'] : $d['Direction']['caption']) . '</a></li>';
		}
		echo '</ul></div><br />';
	}
?>

<p>
<?php
//echo $paginator->counter(array(
//'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
//));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php //echo $paginator->sort('id');?></th>
    <th><?php //echo $paginator->sort('title');?></th>
    <th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($lst as $l):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $l['News']['id']; ?>
        </td>
        <td>
            <?php echo $l['News']['created'] . ' - ' .  $l['News']['title']; ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $l['News']['id'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'delete', $l['News']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $l['News']['id'])); ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
    <?php //echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php //echo $paginator->numbers();?>
    <?php //echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Create New', true), array('action'=>'edit')); ?></li>
        <li><?php echo $html->link(__('List News', true), array('action'=>'index'));?></li>
    </ul>
</div>
