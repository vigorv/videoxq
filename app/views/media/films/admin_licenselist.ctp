<?php
	switch ($subaction)
	{
		case"getregions":
			echo "0\tВыберите регион";
			foreach ($regions as $r)
				echo "\n" . $r['Georegion']['id'] . "\t" . $r['Georegion']['name'];
		break;

		case"getregionfilms":
			foreach ($films as $f)
				echo $f['id'] . "\t" . $f['title'] . "\n";
		break;

		default:
if (isset($allCnt))
{
	echo '<p>' . __('list_total_count', true) . ': ' . $allCnt . '</p>';
	echo '<p>' . __('list_processed_count', true) . ': ' . $addCnt . '</p>';
}
?>

<p>Укажите список адресов видеокаталога:</p>
<form action="/admin/media/licenselist" method="post">

<select name="georegion_id" id="georegion_id" onchange="return getfilms('getregionfilms', this.value)">
<option value="0">Выберите регион</option>
</select><br />

<select disabled="disabled" name="geocity_id" id="geocity_id">
<option value="0">Выберите город</option>
</select><br />
<?php
	$data = null;
	$lst = null;
	echo '<br />установить/сбросить признак лицензии' . $form->checkbox('is_license', array('width' => '20px'));
	echo '<br />установить/сбросить связь с регионом' . $form->checkbox('setgeo', array('width' => '20px'));
	echo $form->textarea('lst', array('rows' => 15));
?>
<input type="submit" />
</form>
<script type="text/javascript">
<!--
tinyMCE = null;

	regionSelect = null;
	filmNames = null;
	filmLinks = null;

	function loadregions()
	{
		regionSelect = document.getElementById("georegion_id");
		while (regionSelect.options.length) regionSelect.options[0]=null;
		$.get("/admin/media/licenselist/getregions", {  },
		function(data){
			regions = data.split("\n");
			for (r=0; r < regions.length; r++)
			{
				option = regions[r].split("\t");
			    i = regionSelect.options.length;
			    regionSelect.options.length = i + 1;
			    regionSelect.options[i].value = option[0];
			    regionSelect.options[i].text = option[1];
			}
		});
	}

	function getfilms(action, id)
	{
		filmNames = document.getElementById("filmNames");
		filmNames.innerHTML = '';
		filmLinks = document.getElementById("filmLinks");
		filmLinks.innerHTML = '';

		$.get("/admin/media/licenselist/" + action + "/" + id, {  },
		function(data){
			films = data.split("\n");
			for (r=0; r < films.length; r++)
			{
				nameLink = films[r].split("\t");
				if (nameLink.length < 2) continue;
			    filmLinks.innerHTML += '<?php echo Configure::read('App.siteUrl');?>media/view/' + nameLink[0] + "<br />";
			    filmNames.innerHTML += nameLink[1] + "<br />";
			}
		});
	}

	loadregions();
-->
</script>

<h3>Закрепленные за регионом/городом фильмы</h3>
<table width="100%" cellpadding="5" border="1">
<thead>
	<td width="50%">
		Название фильма
	</td>
	<td>
		Ссылка на фильм
	</td>
</thead>
<tr>
	<td>
		<div id="filmNames" width="100%" height="400px">
		</div>
	</td>
	<td>
		<div id="filmLinks" width="100%" height="400px">
		</div>
	</td>
</tr>
</table>

<?php
	}
