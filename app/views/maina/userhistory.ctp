<?php
echo $this->element('maina/paginate');
$html->addCrumb(__('Films', true), '');
$html->addCrumb(__('Download History', true), '');
echo $html->getCrumbs(' > ', 'Home');
/*
$tvVision->eskiz(
	array(0 => array(
		'id'			=> 160,
		'title' 		=> 'Название',
		'titleOriginal' => 'Оригинальное Название',
		'director'		=> 'Режиссер',
		'actors'		=> array(
								'Актер 1',
								'Актер 2',
								'Актер 3'
		)
	))
);
*/
?>
<h3>История Скаченного</h3>
<div class="movies">
<?php
	$filmInfo = array();
foreach ($history as $hinfo)
{
    extract($hinfo['film']);
    if (!empty($FilmPicture[0]['file_name']))
        $poster = $imgPath . $FilmPicture[0]['file_name'];
    else
        $poster = '/img/vusic/noposter.jpg';

    if ($Film['imdb_rating'] != 0)
        $imdb_rate = '<span class="imdb">IMDb: ' . $Film['imdb_rating'] . '</span>';
    else
        $imdb_rate = '';

    $MediaRating['rating'];

    $director = '';
    $actors = array();
//pr($Person);
//exit;
    if (isset($Person))
        foreach ($Person as $p) {
			if ($p['Profession']['id'] == 1 && empty($director))
			{
			    if ($lang == _ENG_)
    			{
                    if (!empty($p['Person']['name_en']))
                    	$director = $p['Person']['name_en'];
                }
                else
					$director = $p['Person']['name'] ? $p['Person']['name'] : $p['Person']['name_en'];
			}

			if (($p['Profession']['id'] == 3) && count($actors) < 3)
			{
				if ($lang == _ENG_)
				{
					if (!empty($p['Person']['name_en']))
						$actors[] = $p['Person']['name_en'];
				}
				else
					$actors[] = $p['Person']['name'] ? $p['Person']['name'] : $p['Person']['name_en'];
			}
        }

	$filmInfo[] = array(
		'id'				=> $Film['id'],
		'year'				=> $Film['year'],
		'poster'			=> $poster,
		'film_name_rus'		=> $Film['title'],
		'film_name_org'		=> $Film['title_en'],
		'director'			=> $director,
		'actors'			=> $actors
	);
	//pr($filmInfo);
}

?>
</div>