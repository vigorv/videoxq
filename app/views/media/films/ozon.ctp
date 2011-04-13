<div id="content">
<h2>«<?php
    if ($lang == _ENG_)
   		echo $film["Film"]['title_en'];
   	else
   		echo $film["Film"]['title'];
?>»</h2>
<table width="100%"><tr>
<td class="ozonleft">
<h3><?php __("Buy on");
	echo " OZON.ru";
?></h3>
</td>
<td class="ozonright">
<h3><?php echo '<a href="/media/view/' . $film['Film']['id'] . '">'; __("Download Movie"); echo '</a>'; ?></h3>
</td>
</tr></table>
<?php
	if (!empty($ozons))
	{
		foreach ($ozons as $o)
		{
			$pr = (!empty($o["OzonProduct"]['price'])) ? (sprintf("%01.2f", $o["OzonProduct"]['price'])) : "";
			$cur = (!empty($o["OzonProduct"]['currency'])) ? (' (' . $o["OzonProduct"]['currency'] . ') ') : "";
			$year = (!empty($o["OzonProduct"]['year'])) ? (', ' . $o["OzonProduct"]['year']) : "";
			$media = (!empty($o["OzonProduct"]['media'])) ? (', ' . $o["OzonProduct"]['media']) : "";
			$url = $o["OzonProduct"]['url'];
			echo'<div class="ozonlist"><a target="_blank" href="' . $url . '"><img align="left" hspace="3" width="80" src="' . $o["OzonProduct"]['picture'] . '" />' . $o["OzonProduct"]['title'] . $year . $media . '<br /><b>' . $pr . $cur . '</b></a></div>';
		}
	}
?>
</div>