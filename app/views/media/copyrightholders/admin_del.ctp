<div class="ch_content">
<h2>"Правообладатели"</h2>
<div class="ch_top_menu">
<a href="/admin/copyrightholders">Список правообладателей</a>
<a href="/admin/copyrightholders/films">Связи "Фильмы" <-> "Правообладатели"</a>
<a href="/admin/copyrightholders/import">Импорт из файла Excel (*.xls)</a>
</div>

<br>


<?php
$html->css('adm_copyrightholders','',array(),false);
$javascript->link('adm_copyrightholders.js', false);

//pr("<div align=left>".$cholders_list."</div>");

?>

<div class="ch_btm_menu">
    <a href="/admin/copyrightholders/add" title="Добавить правообладателя" ><img src="/img/copyrightholders/add-icon_32x32.png" class="icon" /></a>
</div>
<div id="logo"></div>
</div>