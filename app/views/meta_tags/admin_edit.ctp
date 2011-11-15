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
	echo $form->create('MetaTag', array('action' => 'edit', 'enctype' => 'multipart/form-data'));
?>
    <fieldset>
         <legend>Редактирование записи о мета-тегах</legend>
<?php
        echo $form->input('id', array('type' => 'hidden', 'value' => (!empty($data['id']) ? $data['id'] : 0)));
        echo $form->input('url', array('label' => 'url', 'value' => (!empty($data['url']) ? $data['url'] : '')));
        echo $form->input('title', array('label' => 'title', 'value' => (!empty($data['title']) ? $data['title'] : '')));
        echo $form->input('description', array('label' => 'description', 'value' => (!empty($data['description']) ? $data['description'] : '')));
        echo $form->input('keywords', array('label' => 'keywords', 'value' => (!empty($data['keywords']) ? $data['keywords'] : '')));
        echo $form->input('title_en', array('label' => 'title_en', 'value' => (!empty($data['title_en']) ? $data['title_en'] : '')));
        echo $form->input('description_en', array('label' => 'description_en', 'value' => (!empty($data['description_en']) ? $data['description_en'] : '')));
        echo $form->input('keywords_en', array('label' => 'keywords_en', 'value' => (!empty($data['keywords_en']) ? $data['keywords_en'] : '')));
        echo $form->input('order', array('label' => 'order', 'value' => (!empty($data['order']) ? $data['order'] : '')));
        echo $form->input('isbase', array('label' => 'isbase', 'value' => 1, 'checked' => (!empty($data['isbase']) ? 'checked' : '')));
	echo $form->submit('Применить');
?>
    </fieldset>
    <?php echo $form->end();?>
</div>
<?php echo $html->link('Вернуться к списку мета-тегов', array('action'=>'index'),array('class'=>'a_btn','style'=>'display: block; clear: both'));?>
<script type="text/javascript">

      $(document).ready(function() {

      });

</script>
