<?php if (!isset($ajax_draw)): ?>

    <li style="text-align:center;">
        <div  style="text-align: center; vertical-align: bottom; margin:auto;padding:10% 5px;">
            <form action="/mobile/search" id="searchform" method="get" >
                <input  type="text" style="clear:left;" placeholder="<?= __('Search film by title', true); ?>…" tabindex="1" id="search-input" onblur="window.scrollTo(0,1)" name="s"/>
                <br/>
                <br/>
                <input  style="font-size:26px" type="submit" tabindex="2" id="search-submit-hidden" name="submit" value="<?= __('Search', true); ?>"/>
            </form>
        </div>
    </li>
<? else: ?>
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
        if (!empty($FilmPicture['file_name']))
            $poster = $html->image($imgPath . $FilmPicture['file_name'], array('width' => 40, 'height' => 55));
        else
            $poster = $html->image('/img/vusic/noposter.jpg', array('width' => 40, 'height' => 55));

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
            <a class="href_li" href="/mobile/films/<?= $Film['id'] ?>" onClick="return myPager.nextScreen(this); ">
                <div class="poster">
                    <?= $poster; ?>
                </div>
                <p class="info_text">
                    <?
                    if (!empty($directors))
                        echo implode(', ', $directors) . '.';
                    echo $Film['year'];
                    ?><br/>
                    <span>«<?= $Film['title' . $langFix] ?>»</span>
                    <?php
                    shuffle($actors);
                    $actors = array_slice($actors, 0, 3);
                    echo implode(', ', $actors);
                    ?><br/>
                    <em>
                        <?php
                        if (!empty($Genre)) {
                            foreach ($Genre as $key => $genre)
                                if ($lang == _ENG_) {
                                    if ($key <> 0)
                                        echo '/ ';
                                    echo $genre['genres']['title_imdb'];
                                }
                                else {
                                    if ($key <> 0)
                                        echo '/ ';
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

<?php if (!isset($ajax_draw)): ?>
    <?php if ($count > 10): ?>
        <div id="more">    
        </div>
    <?php endif; ?>
    <a id="up_button" style="color:black;text-decoration: none;"  href="" onclick="setTimeout(function() { window.scrollTo(0, 1); }, 100); return false">
        <div class="barA" style="background-color: #CCC">  
            <?= __('Scroll up', true) ?> &uarr;
        </div>
    </a>
    <?php if ($count > 10): ?>
        <div id="TenMoreError">
            
        </div>
        <li id="TenMore" class="barA" style="min-height:0; text-align: center">
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