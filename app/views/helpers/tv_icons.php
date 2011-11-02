<?php
class TvIconsHelper extends AppHelper {
     
    public $icons;
    public function AllIcons()
    {
        $this->icons = 
        array ("left" => array("name" => "left", "title" => "Влево", "path" => "/img/main/left_arrow.png", "id" => "icon_l_arrow", "style" => "", "event" => "" ),
        "right" => array("name" => "right", "title" => "Вправо", "path" => "/img/main/right_arrow.png", "id" => "icon_r_arrow", "style" => "", "event" => "" ),
        "refresh" => array("name" => "refresh", "title" => "Обновить", "path" => "/img/main/refresh.png", "id" => "icon_refresh", "style" => "", "event" => "" ),
        "vid_eskiz" => array("name" => "vid_eskiz", "title" => "Вид отображения: Эскизом", "path" => "/img/main/eskiz.png", "id" => "icon_eskiz", "style" => "", "event" => "onclick='switchOn(this); return saveOption('Profile.itemsView', 'eskiz')';" ),
        "vid_list" => array("name" => "vid_list", "title" => "Вид отображения: Списком", "path" => "/img/main/list.png", "id" => "icon_list", "style" => "", "event" => "onclick='switchOn(this); return saveOption('Profile.itemsView', 'list')';" )
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
            echo "<a href='#' .$this->icons['vid_eskiz']['event'].><img src='".$this->icons['vid_eskiz']['path']."' alt='".$this->icons['vid_eskiz']['title']."' class='".$this->icons['vid_eskiz']['id']."'  /></a>";
        }
        if ($name[$i] == 'vid_list')
        {
            echo "<a href='#' .$this->icons['vid_list']['event'].><img src='".$this->icons['vid_list']['path']."' alt='".$this->icons['vid_list']['title']."' class='".$this->icons['vid_list']['id']."'  /></a>";
        }
        }
}
}
?>