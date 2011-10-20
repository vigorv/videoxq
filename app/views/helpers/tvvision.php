<?php
class TvvisionHelper extends AppHelper {
    
    
    public function eskiz ($film_info)
	{
	   //ya4eika filma v televisore
		echo '<div class="movies">
        <div class="moviePreviewWrapper">
        <div class="poster"><a href="/media/view/'.$film_info[0]["id"].'"><img src="'.$film_info[0]["poster"].'" width="80" /></a>
        <div class="knopki">
        <a href="#"><img src="/img/main/facebook.png" style="padding-left:5px;" /></a>
        <a href="#"><img src="/img/main/twitter.png" /></a>
        <a href="#"><img src="/img/main/vkontakte.png" /></a>
        </div></div>
        <p class="text">
        <span>«<a href="/media/view/'.$film_info[0]["id"].'">'.$film_info[0]["film_name_rus"].'</a>»</span>
        <p>'.$film_info[0]["film_name_org"].'</p>
        <p>'.$film_info[0]["year"].'</p>
        <p>'.$film_info[0]["director"].'</p>
        <p>'.$film_info[0]["actors"][0].',</p>
        <p>'.$film_info[0]["actors"][1].',</p>
        <p>'.$film_info[0]["actors"][2].',</p>
        <p>'.$film_info[0]["actors"][3].'.</p>
        </div></div>';
	}
}
?>