<h3>Группы похожих фильмов</h3>
<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php echo $paginator->sort('id');?></th>
    <th><?php echo $paginator->sort('title');?></th>
    <th>Films</th>
    <th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($groups as $group):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr <?php echo $class;?>>
        <td>
            <?php echo $group['SimilarFilm']['id']; ?>
        </td>
        <td>
            <?php echo $group['SimilarFilm']['title']; ?>
        </td>
        <td>
            <?php echo $group['SimilarFilm']['films']; ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('Edit', true), array('action'=>'form', $group['SimilarFilm']['id'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'delete', $group['SimilarFilm']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $group['SimilarFilm']['id'])); ?>
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
	<li><?php echo $html->link(__('New Group', true), array('action'=>'form')); ?></li>
</ul>