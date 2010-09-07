<div class="polls index">
<h2><?php __('Polls');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php echo $paginator->sort('id');?></th>
    <th><?php echo $paginator->sort('title');?></th>
    <th><?php echo $paginator->sort('answers');?></th>
    <th><?php echo $paginator->sort('votes');?></th>
    <th><?php echo $paginator->sort('total_votes');?></th>
    <th><?php echo $paginator->sort('active');?></th>
    <th><?php echo $paginator->sort('created');?></th>
    <th><?php echo $paginator->sort('modified');?></th>
    <th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($polls as $poll):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $poll['Poll']['id']; ?>
        </td>
        <td>
            <?php echo $poll['Poll']['title']; ?>
        </td>
        <td>
            <?php
            $answers = unserialize($poll['Poll']['answers']);
            foreach ($answers as $answer)
            {
                echo $answer . '<br>';
            }

             ?>
        </td>
        <td>
            <?php echo $poll['Poll']['votes']; ?>
        </td>
        <td>
            <?php echo $poll['Poll']['total_votes']; ?>
        </td>
        <td>
            <?php echo $poll['Poll']['active']; ?>
        </td>
        <td>
            <?php echo $poll['Poll']['created']; ?>
        </td>
        <td>
            <?php echo $poll['Poll']['modified']; ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('View', true), array('action'=>'view', $poll['Poll']['id'])); ?>
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $poll['Poll']['id'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'delete', $poll['Poll']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $poll['Poll']['id'])); ?>
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
        <li><?php echo $html->link(__('New Poll', true), array('action'=>'add')); ?></li>
    </ul>
</div>
