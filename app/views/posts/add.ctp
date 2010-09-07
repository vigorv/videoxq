<div class="contentCol">
    <?php
    $javascript->link('jquery.bgiframe.min', false);
    $javascript->link('jquery.ajaxQueue', false);
    $javascript->link('jquery.autocomplete.pack', false);
    $script = '$(document).ready(function() { $("#PostTags").autocomplete(\'/tags/ajax_taglist\', properties = {
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
    echo $form->create('Post', array('class' => 'addPost'));
    echo $form->input('title', array('class' => 'textInput', 'label' => 'Заголовок:',
                                 'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));
    echo $form->input('text', array('class' => 'textInput', 'label' => 'Текст поста:',
                                 'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));

    ?>
        <div class="options">
<?php
    //echo '<label for="TagTag">Теги:</label><br><ul class="facelist"><li class="token-input">';
    //echo $form->hidden('Tag');
    echo $form->input('tags', array('class' => 'textInput', 'label' => 'Теги:', 'type' => 'text', 'autocomplete' => 'off',
                                 'between' => '', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));
    //echo $form->text('Tmp', array('class' => 'textInput'));
    //echo '</li></ul><div id="result_list" style="display:none;"></div>';
    echo $form->input('access', array('class' => 'textInput', 'label' => 'Видимость:',
                                 'between' => '', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));

?>
            <p><label for="post_to">Запостить в:</label><select class="textInput" id="post_to"><option>личный бложек</option></select></p>
        </div>
        <input type="submit" class="button" value="Создать">
    </form>
</div>
