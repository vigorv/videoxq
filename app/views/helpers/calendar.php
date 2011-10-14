<?php
class CalendarHelper extends AppHelper {
    
    public $linksArray;
    
    function _jsCode($content)
	{
		return "<script type=\"text/javascript\">$content</script>";
	}
    function setLinks ($links)
    {
        $linksArray= $links;
    }
    //nastroika formata ssulok naprimer: http://site.ru/events/{%dd}-{%mm}-{%yyyy}
    function LinkFormat()
	{
	   
		return "linkFormat: '{%dd}-{%mm}-{%yyyy}'";
	}
    //nstroika callback function - poka test
    function CallbackFunction()
	{
		return "dateFormat: '{%yyyy}-{%m}-{%d}',
                onSelect: function(date) {
                    alert(date);
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
                $('#calendar').calendarLite(
                {".$this->LinkFormat("123").",
                ".$this->CallbackFunction().",
                ".$this->CalendarStyle()."}
                );
            });");
	}
}
?>