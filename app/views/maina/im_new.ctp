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
    color: #d00;
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
        echo '<form action="/maina/im/new" method=post enctype="multipart/form-data">';
?>
<div id="im_in_menu">
<ul>
			<li><a href="#" style="border-left: 1px solid #74ADE7;">Входящие</a></li>
			<li><a href="#">Исходящие</a></li>
			<li><a href="#" id="current">Написать сообщение</a></li>
            <li style="float:right;border-left: 1px solid #74ADE7;margin-right: 1px;"><a href="#">Удалить</a></li>
</ul>
</div>
    <fieldset>
         <legend>Новое сообщение</legend>
    <?php
        echo $form->input('to_user_name', array('label' => 'Кому', 'name' => 'to_user_name', 'size' => 75, 'value' => (!empty($data['to_user_name']) ? $data['to_user_name'] : '')));
        echo $form->input('title', array('label' => 'Тема', 'name' => 'title', 'size' => 75,'value' => (!empty($data['title']) ? $data['title'] : '')));
        echo $form->input('msg', array('label' => 'Сообщение', 'name' => 'msg','type' => 'textarea','rows' => '5', 'cols' => '58', 'value' => (!empty($data['msg']) ? $data['msg'] : '')));
    ?>
    </fieldset>
<?php echo $form->end('Отправить');?>