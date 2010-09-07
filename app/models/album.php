<?php
class Album extends MediaModel {

    var $name = 'Album';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasMany = array(
            'AudioTrack' => array('className' => 'AudioTrack',
                                'foreignKey' => 'album_id',
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
            'AudioQuality' => array('className' => 'AudioQuality',
                        'joinTable' => 'albums_audio_qualities',
                        'foreignKey' => 'album_id',
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