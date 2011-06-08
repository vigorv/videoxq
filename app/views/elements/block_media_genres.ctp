<div class="module">
	<div>
		<div>
			<div>
				<table>
				<tr valign="middle">
					<td width="100%"><h4><?php
						__('Sort by');
						$divStyle = ''; $imgSrc = 'desc';
						if (empty($blockStatuses['slidersort']))
						{
							$divStyle = ' style="display: none"';
							 $imgSrc = 'asc';
						}
					?></h4></td>
					<td><a id="slidersort" rel="slider" href=""><img width="11" src="/img/s_<?php echo $imgSrc; ?>.png" /></a></td>
				</table>
				<div id="slidersortdiv" <?php echo $divStyle; ?>>
<ul id="genres">
<?php
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
</ul>
				</div>
			</div>
		</div>
	</div>
</div>
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
	$selectedGenres = array();
    foreach ($block_media_genres['genres'] as $key => $genre)
    {
        $class = '';
        if (!empty($this->params['named']['genre']))
        {
            $passedGenres = explode(',', $this->params['named']['genre']);
            if (in_array($key, $passedGenres) === TRUE)
            {
            	$selectedGenres[] = $genre;
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
		$visibleGenres[] = '<li ' . $class . '><a href="' . $link . '">' . $genre . '</a></li>';
    }

    if (!empty($selectedGenres))
    {
    	$selectedGenres = implode(', ', $selectedGenres);
    }
    else
    {
    	$selectedGenres = __("All films", true);
    }
   	$selectedGenres = '<br /><span class="smaller" alt="' . __('Selected', true) . ': ' . $selectedGenres . '" title="' . __('Selected', true) . ': ' . $selectedGenres . '">' . $selectedGenres . '</span>';
?>

<div class="module">
	<div>
		<div>
			<div>
				<table>
				<tr valign="middle">
					<td width="100%"><h4><?php
						__('Genres'); echo $selectedGenres;
						$divStyle = ''; $imgSrc = 'desc';
						if (empty($blockStatuses['slidergenres']))
						{
							$divStyle = ' style="display: none"';
							 $imgSrc = 'asc';
						}
					?></h4></td>
					<td><a id="slidergenres" rel="slider" href=""><img width="11" src="/img/s_<?php echo $imgSrc; ?>.png" /></a></td>
				</table>
				<div id="slidergenresdiv" <?php echo $divStyle;?>>
<ul id="genres">
    <li class="all <?php echo empty($this->params['named']['genre']) && empty($this->params['named']['type']) && empty($this->params['named']['vtype']) ? 'active' : ''; ?>"><a href="/media"><?php __("All films"); ?></a></li>
<?php
    echo implode('', $visibleGenres);
    ?>
</ul>
				</div>
			</div>
		</div>
	</div>
</div>
