<div class="filmComments index">
<h2><?php __('FilmComments');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php echo $paginator->sort('id');?></th>
    <th><?php echo $paginator->sort('film_id');?></th>
    <th><?php echo $paginator->sort('user_id');?></th>
    <th><?php echo $paginator->sort('text');?></th>
    <th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($filmComments as $filmComment):
    extract($filmComment);
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $FilmComment['id']; ?>
        </td>
        <td>
            <?php echo $html->link($Film['title'], array('controller'=> 'media', 'action'=>'view', $Film['id'])); ?>
        </td>
        <td>
            <?php echo $html->link($FilmComment['username'], array('controller'=> 'users', 'action'=>'view', $FilmComment['user_id'])); ?>
        </td>
        <td>
            <?php echo h($FilmComment['text']); ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $FilmComment['id'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'delete', $FilmComment['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $FilmComment['id'])); ?>
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
