<?php
class BookGenre extends MediaModel {

    var $name = 'BookGenre';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasAndBelongsToMany = array(
            'AudioBook' => array('className' => 'AudioBook',
                        'joinTable' => 'audio_books_book_genres',
                        'foreignKey' => 'book_genre_id',
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
                        'joinTable' => 'book_genres_books',
                        'foreignKey' => 'book_genre_id',
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