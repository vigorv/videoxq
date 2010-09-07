<?php
App::import('Model', 'MediaModel');
/**
 * Модель справочника регионов
 *
 */
class Georegion extends MediaModel {
	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Georegion';

    public $hasAndBelongsToMany = array(
            'Film' => array('className' => 'Film',
                        'joinTable' => 'films_georegions',
                        'foreignKey' => 'georegion_id',
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
