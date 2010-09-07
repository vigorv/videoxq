<?php
//pr($blockArray);
foreach ($blockArray as $block)
{
    switch ($block['type'])
    {
        case 'php':
            eval($block['content']);
            break;
        case 'element':
        case 'component+element':
            echo $this->element($block['element']);
            break;
        case 'text':
        case 'component':
            echo $block['content'];
            break;
//        case 'component+element':
//            echo $this->element($block['content']);
//            break;
    }
}
?>
