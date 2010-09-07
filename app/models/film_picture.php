<?php
App::import('Model', 'MediaModel');
class FilmPicture extends MediaModel {

    var $name = 'FilmPicture';
    
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'Film' => array('className' => 'Film',
                                'foreignKey' => 'film_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

}
?>