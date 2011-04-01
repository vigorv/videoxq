<?php
echo $paginator->prev('&larr;', array('escape' => false), null) . '&nbsp;';
//echo $paginator->numbers(array('separator' => ' ', 'format' => '%page%, %pages%, %current%, %count%, %start%, %end%')) . '&nbsp;';
//echo $paginator->counter(array('separator' => ' ', 'format' => '%page%, %pages%, %current%, %count%, %start%, %end%')) . '&nbsp;';
echo $paginator->numbers(array('separator' => ' ', 'tag' => null)) . '&nbsp;';
echo $paginator->next('&rarr;', array('escape' => false), null);
?>
