<script type="text/javascript">
<!--
	function switchGenres()
	{
		$("li[rel=switchedgenre]").toggle();
		return false;
	}
-->
</script>

<ul id="genres">
<?php
//if ($block_media_genres['allowDownload'])
{
	echo '
    <p>' . __("Total in database", true) . ' <strong>' . $app->pluralForm($filmStats['count'], array(__("film", true), __("filma", true), __("films", true))) . '</strong>
	<br>' . __("Total duration", true) . ' <strong>' . $app->timeFormat($filmStats['size']) . '</strong>
    </p>
	';
}

$sort = !empty($this->params['named']['sort']) ? $this->params['named']['sort'] : 'Film.modified';
$serialsClass = !empty($this->params['named']['type']) && strpos($this->params['named']['type'], '!15') === false ? 'active' : '';
$hdtvClass = !empty($this->params['named']['vtype']) !== false ? 'active' : '';
//if (!empty($this->params['named']['type']) && strpos($this->params['named']['type'], '!15') !== false)
//    $serialsLink = '/' . preg_replace('#/type:(.*)#', '/', $this->params['url']['url']);
//elseif ($this->params['url']['url'] == 'media')
//    $serialsLink = '/' . $this->params['url']['url'] . '/index/type:!15,!7';
//else
//    $serialsLink = '/' . $this->params['url']['url'] . '/type:!15,!7';
/*
?>
    <li <?php echo $sort == 'Film.year' ? 'class="active"' : ''; ?>><?= $paginator->link('Новые', array('link' => 'new', 'sort' => 'Film.year', 'order' => array('Film.year' => 'desc'))) ?></li>
    <li <?php echo $sort == 'Film.hits' ? 'class="active"' : ''; ?>><?= $paginator->link('Популярные', array('sort' => 'Film.hits', 'order' => array('Film.hits' => 'desc'))) ?></li>
    <li <?php echo $sort == 'MediaRating.rating' ? 'class="active"' : ''; ?>><?= $paginator->link('Лучшие Юзеров', array('sort' => 'MediaRating.rating', 'order' => array('MediaRating.rating' => 'desc'))) ?></li>
    <li <?php echo $sort == 'Film.imdb_rating' ? 'class="active"' : ''; ?>><?= $paginator->link('Лучшие IMDb', array('sort' => 'Film.imdb_rating', 'order' => array('Film.imdb_rating' => 'desc'))) ?></li>
    <li <?php echo $sort == 'Film.modified' ? 'class="active"' : ''; ?>><?= $paginator->link('Последние добавленные', array('sort' => 'Film.modified', 'order' => array('Film.modified' => 'desc'))) ?></li>
    <li>&nbsp;</li>
    <li class="all <?php echo empty($this->params['named']['genre']) && empty($this->params['named']['type']) && empty($this->params['named']['vtype']) ? 'active' : ''; ?>"><a href="/media">Все фильмы</a></li>
    <li class="all <?php echo (((!$serialsClass) && (!$hdtvClass)) && (!empty($this->params['named']['type']))) ? 'active' : ''; ?>"><a href="/media/index/type:!15,!7,!2">Не сериалы</a></li>
    <li class="all <?php echo $serialsClass; ?>"><a href="/media/index/type:15,7,2">Сериалы</a></li>
<!--
    <li class="all <?php
    	echo $hdtvClass; $hdtvStr = "HDTV";
    	if (!empty($vtInfo[9]['count']))
    		$hdtvStr .= ' (' . $vtInfo[9]['count'] . ')';
    				?>"><a href="/media/index/vtype:9"><?php echo $hdtvStr; ?></a></li>
-->
    <?php
//*/
//*

