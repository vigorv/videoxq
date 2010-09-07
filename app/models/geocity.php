<?php
App::import('Model', 'MediaModel');
/**
 * Модель справочника городов
 *
 */
class Geocity extends MediaModel {
	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Geocity';

    public $hasAndBelongsToMany = array(
            'Film' => array('className' => 'Film',
                        'joinTable' => 'films_geocities',
                        'foreignKey' => 'geocity_id',
                        'associationForeignKey' => 'film_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            )
    );
}
