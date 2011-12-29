<?php
/**
 * файл конфигурации скрипта импорта в кэш-таблицу перекрестного поиска
 * подключает конфигурацию сайта
 */

$incInfo = pathinfo(str_replace(chr(92), '/', __FILE__));
$configPath = str_replace('vendors/cache_search', 'config', $incInfo['dirname']);
require($configPath . '/database.php');

require($incInfo['dirname'] . '/ext_site_parser.php');
require($incInfo['dirname'] . '/import_script.php');
