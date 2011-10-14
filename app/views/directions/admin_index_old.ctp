<?php
$html->css('adm_directions','',array(),false);
$javascript->link('adm_directions.js', false);
//$javascript->link('jstree/_lib/jquery.js', false);
//$javascript->link('jstree/jquery.jstree.js', false);

//pr($javascript);

?>



<script type="text/javascript">
jQuery(document).ready(function() {











/*
$("#tree1")
                // call `.jstree` with the options object
	        .jstree({
	            // the `plugins` array allows you to configure the active plugins on this instance
	            "plugins" : ["themes","html_data","ui","crrm"],
	            // each plugin you have included can have its own config object
	            "core" : { "initially_open" : [ "phtml_1" ] }
	            // it makes sense to configure a plugin only if overriding the defaults
	        })
*/




});
</script>

<?php
    echo '<table class="list_rows" cellpadding="0" cellspacing="0">';
foreach($tree_arr as $id => $title){

    echo '<tr>';
    echo '<td>'.$title.'</td>';
    echo '<td width="150">';
    echo '<div class="hidden_actions">'.
         '<a href="/admin/directions/up/'.$id.'" title="Вверх" ><img src="/img/copyrightholders/adm/Alarm-Arrow-Up-icon_32x32.png" class="icon" /></a>'.
         '<a href="/admin/directions/down/'.$id.'" title="Вниз" ><img src="/img/copyrightholders/adm/Alarm-Arrow-Down-icon_32x32.png" class="icon" /></a>'.
         '<a href="/admin/directions/add/'.$id.'" title="Добавить" ><img src="/img/copyrightholders/adm/Alarm-Plus-icon_32x32.png" class="icon" /></a>'.
         '<a href="/admin/directions/edit/'.$id.'" title="Редактировать" ><img src="/img/copyrightholders/adm/edit-icon2_32x32.png" class="icon" /></a>'.
         '<a href="/admin/directions/delete/'.$id.'" class="delete" title="Удалить?"><img src="/img/copyrightholders/adm/delete-icon_32x32.png" class="icon"/></a>'.
         '</div>';
    echo '</td>';
    echo '</tr>';
}
    echo '</table>';

?>





<div style="text-align: left" id="tree1">
<?php
//echo $tree2->generate($stuff, array('alias' => 'title'));
?>
</div>


<div class="pages index">
<h2><?php __('Directions');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0" class="list_rows">
<tr>
    <th><?php echo $paginator->sort('id');?></th>
    <th><?php echo $paginator->sort('title');?></th>
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
            <?php echo $l['Direction']['id']; ?>
        </td>
        <td>
            <?php echo $l['Direction']['title']; ?>
        </td>
        <td class="actions">
            <div class="hidden_actions">
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $l['Direction']['id'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'delete', $l['Direction']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $l['Direction']['id'])); ?>
            </div>
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
        <li><?php echo $html->link(__('Create New', true), array('action'=>'edit')); ?></li>
    </ul>
</div>
