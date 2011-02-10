<?php
if (($lang == _ENG_) && (empty($imdb_website)))
{
	echo __('Sorry, we do not have a detailed description of the movie', true) . ' &laquo;' . $film['Film']['title_en'] . '&raquo;';
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
    $javascript->link('jquery.fancybox-1.0.0', false);
    $javascript->link('jquery.pngFix', false);
    $script = "$(function() {
       $('a[rel=fancybox]').fancybox({
        'zoomSpeedIn':  0,
        'zoomSpeedOut': 0,
        'overlayShow':  true,
        'overlayOpacity': 0.8
    });
   });";
    $javascript->codeBlock($script, array('inline' => false));
    $script = "$(function() {
       $('a[rel=posters]').fancybox({
        'zoomSpeedIn':  0,
        'zoomSpeedOut': 0,
        'overlayShow':  true,
        'overlayOpacity': 0.8
    });
   });";
    if (!empty($authUser['userid']))
    {
        $javascript->codeBlock($script, array('inline' => false));
        $posterTitle = '';
    }
    else
        $posterTitle = __('Other posters are only available to registered users', true);
    $html->css('fancy', null, array(), false);
    //pr($film);
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

    $imgUrl = $imgPath . $posters[array_rand($posters)]['file_name'];
    $img = $html->image($imgUrl, array('class' => 'poster', 'title' => $posterTitle));
    echo  $html->link($img, $imgUrl, array('rel' => 'posters', 'title' => $posterTitle), false, false) . "\n";?>
    <div id="posters" style="display: none;">
    <?php
    if (!empty($authUser['userid']))
    {
        $posters = am($bigposters, $posters);
        foreach ($posters as $poster)
            echo $html->link($imgPath . $poster['file_name'], null, array('rel' => 'posters')) . "\n";
    }
    ?>
    </div>
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
    <h2>«<?php
    	if ($lang == _ENG_)
    		echo $imdbTitle;
    	else
    		echo $Film['title'];
    ?>»</h2>
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

            if (empty($authUser['userid']))
            {
                $actors = array_slice($actors, 0, 2);
                $actors[] = '<a href="#" title="' . __('Available only to registered users', true) . '">' . __('more', true) . '...</a>';
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
	$yandex = $Film; // ДЛЯ ВЫВОДА ПОИСКА ПО ЯНДЕКСУ
	$linksContent = '';
	$faqLink = ' &nbsp;<span style="font-size:25px"><a alt="' . __('How to download?', true) . '" title="' . __('How to download?', true) . '" href="/pages/faq#download">&nbsp;?&nbsp;</a></span>';
	$yandexLink = '<h3 style="margin-top:12px;"><a target="_blank" href="/media/lite/' . $Film['id'] . '" title="' . __('Download Movie', true) . '">"' . $Film['title' . $langFix] . '" ' . __('download', true) . ' &raquo;</a>' . $faqLink . '</h3>';
//$isWS = false;
	if ($isWS)
	{
		$yandexLink = '<h3 style="margin-top:12px;"><a href="http://nsk54.com/media/view/' . $Film['id'] . '" title="' . __('Download Movie', true) . '">"' . $Film['title' . $langFix] . '" ' . __('download', true) . ' &raquo;</a>' . $faqLink . '</h3>';
		$allowDownload = false; //ВСЕ РАВНО СКАЧАЮТ С НСКА
	}

if ($allowDownload)
{
//pr($FilmVariant);
$language		= ''; //на случай неустановленной информации о трэке
$translation	= ''; //на случай неустановленной информации о трэке
$audio_info		= ''; //на случай неустановленной информации о трэке


foreach ($FilmVariant as $variant)
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
?>
<h4><?php __('Quality');?> <?= $variant['VideoType']['title'] ?><br />
<?php
	if ($lang != _ENG_)
	{
?>
перевод: <?= $variant['Track']['Language']['title'] . ', ' . $variant['Track']['Translation']['title'] ?><br>
<?php
	}
if (!empty($authUser['userid']))
{
?>
<?php __('Video'); ?>: <?= $variant['resolution'] ?><br>
<?php
	echo __('Audio', true) . ': ' . $variant['Track']['audio_info'] . '<br />';

	$language		= $variant['Track']['Language']['title'];
	$translation	= $variant['Track']['Translation']['title'];
	$audio_info		= $variant['Track']['audio_info'];
?>
<?php __('Duration'); ?>: <?= $variant['duration'] ?>
<?php
}
else
{
?>
<a href="#" title="<?php __('Available only to registered users');?>"><?php __('more');?>...</a>
<?php
}
?>
</h4>
<?php
	echo $BlockBanner->getBanner('view');

		if (!empty($authUser['userid']))
		{
			if (in_array(Configure::read('VIPgroupId'), $authUserGroups)) //ДЛЯ ВИПОВ ВВОДИМ УПРАВЛЕНИЕ ВИДИМОСТЬЮ ПЛЕЕРА
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

    	$lnk = Film::set_input_server($Film['dir']).'/' . $FilmVariant[0]['FilmFile'][0]['file_name'];
    	$lnkInfo = pathinfo(strtolower(basename($lnk)));
    	$resolution = preg_split('/[\D]{1,}/', trim($FilmVariant[0]['resolution']));

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
		if (in_array(Configure::read('VIPgroupId'), $authUserGroups)) //ДЛЯ ВИПОВ ВВОДИМ УПРАВЛЕНИЕ ВИДИМОСТЬЮ ПЛЕЕРА
		{
			switch ($playSwitch)
			{
				case 'playoff':
					$playSwitchButton = '<br /><a href="/playswitch.php?' . $Film['id'] . '-playon" style="padding: 2px 5px 2px 5px; border: 1px solid black; background: red; color: white;">' . __('Watch online', true) . ' "' . $Film["title"] . '"</a><br /><br />';
				break;
				case 'playon':
					$playSwitchButton = '<br /><a href="/playswitch.php?' . $Film['id'] . '-playoff" style="padding: 2px 5px 2px 5px; border: 1px solid black; background: green; color: white;">' . __('Turn off online-player', true) . '</a><br /><br />';
				break;
			}
		}
	}
	$divxContent .= $playSwitchButton;


$linksContent .= '
	<table class="fileList">
	<script type="text/javascript">
	<!--
		function switchPlay(cur)
		{
			$("a img[rel=play]").css({display: \'\'});
			cur.style.display="none";
		}
	-->
	</script>
';
if (count($variant['FilmFile']) > 1)
{
    $linksContent .= '
    	<tr>
        <td class="action">';

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
        $linksContent .= $html->link($img, '/basket/' . $action . '/' . $variant['id'] . '/variant', array('onclick' => 'basket('.$variant['id'].', \'variant\', this);return false;','id' => 'variant_' . $variant['id'], 'alt' => __('Add to download list', true)), false, false);
    endif;

    $linksContent .= '</td>
        <td class="size">' . $app->sizeFormat($total) . '</td>
        <td class="title">' . __('All Files', true) . '</td>
    	</tr>
    ';
}
	$linksContent .= '
    	<script type="text/javascript">
    	<!--
    		function filmClk(id)
    		{
    			window.setTimeout(\'$.get("/utils/film_clicks/\' + id + \'")\', 100);
    			return true;
    		}
    	-->
    	</script>
	';
    $playDisplay = 'none';
    foreach ($variant['FilmFile'] as $file)
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
    	$linksContent .= '
    	<tr>
        	<td class="action" style="padding-left:30px">
		';

        if ($authUser['userid'] > 0):
			$linksContent .= $html->link($img,
                               '/basket/'. $action .'/' . $file['id'] . '/file',
                                 array('onclick' => 'basket('.$file['id'].', \'file\', this);return false;',
                                       'id' => 'file_' . $variant['id'] . '_' . $file['id']),
                                 false, false);
        else:
            $linksContent .= '<img width="20" height="20" title="' . __('Add to download list', true) . ' (' . __('Available only to registered users', true) . ')" alt="' . __('Add to download list', true) . ' (' . __('Available only to registered users', true) . ')" src="/img/vusic/add.gif" />';
        endif;

        $linksContent .= '
        	</td>
        	<td class="size">' . $app->sizeFormat($file['size']) . '</td>
	        <td class="title">
		';
		$play = '';
		//if (in_array(Configure::read('VIPgroupId'), $authUserGroups)) //ДЛЯ ВИПОВ ВВОДИМ УПРАВЛЕНИЕ ВИДИМОСТЬЮ ПЛЕЕРА
		if (!empty($authUser['userid'])) //ДЛЯ ВИПОВ ВВОДИМ УПРАВЛЕНИЕ ВИДИМОСТЬЮ ПЛЕЕРА
		{
        	$href='<a onclick="return filmClk(' . $Film['id'] . ');" href="' . Film::set_input_server($Film['dir']).'/' . $file['file_name'] . '">' . $file['file_name'] . '</a>&nbsp;';
        	$share = Film::set_input_share($Film['dir']);
	    	$lnkInfo = pathinfo(strtolower(basename($file['file_name'])));
        	if (($allowDownload) && !empty($lnkInfo['extension']) && ($lnkInfo['extension'] == 'avi'))
        	{
				$play = '<a href="#" onclick="return getdivx(' . $file['id'] . ');"><img src="/img/play.gif" width="19" rel="play" style="display: ' . $playDisplay . '" alt="" title="' . __('Watch online', true) . '" onclick="switchPlay(this);" /></a>';
        		if (!empty($playSwitch) && ($playSwitch == 'playon')) $play = '';
        	}
//        	foreach ($players as $player)
//	        	$href .= ' <a href="/media/playlist/' . $file['id'] . '/' . $player['name'] . '"><img height="16" src="/img/ico/' . $player['name'] . '16.gif" /></a>';
		}
//ОТКЛЮЧАЕМ ИКОНКУ ВОСПРОИЗВЕДЕНИЯ
//if (($authUser['username'] <> 'vanoveb') && ($authUser['username'] <> 'stell_hawk')) $play = '';

        $playDisplay = '';
		$linksContent .= $href;

		/*if (!empty($file['dcpp_link'])){
        <a href="<?= $file['dcpp_link']?>">DC++</a> } */
		$linksContent .= '
        		</td>
        		<td>
        		' . $play . '
        		</td>
    		</tr>
    	';
    }
	$linksContent .= '</table>';
	}
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

	//ПРОВЕРКА НА ОПЕРУ-ТУРБО
	function isOperaTurbo()
	{
		$agent = (empty($_SERVER['HTTP_USER_AGENT']) ? '' : strtolower($_SERVER['HTTP_USER_AGENT']));
		$hostName = strtolower(gethostbyaddr($_SERVER["HTTP_X_REAL_IP"]));
		return (
				(strpos($hostName, 'opera-mini.net') !== false)
				||
				(strpos($agent, 'opera mini') !== false)
			   );
	}

	if (isOperaTurbo())
	{
		$browser = strtolower(get_browser(null, true));
		//if (strpos($browser, 'opera') === true)
		{
			$linksContent = '<br /><b>' . __('Users of the Opera browser', true) . '</b> для получения ссылок необходимо отключить опцию "Opera Turbo",<br />' . __('and then', true) . ' <a href="/media/view/' . $Film['id'] . '">' . __('get links', true) . '</a>';
		}
	}

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
	$linksContent = $yandexLink;
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

	if (!$geoIsGood)
	{
		$divxContent = '';
		$linksContent = '';
		if ($film['Film']['imdb_id'])
		{
			echo '<h3 style="margin-top:12px;"><a target="_blank" href="http://imdb.com/title/' . $film['Film']['imdb_id'] . '">"' . $film['Film']['title'] . '" imdb.com &raquo;</a></h3>';
		}
		//echo '<h3 style="margin-top:12px;"><a target="_blank" title="скачать на kinopoisk.ru" href="http://www.kinopoisk.ru/index.php?kp_query=' . rawurlencode(iconv('utf-8','windows-1251', $film['Film']['title'])) . '">"' . $film['Film']['title'] . '" cкачать &raquo;</a></h3>';
		//echo '<h3 style="margin-top:12px;"><a target="_blank" title="скачать" href="http://yandex.ru/yandsearch?text=' . rawurlencode(iconv('utf-8','windows-1251', $film['Film']['title'])) . '">"' . $film['Film']['title'] . '" cкачать &raquo;</a></h3>';
		echo $yandexLink;
	}
	if ($geoIsGood && !empty($authUserGroups) && in_array(Configure::read('VIPgroupId'), $authUserGroups))
	{
		$linksContent	= $vipLinks;
		$divxContent	= $vipDivx;
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
	if (!empty($ozons))
	{
?>
<h3><a href="/media/ozon/<?php echo $Film['id']; ?>" title="<?php __('Buy on'); echo ' ozon.ru'; ?>" alt="<?php __('Buy on'); echo ' ozon.ru'; ?>"><?php __('Buy on'); echo ' ozon.ru'; ?></a></h3>
<?php
	}
?>
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
?>