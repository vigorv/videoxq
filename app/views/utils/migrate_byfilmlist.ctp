<?php
if (isset($allCnt))
{
	echo '<p>' . __('list_total_count', true) . ': ' . $allCnt . '</p>';
}
?>

<p>Укажите список адресов видеокаталога на фильмы для повторного мигрирования:</p>
<form action="/utils/migrate_byfilmlist" method="post">

<?php
	$data = null;
	$lst = null;
	echo $form->textarea('lst', array('rows' => 15));
?>
<input type="submit" />
</form>
<script type="text/javascript">
<!--
tinyMCE = null;
-->
</script>
