<?php
$zone = false;
$zones = Configure::read('Catalog.allowedIPs');
$zone = checkAllowedMasks($zones,$_SERVER['REMOTE_ADDR'], 1);
 if($zone)$imgPath = Configure::read('Catalog.imgPath');
 else $imgPath = Configure::read('Catalog.imgPathInet');

$pass = $this->passedArgs;
$pass['action'] = str_replace(Configure::read('Routing.admin') . '_', '', $this->action); // temp
?>
<div class="movies">
<?php
if (!empty($films))
	foreach ($films as $row) {
    	extract($row);
?>
    <div class="moviePreviewWrapper">
<?php

$iType="";
if (!empty($FilmVariant))
{
$FilmVariant=set::extract($FilmVariant,'{n}.VideoType.title');
foreach ($FilmVariant as $VideoType)
{
	if($VideoType<>'DVDrip')
	{
	@list($name,$title)=@explode('-',$VideoType);
?>
		<div class="hd<?=$iType?>"><img src="/img/vusic/<?=$name?>.gif" alt="<?=$title?>" title="<?=$title?>"><b><?=$title?></b></div>
<?php $iType++;}}}?>
        <div class="poster">
        <a href="/media/view/<?= $Film['id']?>">
            <?
            if (!empty($FilmPicture[0]['file_name']))
                echo $html->image($imgPath . $FilmPicture[array_rand($FilmPicture)]['file_name'], array('width' => 80));
            else
                echo $html->image('/img/vusic/noposter.jpg', array('width' => 80)); ?>
         </a>
            <div class="ratings rated_<?= round($MediaRating['rating']) ?>"><div></div></div>
            <?php
            if ($Film['imdb_rating'] != 0)
                echo '<span class="imdb">IMDb: ' . $Film['imdb_rating'] . '</span>';
            ?>
        </div>
        <p class="text">
            <?php
$directors = array();
$actors = array();
foreach ($Person as $data)
{
    if ($data['FilmsPerson']['profession_id'] == 1 && count($directors) < 4)
    {
    	if ($lang == _ENG_)
    	{
    		if (!empty($data['name' . $langFix]))
        		$directors[] = $data['name' . $langFix];
    	}
        else
        	$directors[] = $data['name' . $langFix] ? $data['name' . $langFix] : $data['name_en'];
    }
    if (($data['FilmsPerson']['profession_id'] == 3
        || $data['FilmsPerson']['profession_id'] == 4)
        && count($actors) < 4)
    {
    	if ($lang == _ENG_)
    	{
    		if (!empty($data['name' . $langFix]))
        		$actors[] = $data['name' . $langFix];
    	}
    	else
        	$actors[] = $data['name' . $langFix] ? $data['name' . $langFix] : $data['name_en'];
    }
}
if (!empty($directors))
	echo implode(', ', $directors) . '.';
?> <?php echo $Film['year'] ?>
            <span>«<a href="/media/view/<?= $Film['id']?>"><?= $Film['title' . $langFix] ?></a>»</span>
             <?php
shuffle($actors);
$actors=array_slice($actors,0,3);
echo implode(', ', $actors);

?>
            <em><?php
            	if ($lang == _ENG_)
            		echo $app->implodeWithParams(' / ', $Genre, 'title_imdb', ' ', 2);
            	else
            		echo $app->implodeWithParams(' / ', $Genre, 'title', ' ', 2);
            ?></em>
        </p>
    </div>
<?php
}
?>
</div>

