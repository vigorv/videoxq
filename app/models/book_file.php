<?php
class BookFile extends MediaModel {

    var $name = 'BookFile';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'Book' => array('className' => 'Book',
                                'foreignKey' => 'book_id',
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