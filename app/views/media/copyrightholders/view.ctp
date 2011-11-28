<div id="alphFaces">
    <ul>

    <?php

    //ksort($copyrightholders);
    $rus_trigger=0;
    foreach ($alphabet as $letter)
    {

    	if ($lang == _ENG_)
    	{
    		if ((strtolower($letter) < 'a') || (strtolower($letter) > 'z')) continue;
    	}
/* 2011-09-22
 * добавил для корректного разделения по строкам англ и русских букв при
 * неполном англ. алфавите, раньше переход был только на букве "z"
 *
 *
 */
        if (preg_match("/[^a-z]+/ui",strtolower($letter)) && !$rus_trigger){
            $rus_trigger=1;
            echo '</ul><ul>';
        }
        echo '<li><a href="/copyrightholders/letter/' . $letter . '">' . $letter . '</a></li>';



    }
    ?>
    </ul>
</div>



<?php
extract($copyrightholder);
?>
<div id="face">
    <h2><?= $Copyrightholder['name'] ?><br /><em><?= $Copyrightholder['name_en'] ?></em></h2>

<?php
foreach ($CopyrightholdersPicture as $picture)
{
    echo $html->image('/img/' . $picture['file_name'], array('style' => 'margin: 5px'));
}
?>
    <p><?php echo $Copyrightholder['description'];?></p>

<?php
$pro = '';
if (!empty($films))
{
echo '<ol>';
foreach ($films as $film)
{
    extract($film);
/*
    if ($pro != $Profession['title'] && $pro != '')
    {
        echo '</ol>';
    }

    if ($pro != $Profession['title'])
    {
        echo '<h3>' . $Profession['title'] . '</h3><ol>' . "\n";
        $pro = $Profession['title'];
    }
*/
    echo '<li><a href="/media/view/' . $Film['id'] . '">' . $Film['title'] . '</a> (' . $Film['year'] . ')</li>' . "\n";
}
echo '</ol>';
}
?>
</div>





<?php
/*

foreach ($PersonPicture as $picture)
{
    echo $html->image(Configure::read('Catalog.imgPath') . $picture['file_name'], array('style' => 'margin: 5px', 'width' => 60, 'height' => 72));
}
echo '<br />';
echo $Person['name'] . '<br />' . $Person['name_en'] . '<br />';
echo $app->implodeWithParams(', ', $Profession) . '<br />';
echo '<h2>Информация</h2>';
echo $Person['description'] . '<br />';
echo '<h2>Фильмы</h2>';

foreach ($Film as $filmPerson)
{
    echo $html->link($filmPerson['title'], '/media/view/' . $filmPerson['id'], array('target' => '_parent')) . '<br />';
}

*/
?>