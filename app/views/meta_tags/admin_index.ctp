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
    echo '<th>URL Original</th>';
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
    echo '<td>'.((($paginator->current()-1)*$rows_per_page)+($n)).'</td>';
    echo '<td>'.$row['MetaTag']['url'].'</td>';
    echo '<td>'.$row['MetaTag']['url_original'].'</td>';
    echo '<td>'.$row['MetaTag']['title'].'</td>';
    echo '<td>'.$row['MetaTag']['description'].'</td>';
    echo '<td>'.$row['MetaTag']['keywords'].'</td>';
    echo '<td>'.$row['MetaTag']['title_en'].'</td>';
    echo '<td>'.$row['MetaTag']['description_en'].'</td>';
    echo '<td>'.$row['MetaTag']['keywords_en'].'</td>';
    echo '<td>'.$row['MetaTag']['order'].'</td>';
    echo '<td>'.($row['MetaTag']['isbase']? 'Основной' : 'Дополнительный').'</td>';
    echo '<td width="150" nowrap>';
    echo '<div class="hidden_actions">'.
//         '<a href="/admin/meta_tags/add/" title="Добавить" ><img src="/img/copyrightholders/adm/Alarm-Plus-icon_32x32.png" class="icon" /></a>'.
         '<a href="/admin/meta_tags/edit/'.$row['MetaTag']['id'].'" title="Редактировать" ><img src="/img/copyrightholders/adm/edit-icon2_32x32.png" class="icon" /></a>'.
         '<a href="/admin/meta_tags/delete/'.$row['MetaTag']['id'].'" class="delete" title="Удалить?"><img src="/img/copyrightholders/adm/delete-icon_32x32.png" class="icon"/></a>'.
         '</div>';
    echo '</td>';
    echo '</tr>';
    $n++;
}
echo '</table>';
?>

<div class="ch_btm_menu" style="position: relative; overflow: hidden">
<div style="width: 330px; float:right;">
<?php echo '<a href="/admin/meta_tags/add/" title="Добавить" >Добавить новую запись <img src="/img/copyrightholders/adm/Alarm-Plus-icon_32x32.png" class="icon" /></a>'; ?>
</div>
    <div style="width: 330px; float:left;">
<?php
    echo $form->create('MetaTag', array('id' => 'MetaTag_rows_per_page', 'enctype' => 'multipart/form-data', 'action' => '/', 'style'=>'padding:0; margin:0'));
    $numeration = array('10'=>'10','20'=>'20','30'=>'30','40'=>'40','50'=>'50','100'=>'100');
    echo $form->input('rows_per_page', array('label'=>'записей на страницу','type'=>'select' ,'empty'=>false, 'options'=>$numeration, 'selected'=> $rows_per_page, 'onchange'=>'this.form.submit();'));
    echo $form->end();
?>
   </div>

<div style="padding: 60px 0 0 5px;text-align: left;">Всего записей: <b style="font-size:18px"><?=$total_rows_count;?></b></div>


</div>
<div class="pages">
<?php echo $this->element('paging'); ?>
</div>
<div style="margin: 5px; overflow: hidden">
<?php 
    echo $html->link('Проверить результат выборки метатегов для URL', array('action'=>'check'),array('class'=>'a_btn','style'=>'display: block; clear: both'));
    echo '<br/>';
    echo $html->link('установка email-адреса для доставки оповещений', array('action'=>'report_set'),array('class'=>'a_btn','style'=>'display: block; clear: both'));    
?>
</div>
<pre>
Примечание: 
- метатэги можно назначать по точному совпадению адреса (поле url) или по маске адреса (группа адресов)
- чтобы задать маску адреса. нужно использовать в поле url символ "%" (обозначает любое кол-во символов)
- если поле url оставить пустым, то тэги будут присутствовать на всех страницах сайта
- признак "Основной" (isbase=1) означает, что тэги будут присутсвовать на всех страницах, соответсвующих данному url.
- признак "Дополнительный" (isbase=0) означает, что тэги будут добавляться к основным тэгам
- примечания и рекомендации
	для маски адреса (группы страниц) рекомендуется указывать признак isbase=0
	для точного адреса рекомендуется указывать признак isbase=1
	ключевые (keywords) слова разделяются символами запятой с пробелом ", "
	описание (description) заканчивается символом точки "."
</pre>