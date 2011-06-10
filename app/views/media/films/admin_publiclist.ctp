<?php
if (isset($allCnt))
{
	echo '<p>' . __('list_total_count', true) . ': ' . $allCnt . '</p>';
	echo '<p>' . __('list_processed_count', true) . ': ' . $addCnt . '</p>';
}
?>

<p>Укажите список адресов видеокаталога на фильмы общественного достояния:</p>
<form action="/admin/media/publiclist" method="post">

<?php
	$data = null;
	$lst = null;
	echo '<br />установить/сбросить признак "общественное достояние"' . $form->checkbox('is_public', array('width' => '20px'));
	echo $form->textarea('lst', array('rows' => 15));
?>
<input type="submit" />
</form>
<script type="text/javascript">
<!--
tinyMCE = null;
-->
</script>
