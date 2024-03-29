<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Translit Results', true), array('action'=>'transtats')); ?></li>
        <li><?php echo $html->link(__('Film Clicks', true), array('action'=>'film_clicks')); ?></li>
        <li><?php echo $html->link(__('Lost Files', true), array('action'=>'lost_files')); ?></li>
        <li><?php echo $html->link(__('Search Logs', true), array('action'=>'search_logs')); ?></li>
    </ul>
</div>
<?php
$javascript->link('ui.core', false);
$javascript->link('ui.datepicker', false);
$html->css('ui.datepicker', null, array(), false);
?>
<div class="users index">
<h2><?php __('TV');?></h2>
<p>
<?php echo $form->create('Tvfilm', array('url' => '/admin/utils/tvs'));
echo $form->input('from', array('id' => 'from'));
echo $form->input('to', array('id' => 'to'));
echo $form->end('Search');
echo '<button onclick="location.href=\'/admin/utils/tvs/import\'">Import</button>';
?>
</p>

<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php echo $paginator->sort('name');?></th>
    <th><?php echo $paginator->sort('start');?></th>
    <th>Channel</th>
    <th>Category</th>
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
            <?php echo $film['Tvfilm']['name']; ?>
        </td>
        <td>
            <?php echo $film['Tvfilm']['start']; ?>
        </td>
        <td>
            <?php echo $film['Tvchannel']['name']; ?>
        </td>
        <td>
            <?php echo $film['Tvcategory']['name']; ?>
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
<?php

$script = '$("#from, #to").datepicker({
    dateFormat: $.datepicker.ATOM,
    firstDay: 1,
    changeFirstDay: false
});
';

echo $javascript->codeBlock($script, array('inline' => true))

?>