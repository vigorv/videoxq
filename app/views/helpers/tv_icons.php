<?php
class TvIconsHelper extends AppHelper {

    public $icons;
    public function AllIcons()
    {
        $this->icons =
        array ("left" => array("name" => "left", "title" => "Влево", "path" => "/img/main/left_arrow.png", "class" => "icon_l_arrow", "style" => "", "event" => "" ),
        "right" => array("name" => "right", "title" => "Вправо", "path" => "/img/main/right_arrow.png", "class" => "icon_r_arrow", "style" => "", "event" => "" ),
        "refresh" => array("name" => "refresh", "title" => "Обновить", "path" => "/img/main/refresh.png", "class" => "icon_refresh", "style" => "", "event" => "" ),
        "vid_eskiz" => array("name" => "vid_eskiz", "title" => "Вид отображения: Эскизом", "path" => "/img/main/eskiz.png", "class" => "icon_eskiz", "style" => "", "event" => "onclick='switchOn(this); return saveOption(\"Profile.itemsView\", \"eskiz\");'" ),
        "vid_list" => array("name" => "vid_list", "title" => "Вид отображения: Списком", "path" => "/img/main/list.png", "class" => "icon_list", "style" => "", "event" => "onclick='switchOn(this); return saveOption(\"Profile.itemsView\", \"list\");'" ),
        "number_6" => array("name" => "number_6", "title" => "Вид отображения: По шесть", "path" => "/img/main/number_6.png", "class" => "icon_number6", "style" => "", "event" => "onclick='switchDigitOn(this); return saveOption(\"Profile.itemsPerPage\", \"6\");'"),
        "number_9" => array("name" => "number_9", "title" => "Вид отображения: По девять", "path" => "/img/main/number_9.png", "class" => "icon_number9", "style" => "", "event" => "onclick='switchDigitOn(this); return saveOption(\"Profile.itemsPerPage\", \"9\");'"),
        "number_12" => array("name" => "number_12", "title" => "Вид отображения: По двенадцать", "path" => "/img/main/number_12.png", "class" => "icon_number12", "style" => "", "event" => "onclick='switchDigitOn(this); return saveOption(\"Profile.itemsPerPage\", \"12\");'"),
        "number_24" => array("name" => "number_24", "title" => "Вид отображения: По двадцать четыре", "path" => "/img/main/number_24.png", "class" => "icon_number24", "style" => "", "event" => "onclick='switchDigitOn(this); return saveOption(\"Profile.itemsPerPage\", \"24\");'")
        );
    }

    public function IconsShow($name) {

        for ($i = 0; $i < sizeof($name);$i++)
       {
		if ($name[$i] != '')
        {
            echo "<a href='#' title='".$this->icons[$name[$i]]['title']."' ".$this->icons[$name[$i]]['event']."><img src='".$this->icons[$name[$i]]['path']."' alt='".$this->icons[$name[$i]]['title']."' class='".$this->icons[$name[$i]]['class']."' style='".$this->icons[$name[$i]]['style']."' /></a>";
        }
        }
}
}
?>