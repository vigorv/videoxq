<?php
if ($lang == _ENG_)
    $gen_fix = '_imdb';
else
    $gen_fix = '';
?>
<?php
if (empty($films)) :
    ?>
    <div style="padding:5px">
        В мобильной версии сайта данный фильм не найден, попробуйте поискать на <a href="/mobile/ver?id=1" >полной версии</a> сайта
        <a href="http://videoxq.com/mobile/ver?id=1" >http://videoxq.com</a>
    </div>

    <?
else:
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
                <a class="href_li" href="/mobile/films/<?= $Film['id'] ?>#home" onclick="return myPager.nextScreen(this);">

                    <div class="poster">
                        <?= $poster; ?>
                    </div>
                    <p class="info_text">
                        <?= $Film['year']; ?><br/>
                        <span>«<?= $Film['title' . $langFix] ?>»</span>
                        <br/>
                        <em>
                            <?php
                            if (isset($Genre))
                                echo $app->implodeWithParams(' / ', $Genre, 'title' . $gen_fix, ' ', 2);
                            ?>
                        </em>
                    </p>        
                </a>
                <div class="clearfix"></div>
            </li>
        <?php endforeach; ?>
<? endif; ?>
<?php if (!isset($ajax_draw)): ?>
    <?php if ($count > 10): ?>
        <div id="more">    
        </div>
    <?php endif; ?>

    <?php if ($count > 10): ?>
        <div id="TenMoreError"></div>
        <li id="TenMore" class="barA" style="min-height:0;text-align: center">
            <a class="footer_ten" href="#" onClick="myPager.tenMore();return false;" ><?= __('Show more videos', true); ?></a>
        </li>
    <?php endif; ?>
<? endif; ?>
<?php
$max = $page * 10;
if ($max >= $count) :
    ?>
    <script language="javascript">
        $('#TenMore').hide();
    </script>
<? endif; ?>
