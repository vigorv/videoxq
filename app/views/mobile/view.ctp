<?php

$isVip = (!empty($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups));

$HQTypes = array(
    9 => 'HDrip'
);

$SQTypes = array(
    2 => 'DVDrip',
    3 => 'DVDScr',
    4 => 'SATrip',
    5 => 'Telecine',
    6 => 'VHSrip',
    10 => 'TVrip',
);

$mobTypes = array(
    11 => '270p'
);

$webTypes = array(
    12 => 'url'
);

if (($lang == _ENG_) && (empty($imdb_website))) {
    echo '<h3 style="margin-left:45px;">' . __('Sorry, we do not have a detailed description of the movie', true) . ' &laquo;' . $film['Film']['title_en'] . '&raquo;</h3><br /><br /><br />';
} else {
    $posterTitle = '';
    extract($film);
    $posters = Set::extract('/FilmPicture[type=poster]/.', $film);
    $bigposters = Set::extract('/FilmPicture[type=bigposter]/.', $film);


?>
<div class="Movie">
    <?php
    if ($lang == _ENG_) {
        $imdbTitle = $parser->getMovieTitle($imdb_website);
        $imdbActors = array();
        $a_actors = $parser->getMovieActors($imdb_website, $name_and_id = True);
        for ($i = 0; $i < count($a_actors[1]); $i++)
            $imdbActors[] = $a_actors[2][$i];

        $imdbActors = implode(', ', $imdbActors);
        $imdbCountries = array();
        $imdbCountry = $parser->getMovieCountry($imdb_website);

        for ($i = 0; $i < count($imdbCountry); $i++)
            $imdbCountries[] = $imdbCountry[$i][1];

        $imdbCountries = implode(', ', $imdbCountries);

        $imdbDirectedBy = array();
        $imdbDirectors = $parser->getMovieDirectedBy($imdb_website);

        for ($i = 0; $i < count($imdbDirectors); $i++)
            $imdbDirectedBy[] = $imdbDirectors[$i][1];

        $imdbDirectedBy = implode(', ', $imdbDirectedBy);

        $imdbWrittenBy = array();
        $imdbWriters = $parser->getMovieWriters($imdb_website);

        for ($i = 0; $i < count($imdbWriters); $i++)
            $imdbWrittenBy[] = $imdbWriters[$i][1];

        $imdbWrittenBy = implode(', ', $imdbWrittenBy);

        $imdbGenres = array();
        $imdbGenre = $parser->getMovieGenres($imdb_website);

        for ($i = 0; $i < count($imdbGenre); $i++) {
            $imdbGenres[] = $imdbGenre[$i][1];
        }

        $imdbGenres = implode(', ', $imdbGenres);

        $imdbRating = $parser->getMovieStars($imdb_website);
    }
    ?>

    <div style="float: right; clear: both;">
        <?php
        $imgUrl = $imgPath . $posters[array_rand($posters)]['file_name'];
        $img = $html->image($imgUrl, array('class' => 'poster', 'title' => $posterTitle));
        echo $html->link($img, $imgUrl, array('rel' => 'posters', 'title' => $posterTitle), false, false) . "\n";
        ?>
        <div id="posters" style="display: none;">
            <?php
            if (!empty($authUser['userid'])) {
                $posters = am($bigposters, $posters);
                foreach ($posters as $poster)
                    echo $html->link($imgPath . $poster['file_name'], null, array('rel' => 'posters')) . "\n";
            }
            ?>
        </div>

    </div>
    <h4><?= __('View online', true); ?></h4
    <div class="Movie_online">


    </div>

</div>
<? } ?>