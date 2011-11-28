<div class="ch_content">
<h2>"Правообладатели"</h2>
<div class="ch_top_menu">
<a href="/admin/copyrightholders" class="current">Список правообладателей</a>
<a href="/admin/copyrightholders/films">Связи "Фильмы" <-> "Правообладатели"</a>
<a href="/admin/copyrightholders/import">Импорт из файла Excel (*.xls)</a>
</div>

<br/>


<?php
$html->css('adm_copyrightholders','',array(),false);
$javascript->link('adm_copyrightholders.js', false);

//pr("<div align=left>".$cholders_list."</div>");

?>
<table class="list_rows" cellspacing="1px">
    <tr><th>№</th><th>Фото</th><th>Название</th><th>Название(англ.)</th><th>Описание</th><th>Скрыт</th><th>&nbsp;</th></tr>
<?php
foreach ($cholders_list as $k => $row){
?>
    <tr<?=$row['Copyrightholder']['hidden']? ' style="color:#aaa;"' : '';?>>
        <td><?=(($paginator->current()-1)*$rows_per_page)+($k+1);?></td>
        <td>
<?php
        foreach($row['CopyrightholdersPicture'] as $pic){
            $file_name = $pic['file_name'];
            echo '<img height="30px" src="/img/'. $file_name .'" style="margin: 5px; float: left'.($row['Copyrightholder']['hidden']? '; opacity: 0.3"' : '').'"/>';
        }
?>
        </td>
        <td><?=$row['Copyrightholder']['name']? $row['Copyrightholder']['name'] : '&nbsp;';?></td>
        <td><?=$row['Copyrightholder']['name_en']? $row['Copyrightholder']['name_en']: '&nbsp;';?></td>
        <td><?=$row['Copyrightholder']['description']? $row['Copyrightholder']['description']: '&nbsp;';?></td>
        <td><?=$row['Copyrightholder']['hidden']? 'Скрыт' : 'Нет';?></td>
        <td nowrap >
            <div class="hidden_actions">
            <a href="/admin/copyrightholders/edit/<?=$row['Copyrightholder']['id']?>" title="Редактировать" ><img src="/img/copyrightholders/adm/edit-icon2_32x32.png" class="icon" /></a>
            <a href="/admin/copyrightholders/delete/<?=$row['Copyrightholder']['id']?>" class="delete" title="Удалить `<?=htmlspecialchars($row['Copyrightholder']['name']);?>`?"><img src="/img/copyrightholders/adm/delete-icon_32x32.png" class="icon"/></a>
            </div>
        </td>
    </tr>
<?php
}
?>

</table>
<div class="ch_btm_menu" style="position: relative; overflow: hidden">
<div style="width: 330px; float:right;">
Добавить правообладателя <a href="/admin/copyrightholders/add" title="Добавить правообладателя" ><img src="/img/copyrightholders/adm/add-icon_32x32.png" class="icon" /></a>
</div>
    <div style="width: 330px; float:left;">
<?php
    echo $form->create('Copyrightholder', array('id' => 'Copyrightholder_rows_per_page', 'enctype' => 'multipart/form-data', 'action' => '/', 'style'=>'padding:0; margin:0'));
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

<div id="logo"></div>
</div>