?>
    <li <?php echo $sort == 'Film.year' ? 'class="active"' : ''; ?>><?=
    	$html->link(__("Newa", true), $pageNavigator->getNavigateUrl(array('link' => 'new', 'sort' => 'Film.year', 'direction' => 'desc')));
    ?></li>
    <li <?php echo $sort == 'Film.hits' ? 'class="active"' : ''; ?>><?=
    	$html->link(__("Popular", true), $pageNavigator->getNavigateUrl(array('sort' => 'Film.hits', 'direction' => 'desc')));
    ?></li>
    <li <?php echo $sort == 'MediaRating.rating' ? 'class="active"' : ''; ?>><?=
    	$html->link(__("Most voted", true), $pageNavigator->getNavigateUrl(array('sort' => 'MediaRating.rating', 'direction' => 'desc')));
    ?></li>
    <li <?php echo $sort == 'Film.imdb_rating' ? 'class="active"' : ''; ?>><?=
    	$html->link(__("Best IMDb", true), $pageNavigator->getNavigateUrl(array('sort' => 'Film.imdb_rating', 'direction' => 'desc')));
    ?></li>
    <li <?php echo $sort == 'Film.modified' ? 'class="active"' : ''; ?>><?=
		$html->link(__("Last added", true), $pageNavigator->getNavigateUrl(array('sort' => 'Film.modified', 'direction' => 'desc')));
	?></li>
    <li>&nbsp;</li>
    <li class="all <?php echo empty($this->params['named']['genre']) && empty($this->params['named']['type']) && empty($this->params['named']['vtype']) ? 'active' : ''; ?>"><a href="/media"><?php __("All films"); ?></a></li>
<?php
/*
if (!$isWS)
{
    <li <?php echo !empty($this->params['named']['is_license']) !== false ? 'class="active"' : ''; ?>>
    	<a href="/media/index/is_license:1"><?php __("Downloadable"); ?></a>
    </li>
}
*/
?>
    <li class="all" rel="switchedgenre"><a href="#" onclick="return switchGenres();"><b><?php __("More genres"); ?> +</b></a></li>
    <li class="all" rel="switchedgenre" style="display: none"><a href="#" onclick="return switchGenres();"><b><?php __("Cut genres"); ?> -</b></a></li>
<!--
    <li class="all <?php
    	echo $hdtvClass; $hdtvStr = "HDTV";
    	if (!empty($vtInfo[9]['count']))
    		$hdtvStr .= ' (' . $vtInfo[9]['count'] . ')';
    				?>"><a href="/media/index/vtype:9"><?php echo $hdtvStr; ?></a></li>
-->
    <?php
//*/
	$enabledGenres = array(
		6, //Боевик
		2, //Драма
		4, //Комедия
		7, //Ужасы
		12, //Приключения
	);

	$visibleGenres = array();
	$hiddenGenres = array();
    foreach ($block_media_genres['genres'] as $key => $genre)
    {
        $class = '';

        $is_visible = in_array(intval($key), $enabledGenres);
        if (!empty($this->params['named']['genre']))
        {
            $passedGenres = explode(',', $this->params['named']['genre']);
            if (in_array($key, $passedGenres) === TRUE)
            {
                $class = ' class="active"';
                $searchKey = array_search($key, $passedGenres);
                unset($passedGenres[$searchKey]);
                if (!empty($passedGenres))
                    $key = implode(',', $passedGenres);
                else
                    $key = null;
            }
            else
            {
                $key .= ',' . $this->params['named']['genre'];
            }
        }
        $link = '/media/index' . ($key ? '/genre:' . $key : '') . ($sort ? '/sort:' . $sort : '');
        if ($is_visible)
        {
			$visibleGenres[] = '<li ' . $class . '><a href="' . $link . '">' . $genre . '</a></li>';
        }
        else
        {
			$hiddenGenres[] = '<li ' . $class . ' style="display:none" rel="switchedgenre"><a href="' . $link . '">' . $genre . '</a></li>';
        }
    }
    echo implode('', $visibleGenres);
    echo implode('', $hiddenGenres);
    ?>
</ul>
