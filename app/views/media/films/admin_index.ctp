<div class="films index">
<h2><?php __('Films');?></h2>
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
    <th><?php echo $paginator->sort('title_en');?></th>
    <th><?php echo $paginator->sort('year');?></th>
    <th><?php echo $paginator->sort('imdb_rating');?></th>
    <th><?php echo $paginator->sort('imdb_id');?></th>
    <th><?php echo $paginator->sort('imdb_votes');?></th>
    <th><?php echo $paginator->sort('imdb_date');?></th>
    <th><?php echo $paginator->sort('oscar');?></th>
    <th><?php echo $paginator->sort('modified');?></th>
    <th><?php echo $paginator->sort('hits');?></th>
    <th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($films as $film):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $film['Film']['id']; ?>
        </td>
        <td>
            <?php echo $film['Film']['title']; ?>
        </td>
        <td>
            <?php echo $film['Film']['title_en']; ?>
        </td>
        <td>
            <?php echo $film['Film']['year']; ?>
        </td>
        <td>
            <?php echo $film['Film']['imdb_rating']; ?>
        </td>
        <td>
            <?php echo $film['Film']['imdb_id']; ?>
        </td>
        <td>
            <?php echo $film['Film']['imdb_votes']; ?>
        </td>
        <td>
            <?php echo $film['Film']['imdb_date']; ?>
        </td>
        <td>
            <?php echo $film['Film']['oscar']; ?>
        </td>
        <td>
            <?php echo $film['Film']['modified']; ?>
        </td>
        <td>
            <?php echo $film['Film']['hits']; ?>
        </td>
        <td class="actions">
            <?php echo $html->link('Подбросить', array('action'=>'up', $film['Film']['id'])); ?>
            <?php echo $html->link(__('View', true), array('action'=>'view', $film['Film']['id'])); ?>
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $film['Film']['id'])); ?>
            <?php echo $html->link(__('Del', true), array('action'=>'delete', $film['Film']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $film['Film']['id'])); ?>
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
        <li><?php echo $html->link(__('License List', true), array('action'=>'licenselist')); ?></li>
        <li><?php echo $html->link(__('Import List', true), array('action'=>'importlist')); ?></li>
        <li><?php echo $html->link(__('Similar Films', true), array('controller'=>'similar_films')); ?></li>
        <li><?php echo $html->link(__('New Film', true), array('action'=>'add')); ?></li>
        <li><?php echo $html->link(__('List Film Types', true), array('controller'=> 'film_types', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Film Type', true), array('controller'=> 'film_types', 'action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Film Comments', true), array('controller'=> 'film_comments', 'action'=>'index')); ?> </li>
    </ul>
</div>
