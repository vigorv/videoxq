<script type="text/javascript">
<!--
tinyMCE = null;
-->
</script>
<?php
if (isset($allCnt))
{
	echo '<p>' . __('list_total_count', true) . ': ' . $allCnt . '</p>';
	echo '<p>' . __('list_processed_count', true) . ': ' . $addCnt . '</p>';
	if (!empty($picsFileName))
		echo '<p>' . __('pics_cmd_file', true) . ': ' . $picsFileName . '</p>';
	if (!empty($filmsFileName))
		echo '<p>' . __('films_cmd_file', true) . ': ' . $filmsFileName . '</p>';
}
?>

<p style="text-align:left">Укажите список адресов видеокаталога:</p>
<script type="text/javascript">
<!--
	function makedump()
	{
		return confirm("сделан ли бэкап базы данных?");
	}
-->
</script>

<form action="/admin/media/importlist" method="post" onsubmit="return makedump();">
<?php
	$data = null;
	$lst = null;
	echo '<p style="text-align:left">это фильмы с лицензией' . $form->checkbox('is_license', array('width' => '20px')) . '</p>';
	echo $form->textarea('lst', array('rows' => 15));
?>
<input type="submit">
</form>
<p style="text-align:left">Для миграции всех новых/измененных после последней миграции фильмов и персон нажмите ссылку
"<a onclick="return makedump();" href="/admin/media/importlist/all">миграция</a>"</p>
