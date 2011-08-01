<?php
header('Content-Type: text/html; charset=utf-8');
Configure::write('debug', 0);
echo $content_for_layout;
?>