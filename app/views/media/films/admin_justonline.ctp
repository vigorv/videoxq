<?php
if (isset($allCnt))
{
	echo '<p>' . __('list_total_count', true) . ': ' . $allCnt . '</p>';
	echo '<p>' . __('list_processed_count', true) . ': ' . $addCnt . '</p>';
}
?>

<p>Укажите список адресов видеокаталога на фильмы, разрешенные только для online-просмотра:</p>
<form action="/admin/media/justonline" method="post">

<?php
	$data = null;
	$lst = null;
	echo '<br />установить/сбросить признак "разрешен только online-просмотр"' . $form->checkbox('just_online', array('width' => '20px'));
	echo $form->textarea('lst', array('rows' => 15));
?>
<input type="submit" />
</form>
<script type="text/javascript">
<!--
tinyMCE = null;
-->
</script>
