<?php
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
?>
<div class="listGen">
<?php
if ($authUser['userid']):
?>
<p>
<?php __("Create download list here"); ?> <a target="_blank" href="/pages/faq#basket"> ? </a><br />
<?php
	if (count($baskets))
	{
?>
<?php __("Sort download list items"); ?></p>
<form id="BasketDownloadForm" method="post" action="/basket/download">
<fieldset style="display:none;">
<input type="hidden" name="_method" value="POST" />
</fieldset>
<input type="hidden" name="data[Basket][elements]" value="" id="BasketElements" />
<p><input type="submit" onclick="download();" value="<?php __("Create list"); ?>" /></p>
<p><?php echo $html->link(__("Clear list", true), array('action' => 'flush'));?></p>
<?php
	}
?>
</form>
</div>

<div id="baskets">
<?php
    $javascript->link('ui.core', false);
    $javascript->link('ui.draggable', false);
    $javascript->link('ui.sortable', false);
    $num = 1;
    foreach ($baskets as $basket)
    {
    ?>
<div class="downloadingList" id="id_<?php echo $basket['FilmVariant']['id'] ?>">
    <span class="nu"><?= $num++ ?>.</span>
    <?php
     $delAction = "basket(" . $basket['FilmVariant']['id'] . ", 'variant', this);return false;";
     echo $html->link(__('RemoveFromBasket', true), array('action' => 'delete', $basket['FilmVariant']['id']), array('onclick' => $delAction), false, false);?>
    <div class="moviePreviewWrapper">
        <div class="poster">
            <?php echo $html->image(Configure::read('Catalog.imgPath') . $basket[0]['Poster'], array('class' => 'poster')) . "\n";?>
            <span class="imdb">IMDb: <?php echo $basket['Film']['imdb_rating'] ?></span>
            <div class="ratings rated_<?= $basket['MediaRating']['rating'] ?>"><div></div></div>
        </div>
        <p class="text">
            <span>«<strong><?php echo $html->link($basket['Basket']['title'], '/media/view/' . $basket['Film']['id']);?> (<?= $app->pluralForm($basket[0]['count'], array(__('file', true), __('filea', true), __('files', true))) ?>, <?= $app->sizeFormat($basket[0]['size']) ?>)</strong>»</span>
            <?= $app->truncateText($basket['Film']['description'], 10) ?>
        </p>
    </div>
</div>
<?php
	}
else:
?>
<p><?php __("For download list you have to"); ?> <a href="/users/register"><?php __("Register"); ?></a>.</p>
<?php
endif;

?>
</div>

<script type="text/javascript">
<!--
$("#baskets").sortable({placeholder: "ui-selected", revert: false });

function download()
{
    $('#BasketElements').val($('#baskets').sortable('toArray'))
}
//-->
</script>
