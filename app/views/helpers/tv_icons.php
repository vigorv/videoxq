<?php
class TvIconsHelper extends AppHelper {
     
    public $icons;
    public function AllIcons()
    {
        $this->icons = 
        array ("left" => array("name" => "left", "title" => "Влево", "path" => "/img/main/left_arrow.png", "id" => "icon_l_arrow", "style" => "", "event" => "" ),
        "right" => array("name" => "right", "title" => "Вправо", "path" => "/img/main/right_arrow.png", "id" => "icon_r_arrow", "style" => "", "event" => "" ),
        "refresh" => array("name" => "refresh", "title" => "Обновить", "path" => "/img/main/refresh.png", "id" => "icon_refresh", "style" => "", "event" => "" ),
        "vid_eskiz" => array("name" => "vid_eskiz", "title" => "Вид отображения: Эскизом", "path" => "/img/main/eskiz.png", "id" => "icon_eskiz", "style" => "", "event" => "onclick='switchOn(this); return saveOption(\"Profile.itemsView\", \"eskiz\");'" ),
        "vid_list" => array("name" => "vid_list", "title" => "Вид отображения: Списком", "path" => "/img/main/list.png", "id" => "icon_list", "style" => "", "event" => "onclick='switchOn(this); return saveOption(\"Profile.itemsView\", \"list\");'" ),
        "number_6" => array("name" => "number_6", "title" => "Вид отображения: По шесть", "path" => "/img/main/number_6.png", "id" => "icon_number6", "style" => "", "event" => "onclick='switchOn(this); return saveOption(\"Profile.itemsPerPage\", \"6\");'"),
        "number_9" => array("name" => "number_9", "title" => "Вид отображения: По девять", "path" => "/img/main/number_9.png", "id" => "icon_number9", "style" => "", "event" => "onclick='switchOn(this); return saveOption(\"Profile.itemsPerPage\", \"9\");'"),
        "number_12" => array("name" => "number_12", "title" => "Вид отображения: По двенадцать", "path" => "/img/main/number_12.png", "id" => "icon_number12", "style" => "", "event" => "onclick='switchOn(this); return saveOption(\"Profile.itemsPerPage\", \"12\");'")
        );
    }
    
    public function IconsShow($name) {
        
        for ($i = 0; $i < sizeof($name);$i++)
       {
		if ($name[$i] == 'left')
        {
            echo "<a href='#'><img src='".$this->icons['left']['path']."' alt='".$this->icons['left']['title']."' id='".$this->icons['left']['id']."'  /></a>";
        }
        if ($name[$i] == 'right')
        {
            echo "<a href='#'><img src='".$this->icons['right']['path']."' alt='".$this->icons['right']['title']."' id='".$this->icons['right']['id']."'  /></a>";
        }
        if ($name[$i] == 'refresh')
        {
           echo "<a href='#'><img src='".$this->icons['refresh']['path']."' alt='".$this->icons['refresh']['title']."' id='".$this->icons['refresh']['id']."'  /></a>";
        }
        if ($name[$i] == 'vid_eskiz')
        {
            echo "<a href='#' ".$this->icons['vid_eskiz']['event']."><img src='".$this->icons['vid_eskiz']['path']."' alt='".$this->icons['vid_eskiz']['title']."' class='".$this->icons['vid_eskiz']['id']."'  /></a>";
        }
        if ($name[$i] == 'vid_list')
        {
            echo "<a href='#' ".$this->icons['vid_list']['event']."><img src='".$this->icons['vid_list']['path']."' alt='".$this->icons['vid_list']['title']."' class='".$this->icons['vid_list']['id']."'  /></a>";
        }
        if ($name[$i] == 'number_3')
        {
            echo "<a href='#' ".$this->icons['number_3']['event']."><img src='".$this->icons['number_3']['path']."' alt='".$this->icons['number_3']['title']."' class='".$this->icons['number_3']['id']."'  /></a>";
        }
        if ($name[$i] == 'number_6')
        {
            echo "<a href='#' ".$this->icons['number_6']['event']."><img src='".$this->icons['number_6']['path']."' alt='".$this->icons['number_6']['title']."' class='".$this->icons['number_6']['id']."'  /></a>";
        }
        if ($name[$i] == 'number_9')
        {
            echo "<a href='#' ".$this->icons['number_9']['event']."><img src='".$this->icons['number_9']['path']."' alt='".$this->icons['number_9']['title']."' class='".$this->icons['number_9']['id']."'  /></a>";
        }
        if ($name[$i] == 'number_12')
        {
            echo "<a href='#' ".$this->icons['number_12']['event']."><img src='".$this->icons['number_12']['path']."' alt='".$this->icons['number_12']['title']."' class='".$this->icons['number_12']['id']."'  /></a>";
        }
        }
}
}
?>