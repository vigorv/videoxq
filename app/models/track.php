<?php
App::import('Model', 'MediaModel');
class Track extends MediaModel {

    var $name = 'Track';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'FilmVariant' => array('className' => 'FilmVariant',
                                'foreignKey' => 'film_variant_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
            'Language' => array('className' => 'Language',
                                'foreignKey' => 'language_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
            'Translation' => array('className' => 'Translation',
                                'foreignKey' => 'translation_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

}
?>