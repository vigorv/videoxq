<?php
class CalendarHelper extends AppHelper {
    
    
    function _jsCode($content)
	{
		return "<script type=\"text/javascript\">$content</script>";
	}
    public function _jsCode_array($days)
    {   
        $count_days = sizeof($days);
        echo "<script type=\"text/javascript\">var days_array = [];";
        for ($i=0;$i < $count_days;$i++)
        {
            $days2 = explode("-",$days[$i]);
            echo "days_array[$i]= '$days[$i]';";
        }
        echo "var count_days = $count_days;</script>";
    }
    
    //function setLinks ($links)
    //{
    //    $linksArray= $links;
    //}
    //nastroika formata ssulok naprimer: http://site.ru/events/{%dd}-{%mm}-{%yyyy}
    function LinkFormat()
	{
	   
		return "linkFormat: 'news/index/{%yyyy}-{%mm}-{%dd}'";
	}
    //nstroika callback function - poka test
    function CallbackFunction()
	{
		return "dateFormat: '{%yyyy}-{%m}-{%d}',
                onClick: function() {
                    return false;
                    }";
	}
    //vneshnui vid kalendarya
    function CalendarStyle()
	{
		return "showYear: true,
                prevArrow: '<---',
                nextArrow: '--->'";
	}
    public function ShowCalendar()
	{
		echo $this->_jsCode("$(document).ready(function(){
                $('#calendarlite').calendarLite(
                {".$this->LinkFormat().",
                ".$this->CallbackFunction().",
                ".$this->CalendarStyle()."}
                );
            });");
	}
}
?>