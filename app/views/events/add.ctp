 <div class="contentCol">
    <?php
    $javascript->link('jquery.bgiframe.min', false);
    $javascript->link('jquery.ajaxQueue', false);
    $javascript->link('jquery.autocomplete.pack', false);
    $script = '$(document).ready(function() { $("#EventTags").autocomplete(\'/tags/ajax_taglist\', properties = {
                                                                            matchContains: true,
                                                                            minChars: 1,
                                                                            selectFirst: true,
                                                                            intro_text: "Введите тэги",
                                                                            no_result: "Не найдено",
                                                                            result_field: "data[Tag][Tag]",
                                                                            width: 264,
                                                                            multiple: true
                                                                        });

                                                });';
    $javascript->codeBlock($script, array('inline' => false), false);
    $javascript->link('tiny_mce/tiny_mce', false);
    $javascript->link('mce_blogs', false);


    $html->css('jquery.autocomplete', null, array(), false);
    ?>
  <h2><a href="/news">Новости</a> / Добавление</h2>
        <div>
<?php
echo $form->create('Event', array('type' => 'file','class' => "createEvent"));?>
	<?php
	    echo $form->error('title');
        echo $form->input('title', array('class' => 'textInput', 'label' => 'Название:',
                                 'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));
        echo $form->input('news_category_id', array('class' => 'textInput', 'label' => 'Категория:',
                                 'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));
	    echo $form->error('notice');
        echo $form->input('notice', array('class' => 'textInput', 'label' => 'Краткое описание:',
                                 'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));
	    echo $form->error('text');
	    
        echo $form->input('text', array('class' => 'textInput', 'label' => 'Сама новость:',
                                 'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));
        echo $form->error('filename');
        echo $form->input('filename', array('class' => 'textInput', 'label' => false, 'type' => 'file', 'id' => 'ImageUpload',
                                 'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));
    echo $form->input('tags', array('class' => 'textInput', 'label' => 'Теги:', 'type' => 'text', 'autocomplete' => 'off',
                                 'between' => '', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));
        
        ?>
<?php //echo $this->element('attachment');
?>
<?php echo $form->end('Готово');?>
</div>
</div>
