<?php
if (empty($films))
    echo '<li>' . __('No results for your search', true) . ' :(</li>';
else
    foreach ($films as $row):
        extract($row);
        if (!empty($FilmPicture[0]['file_name']))
            $poster = $html->image($imgPath . $FilmPicture[array_rand($FilmPicture)]['file_name'], array('width' => 80));
        else
            $poster=$html->image('/img/vusic/noposter.jpg', array('width' => 80));
?>
        <li>
            <div class="poster">
                <a href="/mobile/view/<?= $Film['id'] ?>"><?= $poster; ?></a>
                <div class="ratings rated_<?= round($MediaRating['rating']) ?>"></div>
        <?php
        if ($Film['imdb_rating'] != 0)
            echo '<span class="imdb">IMDb: ' . $Film['imdb_rating'] . '</span>';
        ?>
    </div>
    <p class="text">
        <?php
        $directors = array();
        $actors = array();
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
        <span>«<a href="/mobile/view/<?= $Film['id'] ?>"><?= $Film['title' . $langFix] ?></a>»</span>
        <?php
        shuffle($actors);
        $actors = array_slice($actors, 0, 3);
        echo implode(', ', $actors);
        ?>
        <em>
            <?php
            if ($lang == _ENG_)
                echo $app->implodeWithParams(' / ', $Genre, 'title_imdb', ' ', 2);
            else
                echo $app->implodeWithParams(' / ', $Genre, 'title', ' ', 2);
            ?>
        </em>
    </p>
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