<?php
class AudioBook extends MediaModel {

    var $name = 'AudioBook';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasMany = array(
            'AudioBookFile' => array('className' => 'AudioBookFile',
                                'foreignKey' => 'audio_book_id',
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
                        'joinTable' => 'audio_books_book_genres',
                        'foreignKey' => 'audio_book_id',
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
                        'joinTable' => 'audio_books_book_publishers',
                        'foreignKey' => 'audio_book_id',
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
                        'joinTable' => 'audio_books_persons',
                        'foreignKey' => 'audio_book_id',
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