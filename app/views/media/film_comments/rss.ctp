<?php

$this->set('documentData', array(
		'xmlns:dc' => 'http://purl.org/dc/elements/1.1/'
	)
);

$this->set('channelData', array(
		'title' => __('Comments', true),
		'link' => $html->url('/', true),
		'description' => __("forUws", true),
		'language' => 'ru-Ru'
	)
);

//pr($FilmComments);
// You should import Sanitize
App::import('Sanitize');
foreach ($FilmComments as $FilmComment)
{
		$entryLink = array(
			'controller' => 'media',
			'action' => 'view',
			'id' => $FilmComment['Film']['id']
		);
		$editLink = array(
			'controller' => 'FilmComments',
			'action' => 'admin_edit',
			'id' => $FilmComment['FilmComment']['id']
		);
		$description=$FilmComment['FilmComment']['text']."<br>\n";
		$description.="<a href='".Configure::read('App.siteUrl')."/admin/FilmComments/edit/".$FilmComment['FilmComment']['id']."'>Edit</a><br>\n";
		$description.="<a href='".Configure::read('App.siteUrl')."/admin/FilmComments/delete/".$FilmComment['FilmComment']['id']."'>Delete</a>";
		echo $rss->item(array(), array(
				'title' => $FilmComment['Film']['title'],
				'description' => $description,
				'pubDate' => $FilmComment['FilmComment']['created'],
				'author' => $FilmComment['FilmComment']['username'],
				'link' => $entryLink,
			));
};
?>
