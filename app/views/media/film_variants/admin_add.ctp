<div class="мфкшфтеы form">
<?php echo $form->create('FilmVariant');?>
	<fieldset>
 		<legend><?php __('Add FilmVariant'); echo' (' . $film["Film"]["title"]. ')';?></legend>
	<?php
		echo $form->hidden('film_id', array('value' => $film["Film"]["id"]));
		echo $form->hidden('flag_catalog', array('value' => 1));
		echo $form->input('videoType', array("name" => "data[FilmVariant][video_type_id]"));
		echo $form->input('resolution');
		echo $form->input('duration');
		echo $form->input('active');
        echo $form->input('created', array('timeFormat' => 24));
        echo $form->input('modified', array('timeFormat' => 24));
	?>
		<legend><?php __('Related FilmTrack');?></legend>
	<?php
		echo $form->input('language', 		array("name" => "data[Track][language_id]"));
		echo $form->input('translation',	array("name" => "data[Track][translation_id]"));
		echo $form->input('audio_info',		array("name" => "data[Track][audio_info]"));
	?>
		<legend><?php __('Related FilmFiles');?></legend>
	<?php
        //echo $form->hidden('FilmFiles.film_variant_id', array('value' => $film["Film"]["id"]));
		for ($i = 0; $i < 3; $i++)
		{
	?>
		<legend><?php __('FilmFile'); echo' ' . ($i + 1);?></legend>
	<?php
		echo $form->input('FilmFile.' . $i . '.file_name');
		echo $form->input('FilmFile.' . $i . '.size');
		echo $form->input('FilmFile.' . $i . '.dcpp_link');
		echo $form->input('FilmFile.' . $i . '.ed2k_link');
		echo $form->input('FilmFile.' . $i . '.server_id');
		}
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
