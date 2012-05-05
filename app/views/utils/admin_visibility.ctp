<h3>Управление видимостью нелицензии</h3>
<?php
    if (!empty($masks))
    {
?>
<form method="post" action="/admin/utils/visibility">
<table>
<tr><td>показ описания</td><td><input type="checkbox" name="masks[0][0]" value="1" <?php if ($masks[0] & _LF_MASK_WS_) echo 'checked'; ?> /> внутр.</td><td><input type="checkbox" name="masks[0][1]" value="2" <?php if ($masks[0] & _LF_MASK_INET_) echo 'checked'; ?> /> внешн.</td></tr>
<tr><td>показ ссылок</td><td><input type="checkbox" name="masks[1][0]" value="1" <?php if ($masks[1] & _LF_MASK_WS_) echo 'checked'; ?> /> внутр.</td><td><input type="checkbox" name="masks[1][1]" value="2" <?php if ($masks[1] & _LF_MASK_INET_) echo 'checked'; ?> /> внешн.</td></tr>
</table>
<input type="submit" />
</form>
<?php
    }
    else
	echo 'нет доступа к файлу настроек ' . $fileName . '. Проверьте права записи и чтения';