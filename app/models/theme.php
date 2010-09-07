<?php
class Theme extends MediaModel {

    var $name = 'Theme';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasAndBelongsToMany = array(
            'Film' => array('className' => 'Film',
                        'joinTable' => 'films_themes',
                        'foreignKey' => 'theme_id',
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
