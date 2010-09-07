<div class="мфкшфтеы form">
<?php echo $form->create('FilmVariant', array('action' => 'edit'));?>
	<fieldset>
 		<legend><?php __('Edit FilmVariant');?></legend>
	<?php
		echo $form->hidden('flag_catalog', array("name" => "data[FilmVariant][flag_catalog]", "value" => $filmVariant["FilmVariant"]["flag_catalog"]));
		echo $form->hidden('id', array("name" => "data[FilmVariant][id]", "value" => $filmVariant["FilmVariant"]["id"]));
		echo $form->input('videoType', array("name" => "data[FilmVariant][video_type_id]", "value" => $filmVariant["FilmVariant"]["video_type_id"]));
		echo $form->input('resolution', array("value" => $filmVariant["FilmVariant"]["resolution"]));
		echo $form->input('duration', array("selected" => $filmVariant["FilmVariant"]["duration"]));
		echo $form->input('active', array("value" => 1, "checked" => ($filmVariant["FilmVariant"]["active"]) ? "checked" : ""));
        echo $form->input('created', array('timeFormat' => 24, "selected" => $filmVariant["FilmVariant"]["created"]));
        echo $form->input('modified', array('timeFormat' => 24, "selected" => $filmVariant["FilmVariant"]["modified"]));
	?>
		<legend><?php __('Related FilmTrack');?></legend>
	<?php
		echo $form->hidden('track_id', 		array("name" => "data[Track][id]", "value" => $filmVariant["Track"]["id"]));
		echo $form->input('language', 		array("name" => "data[Track][language_id]", "value" => $filmVariant["Track"]["language_id"]));
		echo $form->input('translation',	array("name" => "data[Track][translation_id]", "value" => $filmVariant["Track"]["translation_id"]));
		echo $form->input('audio_info',		array("name" => "data[Track][audio_info]", "value" => $filmVariant["Track"]["audio_info"]));
	?>
		<legend><?php __('Related FilmFiles');?></legend>
	<?php
        //echo $form->hidden('FilmFiles.film_variant_id', array('value' => $film["Film"]["id"]));
		for ($i = 0; $i < 3; $i++)
		{
			if (!isset($filmVariant["FilmFile"][$i]))
				break;
	?>
		<legend><?php __('FilmFile'); echo' ' . ($i + 1);?></legend>
	<?php
		echo $form->hidden('FilmFile.' . $i . '.id',	array("name" => "data[FilmFile][$i][id]", "value" => $filmVariant["FilmFile"][$i]["id"]));
		echo $form->input('FilmFile.' . $i . '.file_name',	array("name" => "data[FilmFile][$i][file_name]", "value" => $filmVariant["FilmFile"][$i]["file_name"]));
		echo $form->input('FilmFile.' . $i . '.size',		array("name" => "data[FilmFile][$i][size]", "value" => $filmVariant["FilmFile"][$i]["size"]));
		echo $form->input('FilmFile.' . $i . '.dcpp_link',	array("name" => "data[FilmFile][$i][dcpp_link]", "value" => $filmVariant["FilmFile"][$i]["dcpp_link"]));
		echo $form->input('FilmFile.' . $i . '.ed2k_link',	array("name" => "data[FilmFile][$i][ed2k_link]", "value" => $filmVariant["FilmFile"][$i]["ed2k_link"]));
		echo $form->input('FilmFile.' . $i . '.server_id',	array("name" => "data[FilmFile][$i][server_id]", "value" => $filmVariant["FilmFile"][$i]["server_id"]));
		}
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
