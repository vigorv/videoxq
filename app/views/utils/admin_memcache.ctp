<?php

/*
УСТАНОВКА ДЛЯ videoxq.com - требуется правка кода memcache.php
	- вставить в начало файла объявление global $MEMCACHE_SERVERS;
	- закомментировать password protect
	- добавить выход на входе getFooter и getHeader
	- заменить строку "$PHP_SELF=$PHP_SELF.'?';" на "global $PHP_SELF; $PHP_SELF='/admin/utils/memcache/?';";
*/
include_once($_SERVER["DOCUMENT_ROOT"] . '/app/vendors/memcache.php');