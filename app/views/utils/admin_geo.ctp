<?php
echo '<h3>Данные GeoIp</h3>';
echo '<p>(источник: ' . $url . ')</p>';
if (!empty($add))
	echo '<h3>Parsing Geoip dump... ' . $add . ' lines added</h3>';

if (!empty($updated))
{
	echo '<p>Данные обновлены ' . date('Y-d-m H:i:s', $updated) . '</p>';
}
else
{
	echo '<p>Последняя дата обновления не определена (отсутствует дамп-файл)</p>';
}

echo '<br /><button title="скачать и обновить данные" onclick="location.href=\'/admin/utils/geo/import\'"> Import GeoIpDump </button>';
