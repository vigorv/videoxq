<?php
    $html->css('adm_copyrightholders','',array(),false);
    $javascript->link('jquery.livequery.js', false);
    $javascript->link('adm_copyrightholders.js', false);
?>
<script type="text/javascript">
jQuery(document).ready(function() {

});

</script>

<div class="ch_content">
<h2>"Правообладатели"</h2>
<div class="ch_top_menu">
<a href="/admin/copyrightholders">Список правообладателей</a>
<a href="/admin/copyrightholders/films" class="current">Связи "Фильмы" <-> "Правообладатели"</a>
<a href="/admin/copyrightholders/import">Импорт из файла Excel (*.xls)</a>
</div>
<div class="doCategories form">
<?php echo $form->create('Copyrightholder', array('enctype' => 'multipart/form-data', 'action' => 'films'));?>
    <fieldset>
         <legend>Найти фильм</legend>
    <?php
        echo $form->input('Film.title', array('label' => 'Поиск по названию фильма (не менее 3х букв)'));
    ?>
    </fieldset>
<?php echo $form->end('Найти');?>
</div>
<div style="text-align: left">


<?php
if (!empty($films_list)){
?>
<table class="list_rows" cellspacing="1px">
<tr><th>Id</th><th>Название</th><th>Год</th><th>Правообладатель</th><th></th></tr>
<?
foreach ($films_list as $film){
    $copyrightholders_names = '';
    foreach ($film['Copyrightholder'] as $year => $copyrightholder){
        if ($copyrightholders_names){
            $copyrightholders_names .= ', ';
        }
        $copyrightholders_names.= $copyrightholder['name'];

    }
    if (!$copyrightholders_names) {
        $copyrightholders_names = '-';
    }
    echo '<tr><td>' . $film['Film']['id'] . '</td><td>' . $film['Film']['title'] . '</td><td>' . $film['Film']['year'] . '</td><td>' . $copyrightholders_names . '</td><td>';
?>
            <div class="hidden_actions">
            <a href="/admin/copyrightholders/filmedit/<?=$film['Film']['id']?>" title="Редактировать" ><img src="/img/copyrightholders/adm/edit-icon2_32x32.png" class="icon" /></a>
            </div>
<?php
     echo '</td></tr>';
}
?>
</table>
<?php
}
?>


</div>
<div><a href="/admin/copyrightholders/phoneticsearch" class="a_btn">Фонетический поиск (тестовый скрипт)</a></div>
<div id="logo"></div>
</div>
<?php echo $autocomplete->autocomplete('FilmTitle','Film/title',null,30,3); ?>