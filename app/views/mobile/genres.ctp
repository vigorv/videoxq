    <li>
        <a class="href_li" href="/mobile/films">Любой жанр</a>
    </li>
<?

    if (!empty($genres)) :
    foreach ($genres as $genre):?>
    <li><a class="href_li" href="/mobile/genres/<?=$genre['genres']['id'];?>">
            <div style="float:right">
            <?=$genre['genres']['count'];?>
        </div>
    <? if ($lang=='ENG')
            echo $genre['genres']['title_imdb'];
          else echo $genre['genres']['title'];?></a>
        
    </li>
<?        
    endforeach;
    endif;
?>