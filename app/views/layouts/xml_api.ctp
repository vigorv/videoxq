<?php
header('Content-Type: text/html; charset=utf-8');
Configure::write('debug', 0);
echo '<?xml version="1.0" encoding="utf-8" ?>';
echo $content_for_layout;
?>