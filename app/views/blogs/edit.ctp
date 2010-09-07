<div class="contentCol">
    <?php
    echo $form->input('id');
    echo $form->create('Blog', array('class' => 'addPost'));
    echo $form->input('title', array('class' => 'textInput', 'label' => 'Название:',
                                 'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));
    echo $form->input('description', array('class' => 'textInput', 'label' => 'Описание:',
                                 'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));

    ?>
        <input type="submit" class="button" value="Создать">
    </form>
</div>
