<div class="bookmarks index">
<h2><?php __('Bookmarks');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php echo $paginator->sort('id');?></th>
    <th><?php echo $paginator->sort('user_id');?></th>
    <th><?php echo $paginator->sort('url');?></th>
    <th><?php echo $paginator->sort('title');?></th>
    <th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($bookmarks as $bookmark):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $bookmark['Bookmark']['id']; ?>
        </td>
        <td>
            <?php echo $html->link($bookmark['User']['userid'], array('controller'=> 'users', 'action'=>'view', $bookmark['User']['userid'])); ?>
        </td>
        <td>
            <?php echo Sanitize::html($bookmark['Bookmark']['url']); ?>
        </td>
        <td>
            <?php echo Sanitize::html($bookmark['Bookmark']['title']); ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('View', true), array('action'=>'view', $bookmark['Bookmark']['id'])); ?>
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $bookmark['Bookmark']['id'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'delete', $bookmark['Bookmark']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $bookmark['Bookmark']['id'])); ?>
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
        <li><?php echo $html->link(__('New Bookmark', true), array('action'=>'add')); ?></li>
        <li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
    </ul>
</div>
