<?php
echo $this->element('maina/paginate');
$html->addCrumb(__('Films', true), '');
$html->addCrumb(__('Download History', true), '');
echo $html->getCrumbs(' > ', 'Home');
?>
<h3>История Скаченного</h3>
<div class="movies"> ---------------
<?php
foreach ($history as $hinfo)
{
    extract($hinfo['film']);
    if (!empty($FilmPicture[0]['file_name']))
        $poster = $html->image($imgPath . $FilmPicture[0]['file_name'], array('width' => 80));
    else
        $poster=$html->image('/img/vusic/noposter.jpg', array('width' => 80));
    if ($Film['imdb_rating'] != 0)
        $imdb_rate = '<span class="imdb">IMDb: ' . $Film['imdb_rating'] . '</span>';
    else
        $imdb_rate = '';
    ?>

    <div class="moviePreviewWrapper">
    <?= $hinfo['film_clicks']['created']; ?>
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
                foreach ($Person as $p) {
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
                      }
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
<?php
}
?>
</div>