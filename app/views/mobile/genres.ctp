    <li class="genre_li">
        <a class="href_li" href="/mobile/films"><?= __('All genres',true);?></a>
    </li>
<?

    if (!empty($genres)) :
    foreach ($genres as $genre):?>
    <li class="genre_li"><a class="href_li" href="/mobile/genres/<?=$genre['genres']['id'];?>">
            <div style="float:right; margin-right:10px;">
            <?=$genre['genres']['count'];?>
        </div>
            
    <? 
            if ($lang=='en')
            echo $genre['genres']['title_imdb'];
          else echo $genre['genres']['title'];?></a>
        
    </li>
<?        
    endforeach;
    endif;
?>
    
     <a id="up_button" style="color:black;text-decoration: none;"  href="" onclick=" myScroll.refresh();setTimeout(function() { window.scrollTo(0, 1); }, 100);myScroll.scrollTo(0,0); return false">
    <div class="barA" style="background-color: #CCC">  

        <?= __('Scroll up', true) ?> &uarr;
    </div>