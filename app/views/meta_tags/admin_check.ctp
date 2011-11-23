<?php
$html->css('adm_meta_tags','',array(),false);
$javascript->link('adm_meta_tags.js', false);
?>
<script type="text/javascript">
jQuery(document).ready(function() {
});
</script>
<div class="form">
<?php
	echo $form->create('MetaTag', array('action' => 'check', 'enctype' => 'multipart/form-data'));
?>
    <fieldset>
         <legend>Проверка результата выборки метатегов для URL</legend>
<?php
        echo $form->input('url', array('label' => 'URL', 'value' => (!empty($data['url']) ? $data['url'] : '')));
	echo $form->submit('Проверить');
?>
    </fieldset>
    <?php echo $form->end();?>
</div>
<div style="text-align: left">
<?php
if (!empty($data)){
?>
    <h3><span style="font-size: 15px">Сгенерированные мета-теги для url:</span><?=$data['url']?></h3>
    <h3>Rus</h3>
    <div><b>title</b>: <?=$data['metatags_ru']['title']?></div>
    <div><b>description</b>: <?=$data['metatags_ru']['description']?></div>
    <div><b>keywords</b>: <?=$data['metatags_ru']['keywords']?></div>
    <h3>Eng</h3>
    <div><b>title</b>: <?=$data['metatags_en']['title']?></div>
    <div><b>description</b>: <?=$data['metatags_en']['description']?></div>
    <div><b>keywords</b>: <?=$data['metatags_en']['keywords']?></div>                
    <h3></h3>
<?php    
}
?>
</div>



<?php echo $html->link('Вернуться к списку мета-тегов', array('action'=>'index'),array('class'=>'a_btn','style'=>'display: block; clear: both'));?>
<script type="text/javascript">

      $(document).ready(function() {

      });

</script>