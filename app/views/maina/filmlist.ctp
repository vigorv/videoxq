<?
echo $this->element('maina/text_filter');
echo $this->element('maina/paginate');
$tvvision->list_view(array(0 => array(
  'id'   => 160,
  'year'   => '1980',
  'film_name_rus'   => 'Название',
  'film_name_org' => 'Оригинальное Название',
  'director'  => 'Режиссер',
  'poster'  => 'http://media1.anka.ws/smallposters/the_public_enemy.jpeg',
  'actors'  => array(
        'Актер 1',
        'Актер 2',
        'Актер 3',
        'Актер 4'
  )), 1 => array(
  'id'   => 160,
  'year'   => '1987',
  'film_name_rus'   => 'Название2',
  'film_name_org' => 'Оригинальное Название2',
  'director'  => 'Режиссер2',
  'poster'  => 'http://media1.anka.ws/smallposters/the_public_enemy.jpeg',
  'actors'  => array(
        'Бабаюнов',
        'Актер 2',
        'Актер 3',
        'Актер 4'
  )), 2 => array(
  'id'   => 160,
  'year'   => '1987',
  'film_name_rus'   => 'Название2',
  'film_name_org' => 'Оригинальное Название2',
  'director'  => 'Режиссер2',
  'poster'  => 'http://media1.anka.ws/smallposters/the_public_enemy.jpeg',
  'actors'  => array(
        'Бабаюнов',
        'Актер 2',
        'Актер 3',
        'Актер 4'
  ))
));
?>

<div class="movies">
    <?
    if (!empty($films))
        foreach ($films as $film):
            extract($film);
            if (!empty($FilmPicture['file_name']))
                $poster = $html->image($imgPath . $FilmPicture['file_name'], array('width' => 80));
            else
                $poster=$html->image('/img/vusic/noposter.jpg', array('width' => 80));
            if ($Film['imdb_rating'] != 0)
                $imdb_rate = '<span class="imdb">IMDb: ' . $Film['imdb_rating'] . '</span>';
            else
                $imdb_rate = '';
            ?>
            <div class="moviePreviewWrapper">
                <div class="poster">
                    <a href="#"><?= $poster; ?></a>
                    <div class="ratings rated_<?= round($MediaRating['rating']) ?>"><div></div></div>
                    <?= $imdb_rate; ?>
                </div>
                <p class="text">
                    <?php
                    $directors = array();
                    $actors = array();
                    if (isset($Person))
                        foreach ($Person as $data) {
                            /*
                              if ($data['FilmsPerson']['profession_id'] == 1 && count($directors) < 4) {
                              if ($lang == _ENG_) {
                              if (!empty($data['name' . $langFix]))
                              $directors[] = $data['name' . $langFix];
                              }
                              else
                              $directors[] = $data['name' . $langFix] ? $data['name' . $langFix] : $data['name_en'];
                              }
                              if (($data['FilmsPerson']['profession_id'] == 3
                              || $data['FilmsPerson']['profession_id'] == 4)
                              && count($actors) < 4) {
                              if ($lang == _ENG_) {
                              if (!empty($data['name' . $langFix]))
                              $actors[] = $data['name' . $langFix];
                              }
                              else
                              $actors[] = $data['name' . $langFix] ? $data['name' . $langFix] : $data['name_en'];
                              } */
                        }
                    if (!empty($directors))
                        echo implode(', ', $directors) . '.';
                    ?> <?= $Film['year'] ?>
                    <span>«<a href="/media/view/<?= $Film['id'] ?>"><?= $Film['title' . $langFix] ?></a>»</span>
                    <?php
                    shuffle($actors);
                    $actors = array_slice($actors, 0, 3);
                    echo implode(', ', $actors);
                    ?>
                    <em><?php
            if (!empty($Genre)) {
                foreach ($Genre as $key => $genre)
                    if ($lang == _ENG_) {
                        if ($key <> 0)
                            echo '/';
                        echo $genre['genres']['title_imdb'];
                    }
                    else {
                        if ($key <> 0)
                            echo '/';
                        echo $genre['genres']['title'];
                    }
            }
                    ?></em>
                </p>
            </div>
        <? endforeach; ?>
</div>