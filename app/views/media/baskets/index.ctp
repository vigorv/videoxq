<?php
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
?>
<div class="listGen">
<p>Здесь вы можете создать список загрузки для программы "Download Master".<br />
Очередь закачек можно отсортировать в нужном порядке, перетаскивая фильмы мышкой.</p>
<?php
if ($authUser['userid']):
?>
<form id="BasketDownloadForm" method="post" action="/basket/download">
<fieldset style="display:none;">
<input type="hidden" name="_method" value="POST" />
</fieldset>
<input type="hidden" name="data[Basket][elements]" value="" id="BasketElements" />
<p><input type="submit" onclick="download();" value="Создать список загрузки" /></p>
<p><?php echo $html->link('Очистить список', array('action' => 'flush'));?></p>
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
            <span>«<strong><?php echo $html->link($basket['Basket']['title'], '/media/view/' . $basket['Film']['id']);?> (<?= $app->pluralForm($basket[0]['count'], array('файл', 'файла', 'файлов')) ?>, <?= $app->sizeFormat($basket[0]['size']) ?>)</strong>»</span>
            <?= $app->truncateText($basket['Film']['description'], 10) ?>
        </p>
    </div>
</div>
<?php
}
else:
?>
<p>Для того, чтобы воспользоваться списком загрузок, Вам необходимо <a href="/users/register">зарегистрироваться</a>.</p>
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
