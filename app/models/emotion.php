<?php
class Emotion extends MediaModel {

    var $name = 'Emotion';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasAndBelongsToMany = array(
            'Film' => array('className' => 'Film',
                        'joinTable' => 'emotions_films',
                        'foreignKey' => 'emotion_id',
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
?>