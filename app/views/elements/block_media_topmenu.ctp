<?php
    echo '<div class="viewright">';
	if (($this->params['controller']=='media') && ($this->params['action']=='view'))
		echo $this->element('blocks', array('blockArray' => $blockContent['right']));
	echo'</div>';
?>
<!--
<ul id="menu">
<?php
$people = $media = $basket = '>';
$people_end = $media_end = $basket_end = '';
${$this->params['controller']} = 'class="active"><strong>';
${$this->params['controller'] . '_end'} = '</strong>';
?>
    <li <?= $media ?><a href="/media"><?php echo __("Video", true); ?></a><?= $media_end ?></li>
    <li <?= $people ?><a href="/people"><?php echo __("People", true); ?></a><?= $people_end ?></li>
<?php
	if (Configure::read('Config.language') == _RUS_)
	{
?>
    <li <?= $basket ?><a href="/basket"><?php __("Downloads");?></a><?= $basket_end ?></li>
<?php
	}
?>
</ul>
-->
