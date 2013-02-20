<?php


error_reporting(0);
require_once('conf.php');
require_once('lib/edb.class.php');

$db = new edb($db_data);
$db->s("SET NAMES UTF8");
unset($db_data);

if (isset($_REQUEST['item_id'])){
    $item_id = (int) $_REQUEST['item_id'];
    $sql = 'SELECT f.id, f.title, f.title_en, f.dir, f.description, f.year , f.imdb_id FROM `films` f WHERE f.id = ' . $item_id;
    $film = $db->line($sql);
    if ($film){
        $sql = 'SELECT  g.title FROM `genres` g INNER JOIN `films_genres` fg ON (fg.genre_id = g.id) WHERE fg.film_id = ' . $item_id;
        $film['genres'] = $db->q($sql);
        $sql = 'SELECT c.title FROM `countries` c INNER JOIN `countries_films` cf ON (cf.country_id = c.id)	WHERE cf.film_id = ' . $item_id;
        $film['countries'] = $db->q($sql);
        $sql = 'SELECT file_name, type FROM film_pictures WHERE film_id = ' . $item_id.' ORDER BY  `film_pictures`.`type` ASC ';
        $film['pictures'] = $db->q($sql);
        if ($film['pictures'])
            $film['image'] = $film['pictures'][0];
        $sql = 'SELECT p.name,p.name_en,p.description,p.url, fp.role, fp.profession_id FROM `persons` p INNER JOIN `films_persons` fp ON p.id = fp.person_id WHERE fp.film_id ='.$item_id;
        $film['persons'] = $db->q($sql);
        $sql = 'SELECT p.title FROM `publishers` p INNER JOIN `films_publishers` fp ON p.id = fp.publisher_id WHERE fp.film_id='.$item_id;
        $film['publishers'] = $db->q($sql);
        $sql = 'SELECT fv.id,fv.video_type_id,fv.resolution, fv.duration, fv.quality_id FROM `film_variants` fv WHERE fv.film_id='.$item_id;
        $film['variants'] = $db->q($sql);

        foreach ($film['variants'] as &$variant){
            $sql = 'SELECT t.language_id,t.translation_id, t.audio_info FROM `tracks` t WHERE t.film_variant_id = '.$variant['id'];
            $variant['tracks'] = $db->q($sql);
        }
        if (isset($_GET["DEBUG"])){
        echo "<pre>";
        var_dump($film);
        echo "</pre>";
        } else
        echo serialize($film);
    }
}

