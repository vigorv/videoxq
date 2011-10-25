<? if (isset($ajax_draw)): ?>
    <div style="float:right; font-weight:900;margin:0;padding:0; font-size:25px">
        <a id="up_button" style="color:black;text-decoration: none;"  href="" onclick=" myScroll.refresh();setTimeout(function() { window.scrollTo(0, 1); }, 100);myScroll.scrollTo(0,0); return false">&uarr;</a>&nbsp;
    </div>    
    <div class="barA" style="background-color: #CCC">

        <?php echo ($page - 1) * 10; ?>
    </div>
<? endif; ?>
<?php
if (empty($films))
    echo '<div class="barA">' . __('No results for your search', true) . ' :(</div>';
else
if (!is_array($films)) {
    echo '<div class="barA">' . __('You do it to fast', true) . '</div>';
} else
    foreach ($films as $row):
        extract($row);

        if (!empty($FilmPicture[0]['file_name']))
            $poster = $html->image($imgPath . $FilmPicture[0]['file_name'], array('width' => 40, 'height' => 55));
        else
            $poster = $html->image('/img/vusic/noposter.jpg', array('width' => 40, 'height' => 55));
        ?>
        <li> 
            <a class="href_li" href="/mobile/films/<?= $Film['id'] ?>" onclick="return myPager.nextScreen(this);">

                <div class="poster">
                    <?= $poster; ?>
                    <? if (isset($MediaRating)): ?>
                        <div class="ratings rated_<?= round($MediaRating['rating']) ?>"></div>
                    <? endif; ?>
                    <? if ($Film['imdb_rating'] != 0): ?>
                        <span class="imdb">IMDb: <?= $Film['imdb_rating'] ?></span>
                    <? endif; ?>
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
                    echo $Film['year'];
                    ?><br/>
                    <span>«<?= $Film['title' . $langFix] ?>»</span>
                    <?php
                    shuffle($actors);
                    $actors = array_slice($actors, 0, 3);
                    echo implode(', ', $actors);
                    ?>
                    <br/>
                    <em>
                        <?php
                        if (isset($Genre))
                            if ($lang == _ENG_)
                                echo $app->implodeWithParams(' / ', $Genre, 'title_imdb', ' ', 2);
                            else
                                echo $app->implodeWithParams(' / ', $Genre, 'title', ' ', 2);
                        ?>
                    </em>
                </p>        
            </a>
            <div class="clearfix"></div>
        </li>



    <?php endforeach; ?>


<?php if (!isset($ajax_draw)): ?>
    <?php if ($count > 10): ?>
        <div id="more">    
        </div>
    <?php endif; ?>
    <div style="float:right; font-weight:900;margin:0;padding:0; font-size:25px">
        <a id="up_button" style="color:black;text-decoration: none;"  href="" onclick=" myScroll.refresh();setTimeout(function() { window.scrollTo(0, 1); }, 100);myScroll.scrollTo(0,0); return false">&uarr;</a>&nbsp;
    </div>
    <div class="barA" style="background-color: #CCC">  
        <?= __('Total', true) ?>: <?= $count; ?> 
    </div>

    <?php if ($count > 10): ?>
        <div id="TenMoreError">
            
        </div>
        
        <li id="TenMore" class="barA" style="min-height:0;">
            <a class="footer_ten" href="#" onClick="myPager.tenMore();return false;" ><?= __('Show more videos', true); ?></a>
        </li>
    <?php endif; ?>
<? endif; ?>

<?php
$max = $page * 10;
if ($max >= $count) :
    ?>

    <script langauge="javascript">
        $('#TenMore').hide();
    </script>
<? endif; ?>
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