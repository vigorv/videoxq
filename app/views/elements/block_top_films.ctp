<div class="module">
	<div>
		<div>
			<div>
				<table>
				<tr valign="middle">
					<td width="100%"><h4><?php __("Top");
	$divStyle = ''; $imgSrc = 'desc';
	if (empty($blockStatuses['slidertop']))
	{
		$divStyle = ' style="display: none"';
		 $imgSrc = 'asc';
	}
					?> 10</h4></td>
					<td><a id="slidertop" rel="slider" href=""><img width="11" src="/img/s_<?php echo $imgSrc; ?>.png" /></a></td>
				</table>
				<div id="slidertopdiv"<?php echo $divStyle; ?>>
<?php
$lang = Configure::read('Config.language');
$langFix = '';
if ($lang == _ENG_) $langFix = '_en';
foreach ($block_top_films as $post)
{
    echo '<p><a href="/media/view/' . $post['Film']['id'] . '">'
         . h($post['Film']['title' . $langFix]) . '</a></p>';
}
?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
//СТАТИСТИКУ ПОКАЗЫВАЕМ ПО ВСЕМ ФИЛЬМАМ if ($isWS)
{
	$divStyle = ''; $imgSrc = 'desc';
	if (empty($blockStatuses['sliderstat']))
	{
		$divStyle = ' style="display: none"';
		 $imgSrc = 'asc';
	}
	echo '
<div class="module">
	<div>
		<div>
			<div>
				<table>
				<tr valign="middle">
					<td width="100%"><h4>' . __('Statistics', true) . '</h4></td>
					<td><a id="sliderstat" rel="slider" href=""><img width="11" src="/img/s_' . $imgSrc . '.png" /></a></td>
				</table>
				<div id="sliderstatdiv"' . $divStyle . '>
    <p>' . __("Total in database", true) . ' <strong>' . $app->pluralForm($filmStats['count'], array(__("film", true), __("filma", true), __("films", true))) . '</strong>
	<br>' . __("Total duration", true) . ' <strong>' . $app->timeFormat($filmStats['size']) . '</strong>
    </p>
				</div>
			</div>
		</div>
	</div>
</div>
	';
}

?>
<script type="text/javascript">
<!--
	function switchOrder(id)
	{
		if ($('#' + id + ' img').attr("src") == "/img/s_asc.png")
			$('#' + id + ' img').attr("src", "/img/s_desc.png");
		else
			$('#' + id + ' img').attr("src", "/img/s_asc.png");
	}

	$('a[rel=slider]').click(function () {
		$('#' + this.id + 'div').slideToggle("slow");
		switchOrder(this.id);
		$.post("/media/ajax/switchblock", { blockname: this.id } );
      return false;
    });
	$('a[rel=slider]').mouseover(function () {
		switchOrder(this.id);
      return false;
    });
	$('a[rel=slider]').mouseout(function () {
		switchOrder(this.id);
      return false;
    });
-->
</script>