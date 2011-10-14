<?php
class AutocompleteHelper extends AppHelper {
    var $helpers = array('Javascript');
    var $output ='';

    /*
     * $idInput = ID поля <input >c текстом по которому нужно сделать автозаполнение
     * $modelSearch = Модель/Поле для автозаполнения
     * $other = массив с полями
     * $numResult = количество возвращаемы результатов (limit)
     * $strlen = количество символов, после которого начинать искать
     */
    function autocomplete($idInput,$modelSearch,$other=null,$numResult=10,$strlen=1) {
        $fields= "";
        $setBody = "";
        $search = explode("/",$modelSearch);
        if (is_array($other)) {
            foreach ($other As $k => $v) {
                $fields .= $v.',';
                $setBody .= "$('#".$k."').val(".$v.");";
            }
        }
        $fields .= $search[1];

        $this->output.=$this->Javascript->codeBlock('
            $("#'.$idInput.'").ready(function(){
//--                $("#'.$idInput.'").attr("onkeyup","query_'.$idInput.'(this.value)");
                $("#'.$idInput.'").keypress(function (e) {
                    txt = $("#'.$idInput.'").val()+String.fromCharCode(e.which);//current value + the new character
                    query_'.$idInput.'(txt, e.which);
                });
                $("#'.$idInput.'").attr("autocomplete","off");
                $("#'.$idInput.'").after("<span id=\"span_'.$idInput.'\" class=\"autocomplete_live\"></span>");
            });

//--            function query_'.$idInput.'(txt) {
            function query_'.$idInput.'(txt,code) {

                if(txt.length >= '.$strlen.') {
                    $.post("'.$this->webroot.$this->params["controller"].'/autocomplete", {query: txt, fields: "'.$fields.'", search: "'.$search[1].'", model: "'.$search[0].'", numresult: "'.$numResult.'", rand: "'.$idInput.'"}, function(data){
                        if(data != ""){
                        $("#span_'.$idInput.'").html("<ul id=\'ul_'.$idInput.'\' class=\'autocomplete_live\'>"+data+"</ul>");
                        $("#ul_'.$idInput.'").width($("#'.$idInput.'").width());
                        $("#span_'.$idInput.'>ul>li>a").keypress(function(e) {
//--                            pressedKey = e.charCode || e.keyCode || -1;
                            pressedKey = code || -1;
                            switch(pressedKey) {
                                case 38://up
                                    position=position-1;
                                    if (position<0) {
                                        position=dimensione-1;
                                    }
                                    $("#span_'.$idInput.'>ul>li>a").eq(position).focus();
                                    return false;
                                break;

                                case 40://down
                                    position=position+1;
                                    if (position>=dimensione) {position=0;}
                                        $("#span_'.$idInput.'>ul>li>a").eq(position).focus();
                                        return false;
                                    break;
                            }
                        });
                        }
                        else{
                            $("#span_'.$idInput.'").html("");
                        }
                    });
                }
                else{
                    $("#span_'.$idInput.'").html("");
                }
            }

            $("#'.$idInput.'").keypress(function(e) {
                pressedKey = e.charCode || e.keyCode || -1;
                dimensione=$("#span_'.$idInput.'>ul>li").size();
                switch(pressedKey) {
                    case 38://up
                        $("#span_'.$idInput.'>ul>li>a").eq($("#span_'.$idInput.'>ul>li").size()-1).focus();
                        position = $("#span_'.$idInput.'>ul>li").size()-1;
                    break;

                    case 40://down
                        $("#span_'.$idInput.'>ul>li>a").eq(0).focus();
                        position=0;
                    break;
                    case 8://BackSpace

                    break;
                }
            });

            function set_'.$idInput.'('.$fields.') {
                '.$setBody.'
                $("#'.$idInput.'").val('.$search[1].');
                $("#span_'.$idInput.'").html("");
            }
        ');
        return $this->output;
    }
}
?>