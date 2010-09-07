<?php

$sort = !empty($this->params['named']['sort']) ? $this->params['named']['sort'] : 'Film.modified';
$serialsClass = !empty($this->params['named']['type']) && strpos($this->params['named']['type'], '!15') === false ? 'active' : '';
$hdtvClass = !empty($this->params['named']['vtype']) !== false ? 'active' : '';
$pageNavigator->setUrl('/media/index');
?>
                <table border="0" cellspacing="0" cellpadding="0" width="260">
                  <tbody>
                    <tr>
                      <td class="corner1" width="25"> </td>
                      <td class="border3"> </td>
                      <td class="corner2" width="25"> </td>
                    </tr>
                    <tr>
                      <td class="border1"> </td>
                      <td>
<ul id="genres">
    <li <?php echo $sort == 'Film.year' ? 'class="active"' : ''; ?>><?=
    	$html->link('Новые', $pageNavigator->getNavigateUrl(array('link' => 'new', 'sort' => 'Film.year', 'direction' => 'desc')));
    ?></li>
    <li <?php echo $sort == 'Film.hits' ? 'class="active"' : ''; ?>><?=
    	$html->link('Популярные', $pageNavigator->getNavigateUrl(array('sort' => 'Film.hits', 'direction' => 'desc')));
    ?></li>
    <li <?php echo $sort == 'MediaRating.rating' ? 'class="active"' : ''; ?>><?=
    	$html->link('Лучшие Юзеров', $pageNavigator->getNavigateUrl(array('sort' => 'MediaRating.rating', 'direction' => 'desc')));
    ?></li>
    <li <?php echo $sort == 'Film.imdb_rating' ? 'class="active"' : ''; ?>><?=
    	$html->link('Лучшие IMDb', $pageNavigator->getNavigateUrl(array('sort' => 'Film.imdb_rating', 'direction' => 'desc')));
    ?></li>
    <li <?php echo $sort == 'Film.modified' ? 'class="active"' : ''; ?>><?=
		$html->link('Последние добавленные', $pageNavigator->getNavigateUrl(array('sort' => 'Film.modified', 'direction' => 'desc')));
	?></li>
    <li>&nbsp;</li>
    <li class="all <?php echo empty($this->params['named']['genre']) && empty($this->params['named']['type']) && empty($this->params['named']['vtype']) ? 'active' : ''; ?>"><a href="/media">Все фильмы</a></li>
<!--
    <li class="all <?php
    	echo $hdtvClass; $hdtvStr = "HDTV";
    	if (!empty($vtInfo[9]['count']))
    		$hdtvStr .= ' (' . $vtInfo[9]['count'] . ')';
    				?>"><a href="/media/index/vtype:9"><?php echo $hdtvStr; ?></a></li>
-->
    <?php
//*/
    foreach ($block_media_genres['genres'] as $key => $genre)
    {
        $class = '';

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
        echo '<li ' . $class . '><a href="' . $link . '">' . $genre . '</a></li>';
    }
    ?>
</ul>
                      </td>
                      <td class="border2"> </td>
                    </tr>
                    <tr>
                      <td class="corner3" width="25"> </td>
                      <td width="*" class="border4"> </td>
                      <td class="corner4" width="25"> </td>
                    </tr>
                  </tbody>
                </table>
                <br />
<?php
$placeNamePrefix = '';
if ($isWS)
	$placeNamePrefix = 'WS';

$placeName = $placeNamePrefix . 'left2';
echo $BlockBanner->getBanner($placeName);
