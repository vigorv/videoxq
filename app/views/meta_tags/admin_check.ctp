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
<?php echo $html->link('Вернуться к списку мета-тегов', array('action'=>'index'),array('class'=>'a_btn','style'=>'display: block; clear: both'));?>
<script type="text/javascript">

      $(document).ready(function() {

      });

</script>