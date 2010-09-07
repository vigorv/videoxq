<?php
class AudioQuality extends MediaModel {

    var $name = 'AudioQuality';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasAndBelongsToMany = array(
            'Album' => array('className' => 'Album',
                        'joinTable' => 'albums_audio_qualities',
                        'foreignKey' => 'audio_quality_id',
                        'associationForeignKey' => 'album_id',
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
                        'joinTable' => 'audio_qualities_audio_tracks',
                        'foreignKey' => 'audio_quality_id',
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