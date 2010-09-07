<?php
class BookPublisher extends MediaModel {

    var $name = 'BookPublisher';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasAndBelongsToMany = array(
            'AudioBook' => array('className' => 'AudioBook',
                        'joinTable' => 'audio_books_book_publishers',
                        'foreignKey' => 'book_publisher_id',
                        'associationForeignKey' => 'audio_book_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            ),
            'Book' => array('className' => 'Book',
                        'joinTable' => 'books_book_publishers',
                        'foreignKey' => 'book_publisher_id',
                        'associationForeignKey' => 'book_id',
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