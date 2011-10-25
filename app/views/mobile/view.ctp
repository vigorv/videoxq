<?php
$isVip = (!empty($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups));

//print_r($film);
function sortLL($a, $b) {
    return strnatcmp($a['Film']['title'], $b['Film']['title']);
}

$posterTitle = '';
extract($film);
$posters = Set::extract('/FilmPicture[type=poster]/.', $film);
$bigposters = Set::extract('/FilmPicture[type=bigposter]/.', $film);

$imgUrl = $imgPath . $posters[array_rand($posters)]['file_name'];
$img = $html->image($imgUrl, array('class' => 'poster', 'title' => $posterTitle));

$directors = array();
$story = array();
$actors = array();
if (!empty($persons))
    foreach ($persons as $personRow) {
        extract($personRow);
        if (!empty($Person['name']))
            $name = $Person['name'];
        else
            $name = $Person['name_en'];
        $link = '<a href="' . '/mobile/people/' . $Person['id'] . '">' . $name . '</a>';
        if (isset($Profession[1]))
            $directors[] = $link;
        if (isset($Profession[2]) || isset($Profession[22]))
            $story[] = $link;
        if (isset($Profession[3]) || isset($Profession[4]))
            $actors[] = $link;
    }
$actors = array_slice($actors, 0, 10);
//$actors[] = '<a href="#">' . __('more', true) . '...</a>';
?>
<?
if ($lang == _ENG_) {
    if ((empty($imdb_website))) {
        echo '<h3 style="margin-left:45px;">' . __('Sorry, we do not have a detailed description of the movie', true) . ' &laquo;' . $film['Film']['title_en'] . '&raquo;</h3><br /><br /><br />';
    } else {
        $Actors = array();
        $a_actors = $parser->getMovieActors($imdb_website, $name_and_id = True);
        for ($i = 0; $i < count($a_actors[1]); $i++)
            $Actors[] = $a_actors[2][$i];
        $Actors = implode(', ', $Actors);
        $countries = array();
        $imdbCountry = $parser->getMovieCountry($imdb_website);

        for ($i = 0; $i < count($imdbCountry); $i++)
            $countries[] = $imdbCountry[$i][1];

        $countries = implode(', ', $countries);

        $directedBy = array();
        $imdbDirectors = $parser->getMovieDirectedBy($imdb_website);

        for ($i = 0; $i < count($imdbDirectors); $i++)
            $directedBy[] = $imdbDirectors[$i][1];

        $directedBy = implode(', ', $directedBy);

        $writtenBy = array();
        $imdbWriters = $parser->getMovieWriters($imdb_website);

        for ($i = 0; $i < count($imdbWriters); $i++)
            $writtenBy[] = $imdbWriters[$i][1];

        $writtenBy = implode(', ', $writtenBy);
        $imdbGenres = array();
        $imdbGenre = $parser->getMovieGenres($imdb_website);
        for ($i = 0; $i < count($imdbGenre); $i++) {
            $imdbGenres[] = $imdbGenre[$i][1];
        }
        $imdbGenres = implode(', ', $imdbGenres);
        $imdbRating = $parser->getMovieStars($imdb_website);



        $title = $parser->getMovieTitle($imdb_website);
        $title_orig = '';

        if (!empty($imdbRating))
            $rating = '<strong>IMDb: ' . $imdbRating . '</strong>';
        else
            $rating = '';

        $Genres = $imdbGenres;
        $description = $parser->getMovieStory($imdb_website);
    }
} else {
    $title = $Film['title'];
    $title_orig = '<h3>' . $Film['title_en'] . '</h3>';
    $countries = $app->implodeWithParams(', ', $Country);
    if ($Film['imdb_rating'] != 0)
        $rating = '<strong>IMDb: ' . $Film['imdb_rating'] . '</strong>';
    else
        $rating = '';
    if (!empty($directors))
        $directedBy = implode(', ', $directors);
    if (!empty($story))
        $writtenBy = implode(', ', $story);
    if (!empty($actors))
        $Actors = implode(', ', $actors);
    if (!empty($Genre))
        $Genres = $app->implodeWithParams(', ', $Genre);
    $description = $Film['description'];
}
?>

