<?php
class Book extends MediaModel {

    var $name = 'Book';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasMany = array(
            'BookFile' => array('className' => 'BookFile',
                                'foreignKey' => 'book_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => '',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            )
    );

    var $hasAndBelongsToMany = array(
            'BookGenre' => array('className' => 'BookGenre',
                        'joinTable' => 'book_genres_books',
                        'foreignKey' => 'book_id',
                        'associationForeignKey' => 'book_genre_id',
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
            'BookPublisher' => array('className' => 'BookPublisher',
                        'joinTable' => 'books_book_publishers',
                        'foreignKey' => 'book_id',
                        'associationForeignKey' => 'book_publisher_id',
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
            'Person' => array('className' => 'Person',
                        'joinTable' => 'books_persons',
                        'foreignKey' => 'book_id',
                        'associationForeignKey' => 'person_id',
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