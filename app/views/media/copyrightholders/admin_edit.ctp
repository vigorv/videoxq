<div class="ch_content">
<h2>"Правообладатели"</h2>
<div class="ch_top_menu">
<a href="/admin/copyrightholders">Список правообладателей</a>
<a href="/admin/copyrightholders/films">Связи "Фильмы" <-> "Правообладатели"</a>
<a href="/admin/copyrightholders/import">Импорт из файла Excel (*.xls)</a>
</div>

<br/>


<?php
$html->css('adm_copyrightholders','',array(),false);
$javascript->link('adm_copyrightholders.js', false);



?>
<div class="doCategories form">
<?php echo $form->create('Copyrightholder', array('enctype' => 'multipart/form-data'));?>
    <fieldset>
         <legend><?php __('Copyrightholder');?></legend>
    <?php
        echo $form->input('Copyrightholder.id');
        echo $form->input('Copyrightholder.hidden', array('type'=>'checkbox', 'label' => 'Скрытый'));
        echo $form->input('Copyrightholder.name', array('label' => 'Название'));
        echo $form->input('Copyrightholder.name_en', array('label' => 'Название (англ.)'));
        echo $form->input('Copyrightholder.description', array('label' => 'Описание','type' => 'textarea','rows' => '5', 'cols' => '5'));
        if (!empty($this->data['CopyrightholdersPicture'][0]['id'])){
            $file_name = $this->data['CopyrightholdersPicture'][0]['file_name'];
            echo '<img src="/img/'. $file_name .'" style="margin: 5px; float: left"/>';
            echo $file_name;
            echo $form->input('CopyrightholdersPicture.0.id',array('type' => 'hidden'));
        }

        echo $form->input('CopyrightholdersPicture.0.file_name', array('label' => 'Фото', 'type'=>'file'));

    ?>
    </fieldset>
<?php echo $form->end('Применить');?>
</div>

<?php


?>

<div class="ch_btm_menu">

</div>
<div id="logo"></div>
</div>