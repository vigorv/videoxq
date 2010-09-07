<div id="alphFaces">
    <ul>
    <?php
    foreach ($alphabet as $letter)
    {
        echo '<li><a href="/people/letter/' . $letter . '">' . $letter . '</a></li>';
        if (strtolower($letter) == 'z')
            echo '</ul><ul>';
    }
    ?>
    </ul>
</div>

<?php
extract($person);
?>
<div id="face">
    <h2><?= $Person['name'] ?><br /><em><?= $Person['name_en'] ?></em></h2>
<?php
foreach ($PersonPicture as $picture)
{
    echo $html->image(Configure::read('Catalog.imgPath') . $picture['file_name'], array('style' => 'margin: 5px'));
}
?>
    <p><?php echo $Person['description'];?></p>

<?php
$pro = '';

foreach ($films as $film)
{
    extract($film);
    if ($pro != $Profession['title'] && $pro != '')
    {
        echo '</ol>';
    }

    if ($pro != $Profession['title'])
    {
        echo '<h3>' . $Profession['title'] . '</h3><ol>' . "\n";
        $pro = $Profession['title'];
    }

    echo '<li><a href="/media/view/' . $Film['id'] . '">' . $Film['title'] . '</a> (' . $Film['year'] . ')</li>' . "\n";
}
echo '</ol>';
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