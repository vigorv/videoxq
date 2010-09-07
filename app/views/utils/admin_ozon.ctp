<div align="left">
<?php
	switch($action)
	{
		case "import":

		default:
			foreach ($ozonList as $ol)
			{
				if (!empty($ol['updated']))
				{
					echo '<p>' . basename($ol['xml']) . ' обновлен ' . date('Y-d-m H:i:s', $ol['updated']) . '</p>';
				}
			}
	}
	echo '<br /><p>Всего категорий: ' . $ozonCategoryCount . '</p>';
	echo '<p>Всего продуктов: ' . $ozonProductCount . '</p>';
	echo '<br /><input type="button" style="width:150px" value="импорт" title="импорт из каталога OZON.ru" onclick="if (confirm(\'Вы уверены?\')) location.href=\'/admin/utils/ozon/import\';" />';
?>
</div>