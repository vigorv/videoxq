<?php
class TvvisionHelper extends AppHelper {
    
    //eskiz
    public function eskiz ($film_info)
	{  
	   //ya4eika filma v televisore
       echo '<div class="movies">';
       for ($i = 0; $i < sizeof($film_info);$i++)
       {
		echo '
        <div class="moviePreviewWrapper">
        <div class="poster"><a href="/media/view/'.$film_info[$i]["id"].'"><img src="'.$film_info[$i]["poster"].'" width="80" /></a>
        <div class="knopki">
        <a href="#"><img src="/img/main/facebook.png" style="padding-left:5px;" /></a>
        <a href="#"><img src="/img/main/twitter.png" /></a>
        <a href="#"><img src="/img/main/vkontakte.png" /></a>
        </div></div>
        <p class="text">
        <span>«<a href="/media/view/'.$film_info[$i]["id"].'">'.$film_info[$i]["film_name_rus"].'</a>»</span>
        <p>'.$film_info[$i]["film_name_org"].'</p>
        <p>'.$film_info[$i]["year"].'</p>
        <p>'.$film_info[$i]["director"].'</p>';
        $z='';
        for ($j = 0; $j < sizeof($film_info[$i]["actors"]);$j++)
        {
        echo $z.$film_info[$i]["actors"][$j];
        $z=', ';
        }
        echo '.</div>';
	   }
       echo '</div>';
    }
    //list
    public function list_view ($film_info)
	{  
	   //ya4eika filma v televisore
       echo '<div class="movies">';
       for ($i = 0; $i < sizeof($film_info);$i++)
       {
		echo '
        <div class="moviePreviewWrapper_list">
        <p class="text">
        <span style="text-align:center">«<a href="/media/view/'.$film_info[$i]["id"].'">'.$film_info[$i]["film_name_rus"].'</a>»</span>
        <p><span style="text-align:center">'.$film_info[$i]["film_name_org"].'</span></p>
        <p><span style="text-align:center">'.$film_info[$i]["year"].'</span></p>
        <p>'.$film_info[$i]["director"].'</p>';
        $z='';
        for ($j = 0; $j < sizeof($film_info[$i]["actors"]);$j++)
        {
        echo $z.$film_info[$i]["actors"][$j];
        $z=', ';
        }
        echo '.</div>';
	   }
       echo '</div>';
    }
}
?>