<li class="videoinfo">
    <? if (isset($FilmVariant) && (!empty($FilmVariant[0]['FilmFile']))) :
        $lnk = Film::set_input_server($Film['dir']) . '/' . $FilmVariant[0]['FilmFile'][0]['file_name']; ?>
        <div style="text-align:center;margin:auto">
            <? if (count($bigposters)):
                $imgUrl = $imgPath . $bigposters[0]['file_name']; ?>
                <img id="poster" height="400px" width="300px" src="<?= $imgUrl; ?>" />
            <? else: ?>

                <img id="poster" height=200 src="<?= $imgUrl; ?>" />
            <? endif; ?>
        </div>
        <br/>
        <br/>
        <div id="Can_play" style="display:none;">

            <a href="#" onClick="document.getElementById('VideoPlayer').play();return false;"><?= __('Click To Play', true); ?></a>
        </div>
        <video id="VideoPlayer" style="position:absolute;top:-320px;left:-320px;max-width:320px" onloadstart="$('#Can_play').show(); $('#WaitForFilm').hide();" tabindex="0" height="auto" onclick="this.play();" >
            <source  src="<?= $lnk; ?>" />
            <source  type="video/mp4" src="<?= $lnk; ?>" />
        </video>


        <span id="WaitForFilm" style="font-style:italic; color:green; font-size:0.7em;"> 
            <? if ($lang <> _ENG_): ?>Над этим текстом появится ссылка, если фильм доступен для воспроизведения <? else: ?>
                You 'll see link below this text, if video aviable to play
            <? endif; ?>
        </span>
    <? else: ?>
        <img height="200px" src="<?= $imgUrl; ?>" /><br/>
        <span style="font-style:italic; color:red; font-size:0.7em;">
            <? if ($lang <> _ENG_): ?>Нет видео для воспроизведения <? else: ?>
                No video to play<? endif; ?>
        </span>
    <? endif; ?>

    <h2>«<?= $title; ?>»</h2>
    <?= $title_orig; ?>
    <?= $countries; ?>            
    <?= $Film['year']; ?>
    <?= $rating; ?>
    <?php if (isset($directedBy)) { ?>
        <h4><?= __('Directed by'); ?>:</h4>
        <p id="directors"><?= $directedBy; ?></p>
        <?php
    }
    if (isset($writtenBy)) {
        ?>
        <h4><?php __('Writers'); ?>:</h4>
        <p id="story"><?= $writtenBy; ?></p>
        <?
    }
    if (isset($Actors)) {
        ?>
        <h4><?php __('Actors'); ?>:</h4>
        <p id="actors"><?= $Actors ?></p>
        <?php
    }
    if (!empty($Genre)) {
        ?>
        <h4><?php __('Genre'); ?>:</h4>
        <p><?= $Genres; ?></p>
    <?php } ?>

    <p><?= $description; ?></p>
    <br/>
    <?php
    if ($isWS)
        $geoIsGood = true;

    if (($geoIsGood) && ($Film['is_license']) && ($authUser['userid']))
        $isWS = true;

    $panels = array();

    if ($geoIsGood) :
        $language = ''; //на случай неустановленной информации о трэке
        $translation = ''; //на случай неустановленной информации о трэке
        $audio_info = ''; //на случай неустановленной информации о трэке
        $divxContent = '';

        $FilmVariant[] = array('video_type_id' => 9);
        $FilmVariant[] = array('video_type_id' => 2);
        $FilmVariant[] = array('video_type_id' => 11);
        $FilmVariant[] = array('video_type_id' => 12);

        $panelLinksCnt = array();
        foreach ($FilmVariant as $variant)
            if (!empty($variant['FilmFile'])) :
                $total = Set::extract('/FilmFile/size', $variant);
                $total = array_sum($total);

                if ($lang != _ENG_)
                    if (!isset($variant['Track']['Language']['title']))
                        $variant['Track']['Language']['title'] = $language;
                if (!isset($variant['Track']['Translation']['title']))
                    $variant['Track']['Translation']['title'] = $translation;
                if (!isset($variant['Track']['audio_info']))
                    $variant['Track']['audio_info'] = $audio_info;
                ?>
                <h4><?php __('Quality'); ?> <?= $variant['VideoType']['title'] ?><br />
                    <?php
                    if ($lang != _ENG_) :
                        ?>
                        Перевод: <?= $variant['Track']['Language']['title'] . ', ' . $variant['Track']['Translation']['title'] ?><br/>
                        <?php
                    endif;
            endif;
        endif;
        ?>
</li>
<script language="javascript">
    $(document).ready(function() {
        elem =$("#poster");
        setTimeout(function() { 
            //$('body').scrollTop(elem[0].y); 
                 window.scrollTo(0, elem[0].y);
        }, 1000);});      
</script>

