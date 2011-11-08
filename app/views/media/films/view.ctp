<?php
function msgBox($txt)
{
	return '
		<div class="attention">' . $txt . '</div>
	';
}

ob_start();

$isVip = (!empty($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups));

$HQTypes = array(
	9	=>	'HDrip'
);
$SQTypes = array(
	2	=>	'DVDrip',
	3	=>	'DVDScr',
	4	=>	'SATrip',
	5	=>	'Telecine',
	6	=>	'VHSrip',
	10	=>	'TVrip',
);

$mobTypes = array(
	13	=>	'270p'
);

$webTypes = array(
	12	=>	'url'
);

if (($lang == _ENG_) && (empty($imdb_website)))
{
	echo '<h3 style="margin-left:45px;">' . __('Sorry, we do not have a detailed description of the movie', true) . ' &laquo;' . $film['Film']['title_en'] . '&raquo;</h3><br /><br /><br />';
}
else
{
/*
echo '
<script type="text/javascript">
$.get("http://flux.itd/media/getbanner/header", function(html){ document.write(html);});
</script>
';
*/

        $zone = false;
        $zones = Configure::read('Catalog.allowedIPs');
        $zone = checkAllowedMasks($zones,$_SERVER['REMOTE_ADDR'], 1);
        if($zone)$imgPath = Configure::read('Catalog.imgPath');
        else $imgPath = Configure::read('Catalog.imgPathInet');

/*
//TEST
echo'<pre>';
echo $_SERVER['QUERY_STRING'];
echo'</pre>';
/*
echo'<pre>';
var_dump($catalogVariants[0]["Track"]);
echo'</pre>';
echo'<pre>';
var_dump($catalogVariants[0]["FilmFile"]);
echo'</pre>';
//END OF TEST
//*/
	$javascript->link('jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.pack', false);
    $javascript->link('jquery.pngFix', false);
    $html->css('fancybox-1.3.4/jquery.fancybox-1.3.4', null, array(), false);

    $posterTitle = '';
    extract($film);
    $posters = Set::extract('/FilmPicture[type=poster]/.', $film);
    $bigposters = Set::extract('/FilmPicture[type=bigposter]/.', $film);

?>

<div class="movie">
<?php
//    <div class="viewright">
//  echo $this->element('blocks', array('blockArray' => $blockContent['right']));
//    </div>
?>
    <?php

    if ($lang == _ENG_)
    {
		$imdbTitle = $parser->getMovieTitle($imdb_website);

		$imdbActors = array();
		$a_actors = $parser->getMovieActors($imdb_website, $name_and_id=True);
		for ($i = 0; $i < count($a_actors[1]); $i++)
		{
			//$imdbActors[] = '<a href="' . $a_actors[1][$i] . '">' . $a_actors[2][$i] . '</a>';
			$imdbActors[] = $a_actors[2][$i];
		}
		$imdbActors = implode(', ', $imdbActors);

		$imdbCountries = array();
		$imdbCountry = $parser->getMovieCountry($imdb_website);
//echo '<pre>';
//var_dump($imdbCountry);
//echo '</pre>';
//exit;
		for ($i = 0; $i < count($imdbCountry); $i++)
		{
			$imdbCountries[] = $imdbCountry[$i][1];
		}
		$imdbCountries = implode(', ', $imdbCountries);

		$imdbDirectedBy = array();
		$imdbDirectors = $parser->getMovieDirectedBy($imdb_website);
		for ($i = 0; $i < count($imdbDirectors); $i++)
		{
			$imdbDirectedBy[] = $imdbDirectors[$i][1];
		}
		$imdbDirectedBy = implode(', ', $imdbDirectedBy);

		$imdbWrittenBy = array();
		$imdbWriters = $parser->getMovieWriters($imdb_website);
		for ($i = 0; $i < count($imdbWriters); $i++)
		{
			$imdbWrittenBy[] = $imdbWriters[$i][1];
		}
		$imdbWrittenBy = implode(', ', $imdbWrittenBy);

		$imdbGenres = array();
		$imdbGenre = $parser->getMovieGenres($imdb_website);
		for ($i=0; $i < count($imdbGenre); $i++)
		{
			$imdbGenres[] = $imdbGenre[$i][1];
		}
		$imdbGenres = implode(', ', $imdbGenres);

		$imdbRating = $parser->getMovieStars($imdb_website);
    }
?>
<div style="float: right; clear: both;">

    <table border="0" align="right"><tr><td>
        <div class="userRate">
    <?php if (!empty($authUser['userid']) && $votingAllowed): ?>
            <div class="ratings" id="voting">
                <!--[if lte IE 6]><a class="ievote" href="#"><table cellpadding="0" cellspacing="0"><tbody><tr><td><![endif]-->
                <ul class="vote">
                <?php
                for ($i = 5; $i > 0; $i--)
                {
                    if (round($MediaRating['rating']) == $i)
                        $r = ' rated';
                    else
                        $r = '';
                    echo '<li class="rating_'.$i.''.$r.'">
                          <a href="/ratings/vote/'.$Film['id'].'/'.$i.'" title="'.$i.' stars" onclick="vote('.$Film['id'].', '.$i.', this); return false;">'.$i.'</a>
                          </li>';
                }
                ?>
                </ul>
                <!--[if lte IE 6]></td></tr></tbody></table></a><![endif]-->
            </div>
    <?php else: ?>
                <div class="ratings rated_<?= round($MediaRating['rating']) ?>"><div></div></div>
    <?php endif; ?>
    <?php
            if ($MediaRating['num_votes'] > 0)
                echo __('Voices', true) . ': ' .$MediaRating['num_votes'];
            else
                echo __('For this film did not vote.', true);
    ?>
        </div>
    </td></tr>
        <tr>
            <td>
                <div id="posters" style="display: none;">
                <?php
                if (!empty($authUser['userid']))
                {
                    $posters = am($bigposters, $posters);
                    foreach ($posters as $poster){
                        echo $html->link($imgPath . $poster['file_name'], null, array('rel' => 'posters')) . "\n";
                        }
                }

                ?>
                </div>
                <?php
                    $imgUrl = $imgPath . $posters[array_rand($posters)]['file_name'];
                    $img = $html->image($imgUrl, array('class' => 'poster', 'title' => $posterTitle));
                    echo  $html->link($img, $imgUrl, array('rel' => 'posters', 'title' => $posterTitle), false, false) . "\n";
                ?>
            </td>
        </tr>
        <tr>
            <td >
<?php

    //для зарегеных юзеров функционал "избранное"!
    //добавим времнное условие для скрытия кнопочек на внешнем сайте
    if (!empty($authUser['userid']) && !stristr(Configure::read('App.siteUrl'),'videoxq.com') ){
        if (!empty($exist_film_in_favorites) && $exist_film_in_favorites){
            //если фильм уже в избранном, то выведем сооствествующий значок
            //с подписью на память :)
            echo  '<img src="/img/icons/favorites-icon_32x32.png" title="Фильм находится в избранном"/> Фильм находится в <a style="" href="/maina/favorites">избранном</a>';
            echo '<br/>';
            //Добаим значок удалить из избранного
            echo  '<a style="" href="/media/removefromfavorites/'.$Film['id'].'"><img src="/img/icons/remove-from-favorites-icon_32x32.png" title="Удалить из избранного"/>Удалить из избранного</a>';
        }
        else{
            //иначе добавляем кнопку в избранное
            echo  '<a style="" href="/media/addtofavorites/'.$Film['id'].'"><img src="/img/icons/add-to-favorites-icon_32x32.png" title="Добавить в избранное" id="icon_favorite"/><p style="padding-left:15px;padding-top:10px;">Добавить в избранное</p></a>';
        }

/*
        if ( isset($ajax) ) {
            echo $ajax->link('Добавить в избранное', '/media/addtofavorites/' . $Film['id'] ,
                array(
                    'update'=>'updated',
                    'loading' =>"Element.show('loading')",
                    'complete' => "Element.hide('loading')"
                    ));
        } else {
          echo $html->link('Добавить в избранное', '/media/addtofavorites/' . $Film['id']);
        }

*/
    }


?>
            </td>
        </tr>


<!--
        <tr height="100">
            <td align="center">
		<a rel="nohref" nohref="nohref" title="Перейти на рекомендуемый для промотра сайт"><img height="108" src="/img/about/play2.jpg"></a>
            </td>
        </tr>


        <tr>
            <td align="center">
<?php
	if (!$Film['is_license'])
	{
    	echo '<font size="1" color="grey">на правах рекламы</font>';
	}
?>
            </td>
        </tr>
-->

	</table>


</div>
    <h2>«<a rel="nohref" nohref="nohref"><?php
    	if ($lang == _ENG_)
    		echo $imdbTitle;
    	else
    		echo $Film['title'];
    ?></a>»</h2>
    <h3><?php
    	if ($lang != _ENG_) echo $Film['title_en'];
    ?><br>
    <?php
    	if ($lang == _ENG_)
    		echo $imdbCountries;
    	else
    		echo $app->implodeWithParams(', ', $Country);
    ?>
	<?php echo $Film['year']; ?>
	<?php
		if ($lang == _ENG_)
		{
			if (!empty($imdbRating))
			{
		?>
			<strong>IMDb: <?php echo $imdbRating; ?> </strong>
		<?php
			}
		}
		else
		{
			if ($Film['imdb_rating'] != 0)
			{
		?>
			<strong>IMDb: <?php echo $Film['imdb_rating']; ?> </strong>
		<?php
			}
		}
	?>
	</h3>
            <?php
            //pr($persons);
            $directors = array();
            $story     = array();
            $actors    = array();
            foreach ($persons as $personRow)
            {
                extract($personRow);
                /*if (!empty($Person['name']) && !empty($Person['name_en']))
                    $name = $Person['name'] . ' (' . $Person['name_en'] . ')';
                else*/
                if (!empty($Person['name']))
                    $name = $Person['name'];
                else
                    $name = $Person['name_en'];

                $link = '<a href="' . '/people/view/' . $Person['id'] . '">' . $name . '</a>';
                if (isset($Profession[1]))
                    $directors[] = $link;
                if (isset($Profession[2])
                    || isset($Profession[22]))
                    $story[] = $link;
                if (isset($Profession[3])
                    || isset($Profession[4]))
                $actors[] = $link;
            }
/*
            if (empty($authUser['userid']))
            {
                $actors = array_slice($actors, 0, 2);
                $actors[] = '<a href="#" title="' . __('Available only to registered users', true) . '">' . __('more', true) . '...</a>';
            }
            else
*/
            {
                $actors = array_slice($actors, 0, 10);
                $actors[] = '<a href="#">' . __('more', true) . '...</a>';
            }

            ?>
    <?php if (!empty($directors)): ?>
    <h4><?php __('Directed by'); ?>:</h4>
    <p id="directors">
    <?php
    	if ($lang == _ENG_)
    	{
    		echo $imdbDirectedBy;
    	}
    	else
    	{
    		echo implode(', ', $directors);
    	}
    ?>
    </p>
    <?php endif; ?>
    <?php
    	if (!empty($story))
    	{
    ?>
    <h4><?php __('Writers'); ?>:</h4>
    <?php
	    	if ($lang == _ENG_)
	    	{
	    		echo $imdbWrittenBy;
	    	}
	    	else
	    	{
			    echo '<p id="story">' . implode(', ', $story) . '</p>';
	    	}
    	}
    ?>
    <?php
    	if (!empty($actors))
    	{
	?>
	    <h4><?php __('Actors'); ?>:</h4>
	<?php
    		if ($lang == _ENG_)
    		{
    			echo $imdbActors;
    		}
    		else
    		{
    ?>
    <p id="actors"><?php echo implode(', ', $actors);?></p>
    <?php
    		}
    	}
    ?>
    <?php
    	if (!empty($Genre))
    	{
    ?>
    <h4><?php __('Genre');?>:</h4>
	<?php
    		if ($lang == _ENG_)
    		{
    			echo $imdbGenres;
    		}
    		else
    		{
    ?>
    <p><?php echo $app->implodeWithParams(', ', $Genre) ?></p>
    <?php
    		}
    	}
    ?>
    <br>
<?php

function sortLL($a, $b)
{
	return strnatcmp($a['Film']['title'], $b['Film']['title']);
}

if ((!empty($looksLike)) && (count($looksLike) > 1))
{
	echo'<h4>' . __('Similar films', true) . ':</h4><ul><li>';
	$comma = ''; $likeCnt = 0; $more = '';
	usort($looksLike, "sortLL");
	foreach ($looksLike as $l)
	{
		if ($l['Film']['id'] <> $Film['id'])
		{
			$link = $comma . '<a href="/media/view/' . $l['Film']['id'] . '">' . $l['Film']['title' . $langFix] . '</a>';
			if ($likeCnt++ > 2)
				$more .= $link;
			else
				echo $link;
			$comma = ', ';
		}
	}
	if (!empty($more))
	{
		echo trim('
			<script type="text/javascript">
			<!--
				more = \'' . $more . '\';
			-->
			</script><span id="more"> <a href="#" onclick=\'s = document.getElementById("more"); s.innerHTML = more; return false;\'>...' . __('more', true) . '</a></span>
		');
	}
	echo'</li></ul>';
}

if (!empty($similars))
{
	echo'<h4>' . __('Similar to the opinion of experts', true) . ':</h4><ul><li>';
	$comma = ''; $likeCnt = 0; $more = '';
	foreach ($similars as $l)
	{
		if ($l['Film']['id'] <> $Film['id'])
		{
			$link = $comma . '<a href="/media/view/' . $l['Film']['id'] . '">' . $l['Film']['title' . $langFix] . '</a>';
			if ($likeCnt++ > 2)
				$more .= $link;
			else
				echo $link;
			$comma = ', ';
		}
	}
	if (!empty($more))
	{
		echo trim('
			<script type="text/javascript">
			<!--
				more = \'' . $more . '\';
			-->
			</script><span id="moresimilar"> <a href="#" onclick=\'s = document.getElementById("moresimilar"); s.innerHTML = more; return false;\'>...' . __('more', true) . '</a></span>
		');
	}
	echo'</li></ul>';
}
?>
    <p><?php
    	if ($lang != _ENG_)
    		echo $Film['description'];
    	else
    	{
    		echo $parser->getMovieStory($imdb_website);
    	}
    ?></p>
    <br>
<?php
	echo $BlockBanner->getBanner('view');

	$yandex = $Film; // ДЛЯ ВЫВОДА ПОИСКА ПО ЯНДЕКСУ
	$linksContent = '';
	$faqLink = ' &nbsp;<span style="font-size:25px"><a alt="' . __('How to download?', true) . '" title="' . __('How to download?', true) . '" href="/pages/faq#download">&nbsp;?&nbsp;</a></span>';
	$yandexLink = '<h3 style="margin-top:12px;"><a target="_blank" href="/media/lite/' . $Film['id'] . '" title="' . __('Download Movie', true) . '">"' . $Film['title' . $langFix] . '" ' . __('download', true) . ' &raquo;</a>' . $faqLink . '</h3>';

/*
//РОССИЯ СТК
$isWS = true;
$allowDownload = $isWS;
$geoIsGood = true;
//*/

/*
//РОССИЯ ВНЕШНИЕ
$isWS = false;
$allowDownload = $isWS;
$geoIsGood = true;
//*/

if ($isWS)
{
	$geoIsGood = true;
}

if (($geoIsGood) && ($Film['is_license']) && ($authUser['userid']))
{
	$isWS = true;
}
/*
//ЗАРУБЕЖНЫЕ
$isWS = false;
$allowDownload = $isWS;
$geoIsGood = false;
//*/

//$isWS = false;
if ($isWS)
	{
/*
//БОЛЬШЕ НА НСК НЕ ОТПРАВЛЯЕМ
		$yandexLink = '<h3 style="margin-top:12px;"><a href="http://nsk54.com/media/view/' . $Film['id'] . '" title="' . __('Download Movie', true) . '">"' . $Film['title' . $langFix] . '" ' . __('download', true) . ' &raquo;</a>' . $faqLink . '</h3>';
		$allowDownload = false; //ВСЕ РАВНО СКАЧАЮТ С НСКА
*/
	}

	$panels = array();

//if ($allowDownload)
if ($geoIsGood)
{
//pr($FilmVariant);
$language		= ''; //на случай неустановленной информации о трэке
$translation	= ''; //на случай неустановленной информации о трэке
$audio_info		= ''; //на случай неустановленной информации о трэке
$divxContent	= '';

$FilmVariant[] = array('video_type_id' => 9);
$FilmVariant[] = array('video_type_id' => 2);
$FilmVariant[] = array('video_type_id' => 13);
$FilmVariant[] = array('video_type_id' => 12);

//pr($FilmVariant);
$panelLinksCnt = array();  $hideVideo = '';
foreach ($FilmVariant as $variant)
{
//echo $variant['video_type_id'] . ' ';

	if (!empty($variant['FilmFile']))
	{

    $total = Set::extract('/FilmFile/size', $variant);
    $total = array_sum($total);

    $numFiles = 0;
    foreach ($variant['FilmFile'] as $file)
        if (in_array($file['id'], $basket))
            $numFiles++;
//pr($variant);
	if ($lang != _ENG_)
	{
		if (!isset($variant['Track']['Language']['title']))
			$variant['Track']['Language']['title'] = $language;
		if (!isset($variant['Track']['Translation']['title']))
			$variant['Track']['Translation']['title'] = $translation;
	}
	if (!isset($variant['Track']['audio_info']))
		$variant['Track']['audio_info'] = $audio_info;

	$mediaInfo = '<br />';

	$mediaInfo .= '<h4>' . __('Quality', true) . ' ' . $variant['VideoType']['title'] . '<br />';
	if ($lang != _ENG_)
	{
		$mediaInfo .= 'Перевод: ' . $variant['Track']['Language']['title'] . ', ' . $variant['Track']['Translation']['title'] . '<br>';
	}

	if (!empty($authUser['userid']) || $isWS)
	{
		$mediaInfo .= __('Video', true) . ': ' . $variant['resolution'] . '<br />';
		$variant['Track']['audio_info'] = str_replace('stereo', '2ch stereo', $variant['Track']['audio_info']);
		$variant['Track']['audio_info'] = str_replace('стерео', '2ch stereo', $variant['Track']['audio_info']);
		$mediaInfo .= __('Audio', true) . ': ' . $variant['Track']['audio_info'] . '<br />';

		$language		= $variant['Track']['Language']['title'];
		$translation	= $variant['Track']['Translation']['title'];
		$audio_info		= $variant['Track']['audio_info'];
		$mediaInfo .= __('Duration', true) . ': ' . $variant['duration'];

	}
	else
	{

		$mediaInfo .= '<a href="#" title="' . __('Available only to registered users', true) . '">' . __('more', true) . '...</a>';

	}

	$mediaInfo .= '</h4>';

		if (!empty($authUser['userid']))
		{
			if ($isVip || $isWS) //ДЛЯ ВИПОВ ВВОДИМ УПРАВЛЕНИЕ ВИДИМОСТЬЮ ПЛЕЕРА
			{
				if (empty($_COOKIE['playSwitch'])) //ИНИЦИАЛИЗИРУЕМ
				{
					setcookie('playSwitch', 'playoff', time() + 60*60*24*30, '/');
					$playSwitch = 'playoff';
				}
				else
				{
					$playSwitch = $_COOKIE['playSwitch'];
				}
			}
		}

		$divxContent = '<a name="divx"></a><div id="divxdiv" style="z-index:1;">';
		$divxHtml = '';
    	$lnk = Film::set_input_server($Film['dir']).'/' . $FilmVariant[0]['FilmFile'][0]['file_name'];
    	$lnkInfo = pathinfo(strtolower(basename($lnk)));
    	$resolution = preg_split('/[\D]{1,}/', trim($FilmVariant[0]['resolution']));

    if (!empty($authUser['userid']))
    {
			if ($isVip || $isWS) //ДЛЯ ВИПОВ ВВОДИМ УПРАВЛЕНИЕ ВИДИМОСТЬЮ ПЛЕЕРА
			{
    	$divxHtml = '
<object classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616" width="' . $resolution[0] . '"
height="' . $resolution[1] . '" codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab">
                <param name="wmode" value="opaque" />
<param name="autoPlay" value="false" />
<param id="srcparamid" name="src" value="' . $lnk . '" />
                <param name="previewImage" value="" />
<embed id="srcembedid" type="video/divx" src="' . $lnk . '"
				width="' . $resolution[0] . '" height="' . $resolution[1] . '"
				wmode="opaque"
				autoPlay="false" previewImage="" pluginspage="http://go.divx.com/plugin/download/">
</embed>
                </object>

		';
			}
	}

    	if (!empty($playSwitch) && ($playSwitch == 'playoff'))
    	{
    		$divxHtml = '';
    	}

    	if ((count($resolution) > 1) && !empty($lnk) && ($lnkInfo['extension'] == 'avi'))
			$divxContent .= $divxHtml;

		$linksContent .= '
    		<script type="text/javascript">
    		<!--
    			function getdivx(id) {
    				$("#divxdiv").load("/media/getdivx/" + id, {}, function(html){});
    				return false;
    			}
    		-->
			</script>
		';
    $divxContent .= '</div>';
//КНОПКА ВКЛ/ВЫКЛ ПРОИГРЫВАТЕЛЯ
    $playSwitchButton = '';
	if (!empty($authUser['userid']))
	{
		if ($isVip || $isWS) //ДЛЯ ВИПОВ ВВОДИМ УПРАВЛЕНИЕ ВИДИМОСТЬЮ ПЛЕЕРА
		{
			switch ($playSwitch)
			{
				case 'playoff':
					//$playSwitchButton = '<br /><a href="/playswitch.php?' . $Film['id'] . '-playon" style="padding: 2px 5px 2px 5px; border: 1px solid black; background: red; color: white;">' . __('Watch online', true) . ' "' . $Film["title"] . '"</a><br /><br />';
				break;
				case 'playon':
					//$playSwitchButton = '<br /><a href="/playswitch.php?' . $Film['id'] . '-playoff" style="padding: 2px 5px 2px 5px; border: 1px solid black; background: green; color: white;">' . __('Turn off online-player', true) . '</a><br /><br />';
				break;
			}
		}
	}
	$divxContent .= $playSwitchButton;


$linksContent .= '
	<script type="text/javascript">
	<!--
		function switchPlay(cur)
		{
			$("a img[rel=play]").css({display: \'\'});
			$("#file" + cur).css({display: \'none\'});
		}

		function filmClk(id)
		{
			$.get("/utils/film_clicks/"+id)
			return true;
		}

	-->
	</script>
';
	}

$linksContent .= '
	<script type="text/javascript">
	<!--
			function findLinks()
		{
			$("#panelcontent").html("<br /><p>' . __("Downloading...", true) . '</p>");
			$("#panelcontent").load("/media/findlinks/' . $Film['id'] . '");
			return false;
		}
	-->
	</script>
';

$panelContent = ''; $linksCnt = 0;
if ((!empty($variant['FilmFile'])) && (($isVip) || ($isWS)))
{

if (count($variant['FilmFile']) > 0)
{
	$msg = '';
	if ($Film['is_license'] && !($Film['just_online']))
	{
		$msg = msgBox('Данный фильм (рип) был сделан с лицензионного DVD диска по соглашению с правообладателем.');
	}
	if ($Film['is_public'] && !($Film['just_online']))
	{
		$msg = msgBox('Данный фильм находится в общественном достоянии. Скачивание и хранение фильма не преследуется по закону.');
	}

	$panelContent = $mediaInfo;

	if ($Film['just_online'])
	{
		$panelContent .= '
			<br /><h3>' . __('Files List', true) . '</h3>
			' . $msg . '
			<table class="fileList">
		';
	}
	else
	{
		$panelContent .= '
			<br /><h3>' . __('Files List', true) . '</h3>
			' . $msg . '
			<table class="fileList">
	    	<tr>
	        	<td class="action" style="padding-left:20px">
		';
	    if ($numFiles != count($variant['FilmFile']))
	    {
	        $img = __('AddToBasket', true);
	        $action = 'add';
	    }
	    else
	    {
	        $img = __('RemoveFromBasket', true);
	        $action = 'delete';
	    }
	    if ($authUser['userid'] > 0):
	        $panelContent .= $html->link($img, '/basket/' . $action . '/' . $variant['id'] . '/variant', array('onclick' => 'basket('.$variant['id'].', \'variant\', this);return false;','id' => 'variant_' . $variant['id'], 'alt' => __('Add to download list', true)), false, false);
	    endif;

	    $panelContent .= '</td>
	        <td class="size">' . $app->sizeFormat($total) . '</td>
	        <td class="title">' . __('All Files', true) . '</td>
	    	</tr>
	    ';
/*
//ДОБАВЛЯЕМ ССЫЛКУ НА ФАЙЛ .metalink
	    $panelContent .= '</td>
	        <td class="size">' . $app->sizeFormat($total) . '</td>
	        <td class="title"><a title="' . __("by download manager (Download Master, DownThemAll etc.)", true) . '" href="/media/meta/'. $Film['id']. '/' . $variant['id'] . '/' . $variant['video_type_id'] .'">' . __('Get It All', true) . '</a> <i>(' . __('available mirrors', true) . ')</i></td>
	    	</tr>
	    ';
*/
	}
}
    $playDisplay = 'none';  $linksCnt = 0;
	$msg = '';
	if ((count($variant['FilmFile']) >= 3) && !($Film['just_online']))
	{
		$msg = msgBox('Внимание! Вы можете скачивать не более 3(трех) файлов одновременно. Если вы пользуетесь менеджером закачек, пожалуйста, поставьте ограничение на скачивание не более, чем в 3(три) потока.');
    	$msg = '<tr><td colspan="4" style="padding-left:30px">' . $msg . '</td></tr>';
	}
	$fileCnt = 0;
    foreach ($variant['FilmFile'] as $file)
    {
    	if ($Film['just_online'])
    	{
	        //$href = __('Available only to registered users', true);
	    	$panelContent .= '
	    	<tr>
	        	<td class="action" style="padding-left:30px"></td>
	        	<td class="size"></td>
		        <td class="title">
			';
    	}
    	else
    	{
	        if (!in_array($file['id'], $basket))
	        {
	            $img = __('AddToBasket', true);
	            $action = 'add';
	        }
	        else
	        {
	            $img = __('RemoveFromBasket', true);
	            $action = 'delete';
	        }
	        $fileCnt++;
	    	$panelContent .= '
	    	<tr>
	        	<td class="action" style="padding-left:30px">
			';

	        if ($authUser['userid'] > 0):
				$panelContent .= $html->link($img,
	                               '/basket/'. $action .'/' . $file['id'] . '/file',
	                                 array('onclick' => 'basket('.$file['id'].', \'file\', this);return false;',
	                                       'id' => 'file_' . $variant['id'] . '_' . $file['id']),
	                                 false, false);
	        else:
	            $panelContent .= '<img width="20" height="20" title="' . __('Add to download list', true) . ' (' . __('Available only to registered users', true) . ')" alt="' . __('Add to download list', true) . ' (' . __('Available only to registered users', true) . ')" src="/img/vusic/add.gif" />';
	        endif;

	        $panelContent .= '
	        	</td>
	        	<td class="size">' . $app->sizeFormat($file['size']) . '</td>
		        <td class="title">
			';
	        $href = __('Available only to registered users', true);
    	}
		$play = '';
		//if ($isVip) //ДЛЯ ВИПОВ ВВОДИМ УПРАВЛЕНИЕ ВИДИМОСТЬЮ ПЛЕЕРА
//		if (!empty($authUser['userid']) || $isWS)
		{
        	$recUrl = Film::set_input_server($Film['dir']).'/' . $file['file_name'];

			$letter = strtolower(substr($Film['dir'],0,1));
			if(( $letter >= '0' and $letter <= '9')||$letter=='0')
				$letter='0-999';

			if ($geoIsGood)
				$port = ':83/';
			if ($isWS)
				$port = ':83/';
			$flowUrl = str_replace('/' . $letter . '/', $port . $letter . '/', $recUrl);

			if ($geoIsGood)
				$port = ':80/';
			if ($isWS)
				$port = ':80/';
			$recUrl = str_replace('/' . $letter . '/', $port . $letter . '/', $recUrl);

			if ($Film['just_online'])
			{
				$matches = array();
				preg_match('/_e([0-9]+)/', $recUrl, $matches);
//pr($matches);
				$episode = '';
				if (!empty($matches[1]))
				{
					$episode = '- ' . __('episode', true) . '&nbsp;' .  intval($matches[1]) . '&nbsp;';
				}

        		$href=__('Available online only', true) . '&nbsp;' . $episode;
			}
			else
			{
        		$href='<a class="nocontext" onclick="return filmClk(' . $Film['id'] . ');" href="' . $recUrl . '">' . basename($file['file_name']) . '</a>&nbsp;';
			}
        	//$share = Film::set_input_share($Film['dir']);
	    	$lnkInfo = pathinfo(strtolower(basename($file['file_name'])));
        	if (!empty($lnkInfo['extension']))
        	{
				switch ($lnkInfo['extension'])
				{
					case "mp4":
						$play = '<a rel="video" href="#video' . $file['id'] . '"><img src="/img/play.gif" width="19" alt="" title="' . __('Watch online', true) . '" id="file' . $file['id'] . '" /></a>';
						$hideVideo .= '
							 <div id="video' . $file['id'] . '"><a style="width:640px; height:480px; display:block" id="ipad' . $file['id'] . '" onclick="return addVideo' . $variant['id'] . '(' . $file['id'] . ', \'' . $flowUrl  . '\');"></a></div>
						';
					break;

					case "avi":
					case "mkv":
						$key = $file['id'];
						$play = '<a rel="video" href="#video' . $file['id'] . '"><img src="/img/play.gif" width="19" alt="" title="' . __('Watch online', true) . '" id="file' . $file['id'] . '" /></a>';
						$hideVideo .= '<div id="video' . $key . '" style="width:640px; height:480px; overflow: hidden; " >
							<a onclick="return addAviVideo' . $variant['id'] . '(' . $key . ', \'' . $recUrl . '\');"></a>
							<object id="videoobj' . $key . '" classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616" width="640" height="480" codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab">
								<param name="wmode" value="opaque" />
								<param name="autoPlay" value="true" />
								<param name="src" value="' . $recUrl . '" />
								<embed type="video/divx" src="' . $recUrl . '"	width="640" height="480" wmode="opaque" autoPlay="true" previewImage="" pluginspage="http://go.divx.com/plugin/download/">
								</embed>
							</object>
						</div>';
					break;
					default:
						$play = '';
				}
        	}
			else
			{
				$play = '';
			}
//$play = '';//ВРЕМЕННО СКРЫТЬ

//        	foreach ($players as $player)
//	        	$href .= ' <a href="/media/playlist/' . $file['id'] . '/' . $player['name'] . '"><img height="16" src="/img/ico/' . $player['name'] . '16.gif" /></a>';
		}

        $playDisplay = '';
		$panelContent .= $href;

		/*if (!empty($file['dcpp_link'])){
        <a href="<?= $file['dcpp_link']?>">DC++</a> } */
		$panelContent .= '
        		</td>
        		<td>
        		' . $play . '
        		</td>
    		</tr>
    	';
        if ($fileCnt % 3 == 0)
        {
	    	$panelContent .= $msg;
	    	$msg = '';//ВЫВОДИМ ОДИН РАЗ
        }
		$linksCnt++;
    }
	$panelContent .= '</table>
<script type="text/javascript" src="/js/flowplayer/flowplayer-3.2.4.min.js"></script>
<script type="text/javascript" src="/js/flowplayer/flowplayer.ipad-3.2.1.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
       $(".nocontext").bind("contextmenu",function(e){
              return false;
       });
});

<!--
		function addAviVideo' . $variant['id'] . '(num, path)
		{
			return true;
		}

		function addVideo' . $variant['id'] . '(num, path) {
			document.getElementById("ipad" + num).href=path;
			document.getElementById("video" + num).style.display="";
			$f("ipad" + num, "/js/flowplayer/flowplayer-3.2.5.swf",
								{plugins: {
									h264streaming: {
										url: "/js/flowplayer/flowplayer.pseudostreaming-3.2.5.swf"
												 }
	                             },
								clip: {
									provider: "h264streaming",
									autoPlay: true,
									scaling: "fit",
									autoBuffering: true,
									scrubber: true
								},
								canvas: {
									backgroundGradient: "none",
									backgroundColor: "#000000"
								}
					}
						).ipad();
			return false;
		}
-->
</script>
	';
}
else
{
	if (!empty($variant['FilmFile']))
	{
		$panelContent = '
		<br /><br />
		<p>' . __('Available only to registered users', true) . '</p>
		<br />
		';
	}
}

//pr($variant['FilmLink']);
if (!empty($variant['FilmLink']))
{

//	pr($variant);
//exit;
		if ($isWS && !empty($FilmVariant[0]['Track']['Language']['title']) && count($FilmVariant[0]['FilmFile']) == 0)
		{
//ВЫВОД ИНФО ПАРАМЕТРОВ ВИДЕО
?>
<h4><?php __('Quality');?> <?= $FilmVariant[0]['VideoType']['title'] ?><br />
<?php
	if ($lang != _ENG_)
	{
?>
Перевод: <?= $FilmVariant[0]['Track']['Language']['title'] . ', ' . $FilmVariant[0]['Track']['Translation']['title'] ?><br>
<?php
	}
if (!empty($authUser['userid']) || $isWS)
{
?>
<?php __('Video'); ?>: <?= $FilmVariant[0]['resolution'] ?><br>
<?php
	$FilmVariant[0]['Track']['audio_info'] = str_replace('stereo', '2ch stereo', $FilmVariant[0]['Track']['audio_info']);
	$FilmVariant[0]['Track']['audio_info'] = str_replace('стерео', '2ch stereo', $FilmVariant[0]['Track']['audio_info']);
	echo __('Audio', true) . ': ' . $FilmVariant[0]['Track']['audio_info'] . '<br />';
?>
<?php __('Duration'); ?>: <?= $FilmVariant[0]['duration'] ?>
<?php
}
	echo '</h4>';
		}

	if (count($variant['FilmLink']) > 0)
	{

		$variant['video_type_id'] = 12;//ПРИНУДИТЕЛЬНО ДОБАВЛЯЕМ НА ПАНЕЛЬ WEB
		$num = 1; $linksCnt = 0;
		$panelContent = '
			<br />
			<h3>' . __('Links List', true) . '</h3>
		';
		$maxWebLinksCount = Configure::read('App.webLinksCount');
		if ((!$isVip) && (!$isWS))//ЕСЛИ НЕ СТК, ПЕРЕМЕШИВАЕМ
		{
			$variant['FilmLink'] = array_slice($variant['FilmLink'], 0, $maxWebLinksCount + 3);
			srand((float) microtime() * 10000000);
			if (rand(1, 10) > 9)
			{
				unset($variant['FilmLink'][0]);//УДАЛЯЕМ ССЫЛКУ НА FL (ВЕРОЯТНОСТЬ ПОКАЗА 0.9)
			}
			srand((float) microtime() * 10000000);
			shuffle($variant['FilmLink']);
		}

		$startFL = 0; $flCount = 0; $flStr = 'catalog/file/'; $flVipStr = 'catalog/viewv/';
	    foreach ($variant['FilmLink'] as $link)
	    {
	    	$isFL = strpos($link['link'], $flStr);//ЭТО ССЫЛКА ИЗ ОБМЕННИКА
			if ($isFL)
			{
				$flCount++;
			}
		}
		$maxWebLinksCount++;//КОМПЕНСИРУЕМ, ЕСЛИ ССЫЛКА FL ОКАЖЕТСЯ НА ПОСЛЕДНЕМ МЕСТЕ
		$recomended = '
			<div class="recomended">' . __('This link is recommending for your region', true) . '</div>
		';
	    foreach ($variant['FilmLink'] as $link)
	    {
	    	$isFL = strpos($link['link'], $flStr);//ЭТО ССЫЛКА ИЗ ОБМЕННИКА
	    	//if ($isFL && !$isWS) continue;

			if ($isFL)
			{
		    	//if ($isVip)
		    	if ($isVip || $isWS)//внутр пользователям и ВИПам ссылки выдаем сразу
		    	{
					$maxWebLinksCount++;//КОМПЕНСИРУЕМ МАКС. КОЛ-ВО ВЫВОДИМЫХ ССЫЛОК
		    		$link['link'] = str_replace($flStr, $flVipStr, $link['link']);
		    		if (empty($startFL))
		    		{
		    			if ($flCount > 1)
		    			{
							$panelContent .= '<h3 style="margin-bottom:0px;"><img src="/img/greenstar.png" width="20" /> ' . $link['title'] . ' ' . $Film["year"] . '</h3>';
							$panelContent .= '<h3 style="margin-bottom:0px;">';
					    	if ($lang == _ENG_)
					    	{
					    		$panelContent .= $imdbDirectedBy;
					    	}
					    	else
					    	{
					    		$panelContent .= strip_tags(implode(', ', $directors));;
					    	}
							$panelContent .= '</h3>';
							$panelContent .= '<p>';
				    		if ($lang == _ENG_)
				    		{
				    			$panelContent .= $imdbGenres;
				    		}
				    		else
				    		{
							    $panelContent .= $app->implodeWithParams(', ', $Genre);
					    	}
							$panelContent .= '</p>';
			    			$panelContent .= '<ul><li><a target="_blank" href="' . $link['link'] . '">' . $link['filename'] . '</a></li>';
		    			}
		    			else
		    			{
							$panelContent .= '<h3 style="margin-bottom:0px;"><img src="/img/greenstar.png" width="20" /> <a target="_blank" href="' . $link['link'] . '">' . $link['title'] . '</a> ' . $Film["year"] . '</h3>';
							$panelContent .= '<h3 style="margin-bottom:0px;">';
					    	if ($lang == _ENG_)
					    	{
					    		$panelContent .= $imdbDirectedBy;
					    	}
					    	else
					    	{
					    		$panelContent .= strip_tags(implode(', ', $directors));
					    	}
							$panelContent .= '</h3>';
							$panelContent .= '<p>';
				    		if ($lang == _ENG_)
				    		{
				    			$panelContent .= $imdbGenres;
				    		}
				    		else
				    		{
							    $panelContent .= $app->implodeWithParams(', ', $Genre);
					    	}
							$panelContent .= '</p>';
		    			}
		    		}
		    		else
		    		{
		    			$panelContent .= '<li><a target="_blank" href="' . $link['link'] . '">' . $link['filename'] . '</a></li>';
		    		}
		    	}
//*
		    	else
		    	{
		    		if ($startFL) continue;
		    		$panelContent .=  $recomended;
					$panelContent .= '<h3 style="margin-bottom:0px;"><img src="/img/greenstar.png" width="20" /> <a target="_blank" href="' . $link['link'] . '">' . $link['title'] . '</a> ' . $Film["year"] . '</h3>';
					$panelContent .= '<h3 style="margin-bottom:0px;">';
			    	if ($lang == _ENG_)
			    	{
			    		$panelContent .= $imdbDirectedBy;
			    	}
			    	else
			    	{
			    		$panelContent .= strip_tags(implode(', ', $directors));
			    	}
					$panelContent .= '</h3>';
					$panelContent .= '<p>';
		    		if ($lang == _ENG_)
		    		{
		    			$panelContent .= $imdbGenres;
		    		}
		    		else
		    		{
					    $panelContent .= $app->implodeWithParams(', ', $Genre);
			    	}
					$panelContent .= '</p>';
				}
//*/
				$startFL++;
				$maxWebLinksCount--;

				if (empty($recUrl))
		    	{
		    		$recUrl = $link['link'];
		    	}
			}
			else
			{
				if (!empty($startFL))
				{
					$panelContent .= '</ul>';
				}
				$startFL = 0;

				if (!$isWS)
				{
					$panelContent .= '<h3 style="margin-bottom:0px;"><img src="/img/blackstar.png" width="20" /> <a target="_blank" href="' . $link['link'] . '">' . $link['title'] . '</a></h3>';
					$panelContent .= '<p>' . $link['descr'] . '</p>';
				}
			}

			$linksCnt++;
			if ($link['zone'] == 'web')
			{
				$maxWebLinksCount--;
			}
			if ($maxWebLinksCount < 0) break;
	    }
	}
}

	$currentPanelId = '';
	if (in_array(intval($variant['video_type_id']), array_keys($HQTypes)))
	{
		$currentPanelId = 'hqpanel';
	}
	if (in_array(intval($variant['video_type_id']), array_keys($SQTypes)))
	{
		$currentPanelId = 'sqpanel';
	}
	if (in_array(intval($variant['video_type_id']), array_keys($mobTypes)))
	{
		$currentPanelId = 'mobpanel';
	}
	if (in_array(intval($variant['video_type_id']), array_keys($webTypes)))
	{
		$currentPanelId = 'webpanel';
	}

	if (!empty($linksCnt))
	{
		$panelLinksCnt[$currentPanelId] = $linksCnt;
	}

	switch ($currentPanelId)
	{
		case"hqpanel":
			if (empty($panelContent))
			{
				$panelContent = '
				<br /><br />
				<p>' . __('Links not found', true) . '</p>
				<br />
				';
			}
		break;
		case"sqpanel":
			if (empty($panelContent))
			{
				$panelContent = '
				<br /><br />
				<p>' . __('Links not found', true) . '</p>
				<br />
				';
			}
		break;
		case"mobpanel":
			if (empty($panelContent))
			{
				$panelContent = '`
				<br /><br />
				<p>' . __('Links not found', true) . '</p>
				<br />
				';
			}
		break;
		case"webpanel":
			if (empty($panelContent))
			{
				if (($isVip) || ($isWS))
				{
					$panelContent = '
					<script type="text/javascript">
					<!--
						findLinks();
					-->
					</script>
					';
				}
				else
				{
					$panelContent = '
					<br />
					<p>' . __('Links not found', true) . '</p>
					<h3><a href="#" onclick="return findLinks();">' . __('Search in Web', true) . '</a></h3>
					<br />
					';
				}
			}
		break;
	}


	if (!empty($panelContent) && !in_array($currentPanelId, $panels))
	{
		$panels[] = $currentPanelId;
		$panelContent = '<div id="' . $currentPanelId . '">' . $panelContent . '</div>';
	}
	else
		$panelContent = '';

	$linksContent .= $panelContent;

	}

//ВЫВОД УПРАВЛЯЮЩИХ ЗАКЛАДОК
	$linksContent .= '<table width="700" cellspacing="0" cellpadding="3" border="0">';
	$maxLinksPanel = 'webpanel'; $maxLinks = 100;

	if ($Film['is_license'])
	{
		$allPanels = array('sqpanel' => __('Standard definition video', true), 'mobpanel' => __('Video Mobile', true));
		$maxLinksPanel = 'sqpanel';
	}
	else
	{
		//$allPanels = array('hqpanel' => __('High definition video', true), 'sqpanel' => __('Standard definition video', true), 'mobpanel' => __('Video Mobile', true), 'webpanel' => __('Search in Web', true));
		$allPanels = array('webpanel' => __('Search in Web', true));
	}

	if (!empty($ozons))
	{
		$allPanels['ozonpanel'] = __('Buy on', true) .  ' ozon.ru';
		$panelLinksCnt['ozonpanel'] = count($ozons);
		$linksContent .= '<div id="ozonpanel" style="display:none"><br /><h3>' . __('Buy on', true) .  ' ozon.ru</h3>';
		foreach ($ozons as $o)
		{
			$pr = (!empty($o["OzonProduct"]['price'])) ? (sprintf("%01.2f", $o["OzonProduct"]['price'])) : "";
			$cur = (!empty($o["OzonProduct"]['currency'])) ? (' (' . $o["OzonProduct"]['currency'] . ') ') : "";
			$year = (!empty($o["OzonProduct"]['year'])) ? (', ' . $o["OzonProduct"]['year']) : "";
			$media = (!empty($o["OzonProduct"]['media'])) ? (', ' . $o["OzonProduct"]['media']) : "";
			$url = $o["OzonProduct"]['url'];
			$linksContent .= '<div class="ozonlist"><a target="_blank" href="' . $url . '"><img align="left" hspace="3" width="80" src="' . $o["OzonProduct"]['picture'] . '" />' . $o["OzonProduct"]['title'] . $year . $media . '<br /><b>' . $pr . $cur . '</b></a></div>';
		}
		$linksContent .= '</div>';
	}

		foreach ($allPanels as $key => $value)
		{
			$linksCntStr = '';
			if (!empty($panelLinksCnt[$key]))
			{
				$linksCntStr = ' (' . $panelLinksCnt[$key] . ')';
				if (($panelLinksCnt[$key] > 0) && ($panelLinksCnt[$key] < $maxLinks) && ($key <> 'ozonpanel'))
				{
					$maxLinks = $panelLinksCnt[$key];
					$maxLinksPanel = $key;
				}
			}
			if ($linksCntStr)
			{
				$a = '<a style="display: block" href="#" onclick="return focusPanel(\'' . $key . '\');">' . $value . $linksCntStr . '</a>';
			}
			else
			{
				$a = $value . $linksCntStr;
			}
			$linksContent .= '<td id="' . $key . 'folder" class="unfocusedpanel">' . $a . '</td>';
		}
		$linksContent .= '
			<td id="lastfolder">&nbsp;</td></tr>
			<tr><td id="panelcontent" colspan="5"></td></tr>
			</table>
		';
		$linksContent .= '
<script type="text/javascript">
<!--
	function focusPanel(id)
	{
		if (curPanel != id)
		{
			if (curPanel == \'\')
			{
				curPanel = id;
				$("#" + curPanel + "folder").removeClass("unfocusedpanel");
			}
			else
			{
				$("#" + curPanel + "folder").removeClass("focusedpanel");
				$("#" + curPanel + "folder").addClass("unfocusedpanel");
			}
		}
		curPanel = id;
		$("#" + curPanel + "folder").addClass("focusedpanel");
		$("#panelcontent").html($("#" + curPanel).html());

		$("a[rel=video]").fancybox({
	        "zoomSpeedIn":  0,
	        "zoomSpeedOut": 0,
	        "overlayShow":  true,
	        "overlayOpacity": 0.8,
	        "showNavArrows": false,
			"onComplete": function() { $(this.href + " a").trigger("click"); return false; }
		});

		return false;
	}
	curPanel = \'\';
	focusPanel(\'' . $maxLinksPanel . '\');
-->
</script>
	';
}
else
{
	/*
    if ($authUser['userid'] <= 0)
    {
		echo'
			<br /><h4><font color="red">Ссылки доступны только для зарегистрированных пользователей</font></h4>
	    ';
    }
    */
	$divxContent = '';
	//$linksContent = '<a href="http://yandex.ru/yandsearch?text=' . $yandex['title'] . '" title="Скачать бесплатно">Скачать бесплатно "' . $yandex['title'] . '"</a>';
	$linksContent = $yandexLink;
}

/*
if (isset($authUser['username']))// && (($authUser['username'] == 'vanoveb') || ($authUser['username'] == 'stell_hawk')))
{
*/
	if (isset($_SESSION['lastFilms']))
		$lastFilms = $_SESSION['lastFilms'];
	else
		$lastFilms = array();

	if (isset($_SESSION['lastLinks']))
		$lastLinks = $_SESSION['lastLinks'];
	else
		$lastLinks = array();

	if (isset($_SESSION['lastDivx']))
		$lastDivx = $_SESSION['lastDivx'];
	else
		$lastDivx = array();

	$vipLinks = $linksContent;//СОХРАНИМ, ЕСЛИ ОКАЖЕТСЯ ВИПОМ
	$vipDivx = $divxContent;//СОХРАНИМ, ЕСЛИ ОКАЖЕТСЯ ВИПОМ
	$divxContent = '';
	//$linksContent = $yandexLink;
//$geoIsGood = true;
	if ($allowDownload)
	{
		if (!$geoIsGood)
		{
			$divxContent = '';
			$linksContent = $yandexLink;
		}
	}
	else
	{
		$divxContent = '';
		$linksContent = $yandexLink;
	}

	$lastFilms[$film['Film']['id']]	= $Film;
	$lastLinks[$film['Film']['id']]	= $linksContent;
	$lastDivx[$film['Film']['id']]	= $divxContent;
	if ($authUser['userid'] && $allowDownload && $geoIsGood)
	{
		$lastLinks[$film['Film']['id']]	= $vipLinks;
		$lastDivx[$film['Film']['id']]	= $vipDivx;
	}
	else
	{
		//$lastLinks[$film['Film']['id']]	= '<h3 style="margin-top:12px;"><a href="' . __('search_link', true) . $yandex['title' . $langFix] . '" title="' . __('Search web', true) . '">"' . $yandex['title' . $langFix] . '" ' . __('Search web', true) . ' &raquo;</a>' . $faqLink . '</h3>';
		if (!empty($authUser["userid"]) && ($authUser["userid"] > 0))
			$lastLinks[$film['Film']['id']]	= '<h3 style="margin-top:12px;">' . __('Sorry, the license was not found. Download the movie is impossible.', true) . '</h3>';
		else
			$lastLinks[$film['Film']['id']]	= '<h3 style="margin-top:12px;">' . __('Download available just for registered users', true) . '<br /><a href="/users/register">' . __('Register', true) . '</a></h3>';
	}
	$_SESSION['lastFilms']	= $lastFilms;
	$_SESSION['lastLinks']	= $lastLinks;
	$_SESSION['lastDivx']	= $lastDivx;

	//ПРОВЕРКА НА ОПЕРУ-ТУРБО
	function isOperaTurbo()
	{
		$agent = (empty($_SERVER['HTTP_USER_AGENT']) ? '' : strtolower($_SERVER['HTTP_USER_AGENT']));
		$hostName = strtolower(gethostbyaddr(empty($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["REMOTE_ADDR"] : $_SERVER["HTTP_X_FORWARDED_FOR"]));
		return (
				(strpos($hostName, 'opera-mini.net') !== false)
				||
				(strpos($agent, 'opera mini') !== false)
			   );
	}

	//if (isOperaTurbo())
	if ($isOpera)//ЗНАЧЕНИЕ ОПРЕДЕЛЕНО В AppController
	{
		//$browser = strtolower(get_browser(null, true));
		//if (strpos($browser, 'opera') === true)
		{
			$linksContent = '<h3><b>' . __('Users of the Opera browser', true) . '</b> ' . __("have to disable the 'Opera Turbo' option", true) . ',<br />' . __('and then', true) . ' <a href="/media/view/' . $Film['id'] . '">' . __('get links', true) . '</a></h3>';
		}
	}

	if (!$geoIsGood)
	{
		$divxContent = '';
		if (!$isOpera)
			$linksContent = '';
		if ($film['Film']['imdb_id'])
		{
			echo '<h3 style="margin-top:12px;"><a target="_blank" href="http://imdb.com/title/' . $film['Film']['imdb_id'] . '">"' . $film['Film']['title' . $langFix] . '" imdb.com &raquo;</a></h3>';
		}
		//echo '<h3 style="margin-top:12px;"><a target="_blank" title="скачать на kinopoisk.ru" href="http://www.kinopoisk.ru/index.php?kp_query=' . rawurlencode(iconv('utf-8','windows-1251', $film['Film']['title'])) . '">"' . $film['Film']['title'] . '" cкачать &raquo;</a></h3>';
		echo '<h3 style="margin-top:12px;"><a target="_blank" href="http://google.com/search?q=' . rawurlencode(iconv('utf-8','windows-1251', $film['Film']['title'])) . '">"' . $film['Film']['title'] . '" ' . __('Free download', true) . ' &raquo;</a></h3>';
		//echo $yandexLink;
	}
	if ($geoIsGood)// && $isVip)
	{
		$linksContent	= $vipLinks;
		$divxContent	= $vipDivx;
	}

//echo 'GEO DISABLED'; pr($geoIsGood);
//echo 'ALLOW DISABLED'; pr($allowDownload);

	if (!empty($ozons))
	{
?>
<!--
<h3><a href="/media/ozon/<?php echo $Film['id']; ?>" title="<?php __('Buy on'); echo ' ozon.ru'; ?>" alt="<?php __('Buy on'); echo ' ozon.ru'; ?>"><?php __('Buy on'); echo ' ozon.ru'; ?></a></h3>
-->
<?php
	}

echo $divxContent;
echo $linksContent;

?>
    <h4><?php __('Pictures'); ?>:</h4>
    <p>
    <?php
    foreach ($FilmPicture as $picture)
    {
        extract($picture);
        if ($type != 'frame')
           continue;
        $img = $html->image($imgPath . preg_replace('#/f(\d)#i', '/s$1', $file_name), array('style' => 'margin: 2px 5px 2px 0'));
        echo $html->link($img, $imgPath . $file_name, array('rel' => 'fancybox', 'onclick' => 'return stopdivx();'), false, false);
    }
    ?>
</p>
    <?php
    /*
if (count($FilmComment) > 0)
{
	echo '<h4>Комментарии:</h4>';
    foreach ($FilmComment as $comment)
    {
        extract($comment);
?>
    <div class="commentBox">
        <p class="author"><strong><?= $user_id ? $html->link($username, $app->getUserProfileUrl($user_id)) : h($username) ?></strong>
        &nbsp;<em>/&nbsp;<?= $app->timeShort($created) ?></em>
        &nbsp; <?php
        if ($allowEdit)
            echo $html->link('удалить', '/admin/film_comments/delete/' . $id);

        ?>
        </p>
        <p><?= h($text) ?></p>
    </div>
<?php
    }
}
//pr($form);
	if ($authUser['userid'])
    {
    ?>
    <?= $form->create('FilmComment', array('class' => 'leaveComment', 'url' => '/media/view/' . $Film['id'])) ?>
        <h4>Оставить комментарий</h4>
        <?= $form->hidden('film_id', array('value' => $Film['id'])) ?>
        <?php if (!$authUser['userid'])
        {
            echo $form->input('username', array('class' => 'textInput', 'label' => 'Имя:', 'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => 'pl')));
            echo $form->input('email', array('class' => 'textInput', 'label' => 'E-mail <em>(никто не узнает)</em>:',
                                             'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));
		?><p><label for="UserCaptcha">Проверочный код<em class="required">*</em> :</label><br>
		<?php //echo $form->error('captcha'); ?>
		<?php echo $form->text('captcha', array('class' => 'textInput')); ?></p>
		<p><img src="<?php echo $html->url('/users/captcha'); ?>" /></p>

        <?}
        ?>
        <p>
        <?= $form->error('text', 'Вы не ввели текст комментария') ?>
        <?= $form->textarea('text', array('class' => 'textInput')) ?>
        </p>
    <?= $form->end('Ответить') ?>
    <br>
	<?php
    }
    else
    	echo '<h3>Только зарегистрированные пользователи могут оставлять комментарии</h3>';
*/
?>
<br />
<?php
if (!empty($threadInfo))
{
	echo '<h4>' . __('Forum Discussion', true) . ' (' . $threadInfo['stat']. ')</h4>';

	if (!empty($threadInfo['lst']))
	{
	    foreach (array_reverse($threadInfo['lst']) as $l)
    	{
        	extract($l['Vbpost']);
?>
    <div class="commentBox">
        <p class="author"><strong><?php echo ($username) ?></strong>
        &nbsp;<em>/&nbsp;<?= $app->timeShort($dateline) ?></em>
        </p>
        <p><?= (Utils::transUbbTags($pagetext)) ?></p>
    </div>
<?php
	    }
?>
`	<a href="/forum/showthread.php?t=<?php echo $threadInfo['id']; ?>"><?php __('Show all'); ?></a>
<?php
	}
?>
<?php
}
//pr($form);
    ?>
    <?php
    if (!empty($authUser['membergroupids']))
		$blocked = ($authUser['membergroupids'] == 8) || in_array(8, explode(',', $authUser['membergroupids']));
	else $blocked = false;
    if (!empty($authUser['userid']) && !$blocked)
    {
    	if ($ban)
    	{
    		$threadInfo['enabled'] = false;
    	}

    	if (!empty($threadInfo) && $threadInfo['enabled'])
    	{
    		$data['Vbpost'] = array();
    ?>
    <?= $form->create('Vbpost', array('class' => 'leaveComment', 'url' => '/media/view/' . $Film['id'])) ?>
        <h4><?php __('Add a message');?></h4>
        <p>
        <?= $form->error('text', __('You have not entered the message text', true)) ?>
        <?= $form->textarea('pagetext', array('class' => 'textInput', 'value' => '')) ?>
        </p>
    <?= $form->end(__('Answer', true)) ?>
    <br>
	<?php
    	}
    	else
    		echo '<h3>' . __('Discussion of the film on the forum temporarily closed', true) . '</h3>';
    }
    else
    {
    	if ($blocked)
    		echo '<h3>' . __('Blocked users can not participate in the discussion', true) . '</h3>';
    	else
    		echo '<h3>' . __('Only registered users can participate in the discussion', true) . '</h3>';
    }
	?>
</div>
<script type="text/javascript">
	function stopdivx()
	{
		s = document.getElementById("divxdiv");
		if (s != null) s.innerHTML = "";
		return false;
	}
</script>
<?php
}
	$script = "
<script type=\"text/javascript\">
<!--
$(document).ready(function() {
	$('a[rel=posters]').fancybox({
        'zoomSpeedIn':  0,
        'zoomSpeedOut': 0,
        'overlayShow':  true,
        'overlayOpacity': 0.8
    });
    $('a[rel=fancybox]').fancybox({
        'zoomSpeedIn':  0,
        'zoomSpeedOut': 0,
        'overlayShow':  true,
        'overlayOpacity': 0.8
    });
});
-->
</script>";
	if (!empty($hideVideo))
	{
		$script .= "
			<div style=\"display: none\">
				" . $hideVideo . "
			</div>
		";
	}
	echo $script;

$c = ob_get_clean();
if (!empty($recUrl) && !($Film['just_online']))
	$c = strtr ($c, array('rel="nohref" nohref="nohref"' => 'href="' . $recUrl . '"'));
echo $c;
?>