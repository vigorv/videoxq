<div class="adm_content">
<h2><?php __($this->name);?></h2>
<br/>


<?php
$html->css('adm_peoples','',array(),false);
$javascript->link('adm_peoples.js', false);

?>
<div class="doCategories form">
<?php echo $form->create('People', array('enctype' => 'multipart/form-data'));?>
    <fieldset>
         <legend><?php __('Изменить запись');?></legend>
    <?php
        echo $form->input('People.id', array('type' => 'hidden', 'default'=> (!empty($People['id'])? $People['id'] : ''), 'label' => 'Имя'));
        echo $form->input('People.name', array('default'=> (!empty($People['name'])? $People['name'] : ''), 'label' => 'Имя'));
        echo $form->input('People.name_en', array('default'=> (!empty($People['name_en'])? $People['name_en'] : ''), 'label' => 'Имя (англ.)'));
        echo $form->input('People.description', array('default'=> (!empty($People['description'])? $People['description'] : ''), 'label' => 'Описание','type' => 'textarea','rows' => '10', 'cols' => '5'));
    ?>
    </fieldset>
    <br/>
<?php echo $form->end('Применить');?>
</div>

<?php 
    echo '<br/>';
    echo $html->link('Вернуться назад к списку', array('action'=>'index'),array('class'=>'a_btn','style'=>'display: block; clear: both'));    
?>

<div class="ch_btm_menu">

</div>
<div id="logo"></div>
</div>