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
	echo $form->create('MetaTag', array('action' => 'report_set', 'enctype' => 'multipart/form-data'));
?>
    <fieldset>
         <legend>Установка email-адреса для доставки оповещений</legend>
<?php
        echo $form->input('email', array('label' => 'Email', 'value' => (!empty($data['email']) ? $data['email'] : '')));
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