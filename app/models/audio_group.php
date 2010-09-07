<?php
class AudioGroup extends MediaModel {

    var $name = 'AudioGroup';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasAndBelongsToMany = array(
            'AudioGenre' => array('className' => 'AudioGenre',
                        'joinTable' => 'audio_genres_audio_groups',
                        'foreignKey' => 'audio_group_id',
                        'associationForeignKey' => 'audio_genre_id',
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
                        'joinTable' => 'audio_groups_persons',
                        'foreignKey' => 'audio_group_id',
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