<?php
header('Content-Type: text/html; charset=windows-1251');
Configure::write('debug', 0);
echo $content_for_layout;
if (!empty($blockContent['external']))
    echo iconv('utf-8', 'windows-1251', $this->element('blocks', array('blockArray' => $blockContent['external']))) ;
?>