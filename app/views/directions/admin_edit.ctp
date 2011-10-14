<?php
$html->css('adm_directions','',array(),false);
$javascript->link('adm_directions.js', false);
?>
<script type="text/javascript">
jQuery(document).ready(function() {

});
</script>
<div class="form">
<?php
	$javascript->link('ui.core', false);
	$javascript->link('ui.datepicker', false);
	$html->css('ui.datepicker', null, array(), false);

	echo $form->create('Direction', array('action' => 'edit', 'enctype' => 'multipart/form-data'));
?>
    <fieldset>
         <legend>Редактирование категории</legend>
    <?php

        $options = (!empty($data['directions_list']) && $data['directions_list'])? $data['directions_list'] : array();
        echo $form->input('parent_id', array('label'=>'Родительская категория','type'=>'select' ,'empty'=>true, 'options'=>$options, 'selected'=> ((!empty($data['parent_id']) && $data['parent_id'])? $data['parent_id'] : '')));
        echo $form->input('title', array('label' => 'Название полное', 'value' => (!empty($data['title']) ? $data['title'] : '')));
        echo $form->input('caption', array('label' => 'Название краткое', 'value' => (!empty($data['caption']) ? $data['caption'] : '')));
        echo $form->input('txt', array('rows' => 10, 'label' => 'Описание', 'value' => (!empty($data['txt']) ? $data['txt'] : '')));
        echo $form->input('hidden', array('label' => 'Скрыть категорию', 'value' => 1, 'checked' => (!empty($data['hidden']) ? 'checked' : '')));
        echo $form->input('id', array('type'=>'hidden' ,'value' => (!empty($data['id']) ? $data['id'] : '')));
        echo $form->input('old_parent_id', array('type'=>'hidden' ,'value' => (!empty($data['old_parent_id']) ? $data['old_parent_id'] : '')));
        //echo $form->input('srt', array('type' => 'text', 'label' => 'Сортировка', 'value' => (!empty($data['srt']) ? $data['srt'] : 0)));
	echo $form->submit('Применить');
?>
    </fieldset>
    <?php echo $form->end();?>
</div>
<?php echo $html->link('Вернуться к списку категорий', array('action'=>'index'),array('class'=>'a_btn','style'=>'display: block; clear: both'));?>


<script type="text/javascript">

      $(document).ready(function() {

 		$("#createdid").datepicker({
	    dateFormat: $.datepicker.ATOM,
	    firstDay: 1,
	    changeFirstDay: false
		});
      });

</script>
