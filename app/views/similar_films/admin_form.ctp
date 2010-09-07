
<h3><?php echo (empty($group)) ? 'Создать' : 'Редактировать'; ?>
 группу</h3>
<?php
    echo $javascript->link('jquery.bgiframe.min');
    echo $javascript->link('jquery.ajaxQueue');
    echo $javascript->link('jquery.autocomplete.pack');
?>
<script language="javascript">
	tinyMCE = 0;
   $(document).ready(function() { $("#similarFilmsId").autocomplete('/similarFilms/autoComplete', properties = {
                                                                            matchContains: true,
                                                                            minChars: 2,
                                                                            selectFirst: true,
                                                                            intro_text: "",
                                                                            no_result: "",
                                                                            result_field: "data[Tag][Tag]",
                                                                            width: 184,
                                                                            multiple: true
                                                                        });

                                                });
</script>

<?php

	echo $html->css('jquery.autocomplete', null, array());

	echo '<form name="groupform" action="/admin/similar_films/save" method="post">';
	echo '
' . $form->input('SimilarFilm.id',	array("type" => "hidden", "name" => "data[SimilarFilm][id]",	"value" => $group["SimilarFilm"]["id"])) . '
' . $form->input('SimilarFilm.title',	array("name" => "data[SimilarFilm][title]",	"value" => $group["SimilarFilm"]["title"])) . '
';
    echo $form->text('SimilarFilm.films', array('class' => 'textInput', 'id' => 'similarFilmsId', 'type' => 'text',
    	'value' => $group["SimilarFilm"]["films"]
    	, 'autocomplete' => 'off', 'between' => '<br />', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')
    	));

//' . $form->text('SimilarFilm.films',	array("type" => "text", "id" => "similarFilmsId", "name" => "data[SimilarFilm][films]",	"value" => $group["SimilarFilm"]["films"])) . '
echo '
' . $form->end('Submit') . '
	<p><a href="/admin/similar_films">' . __('back', true) . '</a></p>
	</form>
';
?>

