<?php
App::import('Model', 'MediaModel');
/**
 * модель таблицы для сбора статистики по скачанным фильмам
 *
 */
class FilmClick extends MediaModel
{

    var $name = 'FilmClick';

    var $belongsTo = array(
            'Film' => array('className' => 'Film',
                                'foreignKey' => 'film_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

}