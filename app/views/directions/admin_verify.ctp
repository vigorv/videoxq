<?php
$html->css('adm_directions','',array(),false);
$javascript->link('adm_directions.js', false);
?>
<script type="text/javascript">
jQuery(document).ready(function() {

});
</script>
<h2><?=(!empty($data['message']))? $data['message'] : '';?></h2>
<?php
if (!empty($data['result'])){
    pr($data['result']);
}
?>
<?php echo $html->link('Вернуться к списку категорий', array('action'=>'index'),array('class'=>'a_btn','style'=>'display: block; clear: both'));?>