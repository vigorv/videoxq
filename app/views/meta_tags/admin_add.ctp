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

	echo $form->create('MetaTag', array('action' => 'add', 'enctype' => 'multipart/form-data'));
?>
    <fieldset>
         <legend>Добавление записи о мета-тегах</legend>
<?php
        echo $form->input('url', array('label' => 'url', 'value' => (!empty($data['url']) ? $data['url'] : '')));
        echo $form->input('url_original', array('label' => 'url_original', 'value' => (!empty($data['url_original']) ? $data['url_original'] : '')));
        echo $form->input('title', array('label' => 'title', 'value' => (!empty($data['title']) ? $data['title'] : '')));
        echo $form->input('description', array('label' => 'description', 'value' => (!empty($data['description']) ? $data['description'] : '')));
        echo $form->input('keywords', array('label' => 'keywords', 'value' => (!empty($data['keywords']) ? $data['keywords'] : '')));
        echo $form->input('title_en', array('label' => 'title_en', 'value' => (!empty($data['title_en']) ? $data['title_en'] : '')));
        echo $form->input('description_en', array('label' => 'description_en', 'value' => (!empty($data['description_en']) ? $data['description_en'] : '')));
        echo $form->input('keywords_en', array('label' => 'keywords_en', 'value' => (!empty($data['keywords_en']) ? $data['keywords_en'] : '')));
        echo $form->input('order', array('label' => 'order', 'value' => (!empty($data['order']) ? $data['order'] : '')));
        echo $form->input('isbase', array('label' => 'isbase', 'value' => 1, 'checked' => (!empty($data['isbase']) ? 'checked' : '')));
	echo $form->submit('Добавить');
?>
    </fieldset>
    <?php echo $form->end();?>
</div>
<div style="margin: 5px; overflow: hidden">
<?php echo $html->link('Вернуться к списку мета-тегов', array('action'=>'index'),array('class'=>'a_btn','style'=>'display: block; clear: both'));?>
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
<script type="text/javascript">

      $(document).ready(function() {

      });

</script>
