<?php
$curPath = getcwd();
$paths=explode('/',$curPath);
$unset=count($paths)-1;
unset($paths[$unset]);
$curPath=implode('/',$paths);
//include $curPath ."/app/views/elements/block_menu/menu.php";
print_r($_SERVER);
?>