<?php
class AudioTrack extends MediaModel {

    var $name = 'AudioTrack';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'Album' => array('className' => 'Album',
                                'foreignKey' => 'album_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

    var $hasAndBelongsToMany = array(
            'AudioGenre' => array('className' => 'AudioGenre',
                        'joinTable' => 'audio_genres_audio_tracks',
                        'foreignKey' => 'audio_track_id',
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
            'AudioQuality' => array('className' => 'AudioQuality',
                        'joinTable' => 'audio_qualities_audio_tracks',
                        'foreignKey' => 'audio_track_id',
                        'associationForeignKey' => 'audio_quality_id',
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