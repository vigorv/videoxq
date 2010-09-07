<?php
    echo '<div class="viewright">';
	if (($this->params['controller']=='media') && ($this->params['action']=='view'))
		echo $this->element('blocks', array('blockArray' => $blockContent['right']));
	echo'</div>';
?>
<ul id="menu">
<?php
$people = $media = $basket = '>';
$people_end = $media_end = $basket_end = '';
${$this->params['controller']} = 'class="active"><strong>';
${$this->params['controller'] . '_end'} = '</strong>';
?>
    <li <?= $media ?><a href="/media">Фильмы</a><?= $media_end ?></li>
    <li <?= $people ?><a href="/people">Люди</a><?= $people_end ?></li>
    <li <?= $basket ?><a href="/basket">Список загрузок</a><?= $basket_end ?></li>
</ul>
