<?php 
    $javascript->link('jquery.fancybox-1.0.0', false);
    $javascript->link('jquery.pngFix', false);
    $script = "$(function() {
	 $('p.gallery').fancybox({
        'zoomSpeedIn':  0,
       'zoomSpeedOut': 0,
       'overlayShow':  true,
       'overlayOpacity': 0.8
       });
     });";
      $javascript->codeBlock($script, array('inline' => false));

echo $page['Page']['text'];
?>


