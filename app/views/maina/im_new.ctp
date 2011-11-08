<style>
input, textarea{
    padding: 8px 5px 8px 5px;
    margin: 5px 2px 5px 2px;
    background: #f4f4f4;
    border: 1px solid #74ADE7;
    text-decoration: none;
    white-space: nowrap;
}

fieldset{
    padding: 8px;
    margin: 5px 2px 5px 2px;
    border: 1px solid #74ADE7;
    text-decoration: none;
    font-size: 12px;
}

input[type="submit"] {
    display: inline;
    padding: 5px 5px;
    margin:0;    
    cursor: pointer;
}

input:hover, textarea:hover{
    background: #ddd;
    
}

form div.submit {
    border: 0 none;
    clear: both;
    margin-top: 10px;
    margin-left: 2px;
}

form label {
    display: block;
    float: left;
    padding: 10px 10px 10px 0;
    width: 100px;
}    
</style>
<?php 
        //echo $form->create(null, array('url' => array('controller' => 'maina', 'action' => '', 'enctype' => 'multipart/form-data')));
        echo '<form id="im_new" action="/maina/im/new" method="post">';
?>
</div>
<div id="ins_ajax">
<?//выводим меню для сообщений (водящие / исходящие / новое и т.п.)?>
<?=(!$isAjax)? ($this->element($blocks_m_im)).'<div id="ins_ajax">':'';?>  
<?php if ($session->check('Message.flash'))$session->flash();?>
    <fieldset>
         <legend>Новое сообщение</legend>
    <?php
        echo $form->input('to_user_name', array('label' => 'Кому', 'name' => 'to_user_name', 'style' => 'width:550px', 'size' => 75, 'value' => (!empty($data['to_user_name']) ? $data['to_user_name'] : '')));
        echo $form->input('title', array('label' => 'Тема', 'name' => 'title', 'style' => 'width:550px', 'size' => 75,'value' => (!empty($data['title']) ? $data['title'] : '')));
        echo $form->input('msg', array('label' => 'Сообщение', 'name' => 'msg', 'style' => 'width:550px','type' => 'textarea','rows' => '5', 'cols' => '58', 'value' => (!empty($data['msg']) ? $data['msg'] : '')));
    ?>
    </fieldset>
<?php echo $form->end('Отправить');?>
</div>
<script language="javascript">
    subact='<?=$sub_act;?>';
    $('#im_menu_act').fadeOut();
   if ($('#flashMessage').length > 0 ){
       var wp = $('#flashMessage').parent().width();
       var wm = $('#flashMessage').width();
       var xm = (wp/2 - wm/2) - 25 ;
       $('#flashMessage').css('left', xm+'px').show();
       $('#flashMessage').fadeOut(8000);
   }   
   
   
   
   
$(document).ready(function(){

  var options = { 
    target: "#ins_ajax", 
    beforeSubmit: showAjaxLoader,
    success: showResponse, 
    timeout: 3000000 
  };

  $('#im_new').submit(function() { 
    $(this).ajaxSubmit(options); 
    return false;
  }); 

});


function showAjaxLoader(formData, jqForm, options) { 
    $('#ins_ajax').fadeOut(555, function(){
        $(this).html('<img id="ajax_loader_icon" src="/img/ajax-loader.gif">');
        x = x + ($('#ajax_loader_icon').width())/2;
        y = y + ($('#ajax_loader_icon').height())/2;
        $('#ajax_loader_icon').attr("style","display: block; position: absolute; left: "+x+"px; top:"+y+"px");
        $(this).fadeIn(555);
    });    
    return true; 
} 
 
function showResponse(responseText, statusText)  { 
    $('#im_menu_nav a').removeClass("current");
    $(this).addClass("current");
    
    $('#im_menu_act').fadeIn();
    $('#ins_ajax').html(responseText);
}   
</script>
<?=(!$isAjax)? '</div>':'';?>
