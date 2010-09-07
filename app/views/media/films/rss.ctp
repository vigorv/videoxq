<?php
//*

$this->set('documentData', array(
		'xmlns:dc' => 'http://purl.org/dc/elements/1.1/'
	)
);

$this->set('channelData', array(
		'title' => __($film['Film']['title'], true),
		'link' => $html->url('/', true),
		'description' => __("forUws", true),
		'language' => 'ru-Ru'
	)
);
//*/
$entryLink = array(
	'controller' => 'media',
	'action' => 'view',
	'id' => $film['Film']['id']
);
$entryLink = 'http://www.videoxq.com/media/view/' . $film['Film']['id'];	//ПРЯМАЯ ССЫЛКА

// You should import Sanitize
App::import('Sanitize');
//print_r($film);
$pic = '';
if (count($film['FilmPicture']) > 0)
{
	$pics = array();
	foreach ($film['FilmPicture'] as $pic)
	{
		if ($pic['type'] == 'poster')
		{
			$pics[] = $pic['file_name'];
		}
	}
	if (!empty($pics))
	{
		$rndKeys = array_rand($pics, 1);
		$pic = Configure::read('Catalog.imgPath') . $pics[$rndKeys];
	}
	else
		$pic = '/img/vusic/noposter.jpg';
}
$genre = '';
if (count($film['Genre']) > 0)
{
	$genres = array();
	foreach ($film['Genre'] as $genre)
	{
		$genres[] = $genre['title'];
	}
	$genre = implode(',', $genres);
}
echo $rss->item(array(), array(
		'title' => $film['Film']['title'],
		'link' => $entryLink,
		'picture' => $pic,
		'genre' => $genre,
	)
);
