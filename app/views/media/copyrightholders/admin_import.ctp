<script type="text/javascript">
jQuery(document).ready(function() {
    $('#more_params').click(function(){
        $(this).next('div.more').slideToggle();
    });

    if($('input#CopyrightholderAll').is(':checked')){
            $('input#CopyrightholderFrom').attr('disabled','true')
                                          .parent('td')
                                          .animate({ opacity:"0"}, 300)
                                          .prev()
                                          .animate({ opacity:"0"}, 300);
            $('input#CopyrightholderTo').attr('disabled','true').parent('td').animate({ opacity:"0"}, 300);
    }

    $('input#CopyrightholderAll').click(function(){
        if($('input#CopyrightholderAll').is(':checked')){
            $('input#CopyrightholderFrom').attr('disabled','true')
                                          .parent('td')
                                          .animate({ opacity:"0"}, 300)
                                          .prev()
                                          .animate({ opacity:"0"}, 300);
            $('input#CopyrightholderTo').attr('disabled','true').parent('td').animate({ opacity:"0"}, 300);
        }
        else{
            $('input#CopyrightholderFrom').attr('disabled','')
                                          .parent('td')
                                          .animate({ opacity:"1"}, 300)
                                          .prev()
                                          .animate({ opacity:"1"}, 300);
            $('input#CopyrightholderTo').attr('disabled','').parent('td').animate({ opacity:"1"}, 300);
        }
    });
});

</script>
<style>
.form labels {
    float: left;
}

</style>
<div class="ch_content">
<h2>"Правообладатели"</h2>
<div class="ch_top_menu">
<a href="/admin/copyrightholders">Список правообладателей</a>
<a href="/admin/copyrightholders/films">Связи "Фильмы" <-> "Правообладатели"</a>
<a href="/admin/copyrightholders/import" class="current">Импорт из файла Excel (*.xls)</a>
</div>

<br/>


<?php
$html->css('adm_copyrightholders','',array(),false);
$javascript->link('adm_copyrightholders.js', false);


?>
<div class="doCategories form">
<?php echo $form->create('Copyrightholder', array('enctype' => 'multipart/form-data', 'action' => '/import'));?>
    <fieldset>
         <legend>Импорт из Excel</legend>
         <span class="a_btn" id="more_params">Дополнительные параметры</span>
         <div  style="display:none" class="more">
    <?php
    //пришлось выводить вручную формы, кэйковскими через..опными методами не получилось кое что сделать :(((
    //+ стили фиг поправишь
              echo '<div>
              <table style="border:0"><tr><td width="40px"  style="border:0">
                <label for="CopyrightholderAll">Все строки</label>
                <input type="checkbox" id="CopyrightholderAll" name="data[Copyrightholder][all]" checked="1" >
              </td><td width="40px"  style="border:0"><h2 style="padding-top:0">или</h2>
              </td><td width="110px"  style="border:0">
                <label for="CopyrightholderFrom">начиная со строки</label>
                <input type="text" id="CopyrightholderFrom" value="1" maxlength="7" name="data[Copyrightholder][from]" style="width:100px"/>
              </td><td width="110px"  style="border:0">
                <label for="CopyrightholderTo">по строку</label>
                <input type="text" id="CopyrightholderTo" value="1" maxlength="7" name="data[Copyrightholder][to]" style="width:100px"/>
              </td></tr></table>
                </div>';
    ?>
        </div>
    <?php
        echo $form->input('Copyrightholder.file_name', array('label' => 'Файл Excel(*.xls)', 'type'=>'file'));

    ?>
    </fieldset>
<?php echo $form->end('Импорт');?>
</div>
<?php
//если импорт был
if ($data['import_event']){
    echo '<div align=left>';
    echo 'Проанализировано строк: <b>'.$data['count_analysed_rows'].'</b><br/>';
    echo 'Количество импортированых связей "Фильм"<->"Правообладатель": <b>'.$data['count_imported_links'].'</b><br/>';
    // если импортированы новые правообладатели, то выведем их список
    if (!empty($data['imported_list']) && $data['imported_list']){
        echo 'Импортировано новых правообладателей : <b>'.count($data['imported_list']).'</b><br><br>';
        foreach($data['imported_list'] as $key=>$row){
            echo ($key+1).'. '.$row['cname'].'<br>';
        }
    }
    else{
            echo '<b>Новых правообладателей не импортировано.</b><br>';
    }
    echo '</div>';
}
?>

<div id="logo"></div>
</div>
