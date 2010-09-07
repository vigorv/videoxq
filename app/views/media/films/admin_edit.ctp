<div class="films form">
<?php
//@TODO: Было без массива, добавил эту дрянь временно чотбы разрулить
echo $form->create('Film',array('url'=>'edit/'.$form->value('Film.id')))
;?>
    <fieldset>
         <legend><?php __('Edit Film');?></legend>
    <?php
        echo $form->input('id');
        echo $form->input('film_type_id');
        echo $form->input('title');
        echo $form->input('title_en');
        echo $form->input('description');
        echo $form->input('active');
        echo $form->input('is_license');
        echo $form->input('oscar');
        echo $form->input('year');
        echo $form->input('dir');
        echo $form->input('hits');
        echo $form->input('imdb_rating');
        echo $form->input('imdb_id');
        echo $form->input('imdb_votes');
        echo $form->input('imdb_date', array('timeFormat' => 24));
        echo $form->input('created', array('timeFormat' => 24));
        echo $form->input('modified', array('timeFormat' => 24));
        echo $form->input('Country');
        echo $form->input('Genre');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Film.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Film.id'))); ?></li>
        <li><?php echo $html->link(__('List Films', true), array('action'=>'index'));?></li>
    </ul>
</div>
