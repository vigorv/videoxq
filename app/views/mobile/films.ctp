<?php


if (empty($films))
    echo '<li>' . __('No results for your search', true) . ' :(</li>';
else
    if (!is_array($films)) {
            echo '<li>'.__('You do it to fast',true).'</li>';
    } else
    foreach ($films as $row):
        extract($row);
    
        if (!empty($FilmPicture['file_name']))
            $poster = $html->image($imgPath . $FilmPicture['file_name'], array('width' => 80));
        else
            $poster=$html->image('/img/vusic/noposter.jpg', array('width' => 80));
        ?>
    <li> 
        <a class="href_li" href="/mobile/films/<?= $Film['id'] ?>" onclick="nextScreen(this); return false;">
           
            <div class="poster">
                <?= $poster; ?>
                <? if(isset($MediaRating)):?>
                <div class="ratings rated_<?= round($MediaRating['rating']) ?>"></div>
                <?endif;?>
                <? if ($Film['imdb_rating'] != 0):?>
                    <span class="imdb">IMDb: <?=$Film['imdb_rating'] ?></span>
                 <?endif;?>
            </div>
            <p class="text">
                <?php
                $directors = array();
                $actors = array();
                if (!empty($Person))
                foreach ($Person as $data) {
                    if ($data['FilmsPerson']['profession_id'] == 1 && count($directors) < 4) {
                        if ($lang == _ENG_) {
                            if (!empty($data['name' . $langFix]))
                                $directors[] = $data['name' . $langFix];
                        } else
                            $directors[] = $data['name' . $langFix] ? $data['name' . $langFix] : $data['name_en'];
                    }
                    if (($data['FilmsPerson']['profession_id'] == 3 || $data['FilmsPerson']['profession_id'] == 4)
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
                $Film['year'];
                ?>
                <span>«<?= $Film['title' . $langFix] ?>»</span>
                <?php
                shuffle($actors);
                $actors = array_slice($actors, 0, 3);
                echo implode(', ', $actors);
                ?>
                
                <em>
                    <?php
                    if (isset($Genre))   {
                        if ($lang == _ENG_)
                            echo $app->implodeWithParams(' / ', $Genre, 'title_imdb', ' ', 2);
                        else
                            echo $app->implodeWithParams(' / ', $Genre, 'title', ' ',2);
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