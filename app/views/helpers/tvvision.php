<?php
class TvvisionHelper extends AppHelper {
    
    
    public function Eskiz ($film_info)
	{
	   //ya4eika filma v televisore
		echo '<div class="moviePreviewWrapper">
        <div class="poster"><img src="'.$film_info[0]["poster"].'" />
        <div class="knopki">
        <img src="/img/main/facebook.png" />
        <img src="/img/main/twitter.png" />
        <img src="/img/main/vkontakte.png" />
        </div></div>
        <div class="text"><p class="text">
        <span>«<a href="/media/view/'.$film_info[0]["id"].'">'.$film_info[0]["film_name_rus"].'</a>»</span>
        <p>'.$film_info[0]["film_name_org"].'</p>
        <p>'.$film_info[0]["year"].'</p>
        </div></div>';
	}
}
?>