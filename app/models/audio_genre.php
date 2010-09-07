<?php
App::import('Model', 'MediaModel');
class AudioGenre extends MediaModel {

    var $name = 'AudioGenre';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasAndBelongsToMany = array(
            'AudioGroup' => array('className' => 'AudioGroup',
                        'joinTable' => 'audio_genres_audio_groups',
                        'foreignKey' => 'audio_genre_id',
                        'associationForeignKey' => 'audio_group_id',
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
            'AudioTrack' => array('className' => 'AudioTrack',
                        'joinTable' => 'audio_genres_audio_tracks',
                        'foreignKey' => 'audio_genre_id',
                        'associationForeignKey' => 'audio_track_id',
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