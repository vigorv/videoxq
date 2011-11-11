<?php
$html->css('adm_meta_tags','',array(),false);
$javascript->link('adm_meta_tags.js', false);

?>
<style>
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
});
</script>

<h2>Мета-теги страниц сайта</h2>
<?php
echo '<table class="list_rows" cellpadding="0" cellspacing="0">';
    echo '<tr>';
    echo '<th>№</td>';
    echo '<th>URL</th>';
    echo '<th>Title</th>';
    echo '<th>Description</th>';
    echo '<th>Keywords</th>';
    echo '<th>Title Eng</th>';
    echo '<th>Description Eng</th>';
    echo '<th>Keywords Eng</th>';
    echo '<th>Order</th>';
    echo '<th>Is base</th>';
    echo '<th width="150"></th>';
    echo '</tr>';
$n=1;
foreach($metatags['data'] as $row){
    echo '<tr>';
    echo '<td>'.$n.'</td>';
    echo '<td>'.$row['MetaTag']['urlmask'].'</td>';
    echo '<td>'.$row['MetaTag']['title'].'</td>';
    echo '<td>'.$row['MetaTag']['description'].'</td>';
    echo '<td>'.$row['MetaTag']['keywords'].'</td>';
    echo '<td>'.$row['MetaTag']['title_en'].'</td>';
    echo '<td>'.$row['MetaTag']['description_en'].'</td>';
    echo '<td>'.$row['MetaTag']['keywords_en'].'</td>';
    echo '<td>'.$row['MetaTag']['order'].'</td>';
    echo '<td>'.($row['MetaTag']['isbase']? 'Основной' : 'Дополнительный').'</td>';
    echo '<td width="150">';
    echo '<div class="hidden_actions">'.
         '<a href="/admin/meta_tags/add/'.$row['MetaTag']['id'].'" title="Добавить" ><img src="/img/copyrightholders/adm/Alarm-Plus-icon_32x32.png" class="icon" /></a>'.
         '<a href="/admin/meta_tags/edit/'.$row['MetaTag']['id'].'" title="Редактировать" ><img src="/img/copyrightholders/adm/edit-icon2_32x32.png" class="icon" /></a>'.
         '<a href="/admin/meta_tags/delete/'.$row['MetaTag']['id'].'" class="delete" title="Удалить?"><img src="/img/copyrightholders/adm/delete-icon_32x32.png" class="icon"/></a>'.
         '</div>';
    echo '</td>';
    echo '</tr>';
    $n++;
}
echo '</table>';
?>

<div>Всего записей: <?=$metatags['count']?></div>