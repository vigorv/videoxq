<?php
if (empty($films))
    echo '<li>' . __('No results for your search', true) . ' :(</li>';
else
if (!is_array($films)) {
    echo '<li>' . __('You do it to fast', true) . '</li>';
} else
    foreach ($films as $row):
        extract($row);
        if (!empty($FilmPicture['file_name']))
            $poster = $html->image($imgPath . $FilmPicture['file_name'], array('width' => 80));
        else
            $poster=$html->image('/img/vusic/noposter.jpg', array('width' => 80));

        $directors = array();
        $actors = array();
        if (!empty($Person))
            foreach ($Person as $data) {
                if ($data['FilmsPerson']['profession_id'] == 1 && count($directors) < 4) {
                    if ($lang == _ENG_) {
                        if (!empty($data['Person']['name' . $langFix]))
                            $directors[] = $data['Person']['name' . $langFix];
                    } else
                        $directors[] = $data['Person']['name' . $langFix] ? $data['Person']['name' . $langFix] : $data['Person']['name_en'];
                }
                if (($data['FilmsPerson']['profession_id'] == 3 || $data['FilmsPerson']['profession_id'] == 4)
                        && count($actors) < 4) {
                    if ($lang == _ENG_) {
                        if (!empty($data['Person']['name' . $langFix]))
                            $actors[] = $data['Person']['name' . $langFix];
                    }
                    else
                        $actors[] = $data['Person']['name' . $langFix] ? $data['Person']['name' . $langFix] : $data['Person']['name_en'];
                }
            }
        ?>
        <li> 
            <a class="href_li" href="/mobile/films/<?= $Film['id'] ?>" onClick="myPager.nextScreen(this); return false;">

                <div class="poster">
                    <?= $poster; ?>
                    <? if (isset($MediaRating)): ?>
                        <div class="ratings rated_<?= round($MediaRating['rating']) ?>"></div>
                    <? endif; ?>
                    <? if ($Film['imdb_rating'] != 0): ?>
                        <span class="imdb">IMDb: <?= $Film['imdb_rating'] ?></span>
                    <? endif; ?>
                </div>
                <p class="info_text">
                    <?
                    if (!empty($directors))
                        echo implode(', ', $directors) . '.';
                    echo $Film['year'];
                    ?>
                    
                    <span>«<?= $Film['title' . $langFix] ?>»</span>
                    <br/>
                    <?php
                    shuffle($actors);
                    $actors = array_slice($actors, 0, 3);
                    echo implode(', ', $actors);
                    ?>
<br/>
                    <em>
                        <?php
                        if (!empty($Genre)) {
                            foreach ($Genre as $key => $genre)
                                if ($lang == _ENG_) {
                                    if ($key<>0) echo '/ ';
                                    echo $genre['genres']['title_imdb'];
                                }
                                else{
                                    if ($key<>0) echo '/ ';
                                    echo $genre['genres']['title'];
                                }
                                    
                            //echo $app->implodeWithParams(' / ', $Genre, "title_imdb", ' ', 2);
                            //echo $app->implodeWithParams(' / ', $Genre, "genres", ' ',2);
                        }
                        ?>
                    </em>
                </p>        
            </a>
            <div class="clearfix"></div>
        </li>



    <?php endforeach; ?>

<div class="bar"  style="text-align: center; width:auto">
    <?= $paginator->prev(__('Prev', true), array('class' => "button"), null, null); ?>
    <?= $paginator->next(__('Next', true), array('class' => "button"), null, null); ?>
</div>
<?
/*
  <li>
  <a href="#"><?=__('Load More 10 results',true);?></a>
  </li>
 *
 *
 */
?>
<? /*
  <div class="toolbar">
  <h4><?= $paginator->counter(); ?></h4>
  </div>
 *
 *
 */ ?>