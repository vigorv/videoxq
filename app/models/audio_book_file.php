<?php
class AudioBookFile extends MediaModel {

    var $name = 'AudioBookFile';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'AudioBook' => array('className' => 'AudioBook',
                                'foreignKey' => 'audio_book_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

    var $hasOne = array(
            'Language' => array('className' => 'Language',
                                'foreignKey' => 'id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

}
?>