<?php
class TvvisionHelper extends AppHelper {
    
    //eskiz
    public function eskiz ($film_info)
	{  
	   if (is_array($film_info) and !empty($film_info))
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
        <p>'.$film_info[$i]["director"].'</p></p>';
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
    else
    {
        echo "<p>Список пуст!</p>";
    }
  }
    //list
    public function list_view ($film_info)
	{  
	   if (is_array($film_info) and !empty($film_info))
       {
	   //ya4eika filma v televisore
       echo '<div class="movies_list">';
       echo '<div class="movies_l"><ol>';
       for ($i = 0; $i < sizeof($film_info)/2;$i++)
       {
		echo '
        <p class="text">
        <li style="float:left; margin:0; padding:0;">
        <div class="moviePreviewWrapper_list">
        <span><a href="/media/view/'.$film_info[$i]["id"].'">'.$film_info[$i]["film_name_rus"].'
         / '.$film_info[$i]["film_name_org"].'
         / '.$film_info[$i]["year"].'</a></span></li></p></div>';
	   }
       echo '</ol></div>';
       echo '<div class="movies_r"><ol start="'.((sizeof($film_info)/2)+1).'">';
       for ($i = (sizeof($film_info)/2)+1; $i < sizeof($film_info);$i++)
       {
		echo '
        <p class="text">
        <li style="float:left; margin:0; padding:0;">
        <div class="moviePreviewWrapper_list">
        <span><a href="/media/view/'.$film_info[$i]["id"].'">'.$film_info[$i]["film_name_rus"].'
         / '.$film_info[$i]["film_name_org"].'
         / '.$film_info[$i]["year"].'</a></span></li></p></div>';
	   }
       echo '</ol></div>';
       echo '</div>';
    }
    else
    {
        echo "<p>Список пуст!</p>";
    }
  }
}
?>