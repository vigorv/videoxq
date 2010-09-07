<?php
/*
echo'<pre>';
print_r($films);
echo'</pre>';
exit;
//*/

$this->set('documentData', array(
		'xmlns:dc' => 'http://purl.org/dc/elements/1.1/'
	)
);

$this->set('channelData', array(
		'title' => __("videoxq.com", true),
		'link' => $html->url('/', true),
		'description' => __("videoxq.com", true),
		'language' => 'ru-Ru'
	)
);
// You should import Sanitize
App::import('Sanitize');
//print_r($film);
foreach ($films as $film)
{
	//*/
	$entryLink = array(
		'controller' => 'media',
		'action' => 'view',
		'id' => $film['Film']['id']
	);
	$entryLink = 'http://www.videoxq.com/media/view/' . $film['Film']['id'];	//ПРЯМАЯ ССЫЛКА

	$pic = '';
	if (count($film['FilmPicture']) > 0)
	{
		$pics = array();
		foreach ($film['FilmPicture'] as $pic)
		{
			if (($pic['type'] == 'poster') || ($pic['type'] == 'smallposter'))
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
			'guid'	=> $entryLink,
			'link' => $entryLink,
			'picture' => $pic,
			'genre' => $genre,
			'description' => '<img src="' . $pic . '" vspace="5" />' . $genre,
		)
	) . "\n";
}