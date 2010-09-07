    <?= $form->create('FilmComment', array('class' => 'leaveComment')) ?>
        <h4>Оставить комментарий</h4>
        <?php // $form->hidden('film_id', array('value' => $Film['id'])) ?>
        <?= $form->error('text', 'Вы не ввели имя', array('wrap' => false)) ?>
        <?= $form->input('username', array('class' => 'textInput', 'label' => 'Имя:', 'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => 'pl'))) ?>
        <?= $form->error('text', 'Вы не ввели email', array('wrap' => false)) ?>
        <?= $form->input('email', array('class' => 'textInput', 'label' => 'E-mail <em>(никто не узнает)</em>:', 'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => ''))) ?>
        <p>
        <?= $form->error('text', 'Вы не ввели текст комментария') ?>
        <?= $form->textarea('text', array('class' => 'textInput')) ?>
        </p>
    <?= $form->end('Сказануть') ?>
