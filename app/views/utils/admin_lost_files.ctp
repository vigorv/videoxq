<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Search Logs', true), array('action'=>'search_logs')); ?></li>
		<li><?php echo $html->link(__('Film Clicks', true), array('action'=>'film_clicks')); ?></li>
        <li><?php echo $html->link(__('Translit Results', true), array('action'=>'transtats')); ?></li>
        <li><?php echo $html->link(__('TV', true), array('action'=>'tvs')); ?></li>
    </ul>
</div>
<form action="" method="post">
<input type="submit" value="Сбросить исправленные" />
<table cellpadding="0" cellspacing="0">
<tr>
    <th>Film.title</th>
<!--    <th>FilmFile.file_name</th>-->
    <th>Status</th>
</tr>
<?php
$i = 0;
foreach ($films as $film)
{
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr <?php echo $class;?>>
        <td>
            <?php echo $film['Film']['title'] . ' (http://videoxq.com/media/view/' . $film['Film']['id'] . ')'; ?>
        </td>
<!--
        <td>
            <?php
            echo $film['FilmFile']['file_name'];
            ?>
        </td>
-->
        <td>
<?php

//ПРОВЕРКА ФИЗИЧЕСКОГО ЧТЕНИЯ
	$ans = false;
   	$lnk = Film::set_input_share($film['Film']['dir']).'/' . $film['FilmFile']['file_name'];
	$lnk = parse_url($lnk);
	$host = $lnk["host"];
	//$lnk = str_replace('.avi', '111.avi', $lnk['path']);//ПОРТИМ ИМЯ ДЛЯ ТЕСТА
	$lnk = $lnk['path'];
	//$lnk = explode('/', $lnk['path']);
	//$host = $lnk[2];
	//unset($lnk[0]);
	//unset($lnk[1]);
	//unset($lnk[2]);
	//$lnk = '/' . implode('/', $lnk);
	$fp = fsockopen($host, 80, $errno, $errstr, 30);
	if (!$fp) {
	    echo "$errstr ($errno)<br />\n";
	} else {
	    $out = "GET " . $lnk . " HTTP/1.1\r\n";
	    $out .= "Host: " . $host . "\r\n";
	    $out .= "Referer: {$_SERVER['HTTP_REFERER']}/\r\n";
	    $out .= "Connection: Close\r\n\r\n";

	    fwrite($fp, $out);
//	    while (!feof($fp)) {
//	        echo fgets($fp, 128);
//	    }
		$ans = fgets($fp, 128);
	}

	if (!preg_match('/http(.*?)200/i', $ans))
	{
		echo '<font color="red">http://' . $host . $lnk . '</font><br />(' . $ans . ')';
	}
	else
	{
		echo '<input type="hidden" name="data[' . $film['FilmFile']['filmfile_id'] . ']" value="' . $film['FilmFile']['filmfile_id'] . '" />';
		echo '<font color="green">' . $ans . '</font>';
	}
	echo '</p>';
    fclose($fp);
?>

    </td>
    </tr>
<?php } ?>
</form>
</table>
