<?php
    $html->css('adm_copyrightholders','',array(),false);
//    $javascript->link('jquery.livequery.js', false);
    $javascript->link('adm_copyrightholders.js', false);
?>
<script type="text/javascript">
function form_submit(){
    var cid = $('select#CopyrightholderId').val();
    if (cid != ''){
        var link = $('#Copyrightholder_add_link').attr("href");
        $('#Copyrightholder_add_link').attr("href",link+cid);
        return true;
    }
    else{
        alert ('Выберите сначала правообладателя');
    }
    return false;
}

jQuery(document).ready(function() {

});

</script>

<div class="ch_content">
<h2>"Правообладатели"</h2>
<div class="ch_top_menu">
<a href="/admin/copyrightholders">Список правообладателей</a>
<a href="/admin/copyrightholders/links" class="current">Связи "Фильмы" <-> "Правообладатели"</a>
<a href="/admin/copyrightholders/import">Импорт из файла Excel (*.xls)</a>
</div>
<div style="text-align: left; padding-top: 10px;">
<table class="list_rows" cellspacing="1px">
<?php
if (!empty($film_data)){
    echo '<tr>';
    echo '<td  style="vertical-align: middle" width="200px"><b>Фильм </b>'.
         '<a class="a_btn" href="/admin/copyrightholders/films">Выбрать другой фильм</a>'.
         '</td>'.
         '<td style="vertical-align: middle"><h2 style="padding-top:0"> '.$film_data['Film']['title'].'</h2>'.
         '</td>';
    echo '</tr>';
    $n=1;
    if (!empty($film_data['Copyrightholder'])){
         $n = count ($film_data['Copyrightholder']);
    }
    echo '<tr><td rowspan="'.($n+1).'"><b>Правообладатели </b> </td><td>';
    echo '<ul>';
    if (!empty($film_data['Copyrightholder'])){
        foreach($film_data['Copyrightholder'] as $copyrightholder){
            echo '<li>'.$copyrightholder['name'].
                 '<a href="/admin/copyrightholders/filmedit/'.$film_data['Film']['id'].'/del/'.$copyrightholder['id'].'" class="delete" title="Удалить `'.$copyrightholder['name'].'`?"><img src="/img/copyrightholders/adm/delete-icon_32x32.png" class="icon"/></a>'.
                 '</li>';
        }
    }
    else{
        echo '<li>Нет</li>';
    }
    echo '</ul>';
    echo '</td></tr>';
    echo '<tr><td style="vertical-align:middle">';
    echo '<div style="float:left">';
    echo $form->create('Copyrightholder', array('id' => 'Copyrightholder_add_form', 'enctype' => 'multipart/form-data', 'action' => '/filmedit/'.$film_data['Film']['id'].'/add'));
    echo $form->select('Copyrightholder.id', $copyrightholders_list);
    echo $form->end();
    echo '</div>';
    echo '<a id="Copyrightholder_add_link" href="/admin/copyrightholders/filmedit/'.$film_data['Film']['id'].'/add/" title="Добавить правообладателя" ><img src="/img/copyrightholders/adm/add-icon_32x32.png" class="icon" onclick="return form_submit();" /></a>';


    echo '</td></tr>';
}
?>
</table>
</div>

<div id="logo"></div>
</div>