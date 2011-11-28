<?php
    $html->css('adm_copyrightholders','',array(),false);
    $javascript->link('jquery.livequery.js', false);
    $javascript->link('adm_copyrightholders.js', false);
?>
<script type="text/javascript">
jQuery(document).ready(function() {
    $('a[href*=/sort:],a[href*=/page:]').livequery('click', function(){
        $('#ch_content').load($(this).attr('href'));
        return false;
    });
});

</script>

<div class="ch_content">
<h2>"Правообладатели"</h2>
<div class="ch_top_menu">
<a href="/admin/copyrightholders">Список правообладателей</a>
<a href="/admin/copyrightholders/links" class="current">Связи "Фильмы" <-> "Правообладатели"</a>
<a href="/admin/copyrightholders/import">Импорт из файла Excel (*.xls)</a>
</div>

<table><tr><td width="50%"><?php //pr($film_list);?></td><td width="50%"><?php //pr($copyrightholders_list);?></td></tr></table>

<div style="padding-top: 35px;">&nbsp;</div>
    <div id="ch_content">
<!-- ----------------------------------------------------------------------- -->
<div style="text-align: left">
<?php

   foreach ($data as $row){
        echo $row['Copyrightholder']['name'].'<br>';
}
?>
</div>
        <div class="pages">
        <?php echo $this->element('paging'); ?>
        </div>
<!-- ----------------------------------------------------------------------- -->
    </div>

<div id="logo"></div>
</div>