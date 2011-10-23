<?php
$html->css('adm_directions','',array(),false);
$javascript->link('adm_directions.js', false);
//$javascript->link('jstree/_lib/jquery.js', false);
$javascript->link('jstree/jquery.jstree.js', false);

//pr($javascript);

?>
<style>


     .curent {
//        color: #d00;

</style>
<script type="text/javascript">
jQuery(document).ready(function() {
$('.current').children('a').css('color','#f00');



$("#tree1")
                // call `.jstree` with the options object
	        .jstree({
	            // the `plugins` array allows you to configure the active plugins on this instance
	            "plugins" : ["themes","html_data","ui"],
	            // each plugin you have included can have its own config object
	            "core" : { "initially_open" : [ "current_item" ]}
	            // it makes sense to configure a plugin only if overriding the defaults
	        })





});
</script>





<div style="text-align: left" >
<?php
//echo $tree2->generate($stuff, array('alias' => 'title'));
?>
</div>

<h2><?php __('Directions');?></h2>

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
<?php echo $html->link('Восстановление структуры дерева категорий', array('action'=>'recover'),array('class'=>'a_btn','style'=>'display: block; clear: both'));?>
<?php echo $html->link('Переиндексация структуры дерева категорий', array('action'=>'reorder'),array('class'=>'a_btn','style'=>'display: block; clear: both'));?>
<?php echo $html->link('Проверка структуры дерева категорий', array('action'=>'verify'),array('class'=>'a_btn','style'=>'display: block; clear: both'));?>


<div style="text-align: left; clear: both" id="tree1">
<?php
if (!empty($directions_data) && $directions_data){
    echo $directions->showTree($directions_data['list'],$directions_data['current_id']);
}





?>
</div>


