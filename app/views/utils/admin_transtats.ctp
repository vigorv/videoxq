<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Search Logs', true), array('action'=>'search_logs')); ?></li>
		<li><?php echo $html->link(__('Film Clicks', true), array('action'=>'film_clicks')); ?></li>
        <li><?php echo $html->link(__('Lost Files', true), array('action'=>'lost_files')); ?></li>
        <li><?php echo $html->link(__('TV', true), array('action'=>'tvs')); ?></li>
    </ul>
</div>
<div class="users index">
<h2><?php __('Translit statistics');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php echo $paginator->sort('search');?></th>
    <th><?php echo 'created'; ?></th>
</tr>
<?php
$i = 0;
foreach ($search as $word):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr <?php echo $class;?>>
        <td>
            <?php echo $word['Transtat']['search']; ?>
        </td>
        <td>
            <?php
            echo $word['Transtat']['created'];
            ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
    <?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 |  <?php echo $paginator->numbers();?>
    <?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
