
<div class="MainPage">
    <h3>MainPage</h3>
    <?if (isset($block_films)):?>
    <div class="sv_block"> 
        <?=$block_films;?>
    </div>
    <?endif;?>

    <div class="block_clear">
    <?if(isset($block_left_news)):?>
    <div class="left_45">
        <?=$block_left_news?>
    </div>
    <?endif;?>
    <?if(isset($block_left_news_B)):?>
    <div class="left_45">
        <?=$block_left_news_B?>
    </div>
    <?endif;?>
    
    </div>
    <?if(isset($block_authors)):?>
    <div class="sv_block">
        <?=$block_authors?>
    </div>
    <?endif;?>
</div>
