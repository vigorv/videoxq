<?php
header('Content-Type: text/xml; charset=utf-8');
Configure::write('debug', 0);
echo $content_for_layout;
