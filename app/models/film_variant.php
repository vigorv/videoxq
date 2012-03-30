<?php
App::import('Model', 'MediaModel');
class FilmVariant extends MediaModel {
    var $name = 'FilmVariant';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'Film' => array('className' => 'Film',
                                'foreignKey' => 'film_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
            'VideoType' => array('className' => 'VideoType',
                                'foreignKey' => 'video_type_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
            'Quality' => array('className' => 'Quality',
                                'foreignKey' => 'quality_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

    var $hasMany = array(
            'FilmFile' => array('className' => 'FilmFile',
                                'foreignKey' => 'film_variant_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => '',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            			),
            'FilmLink' => array('className' => 'FilmLink',
                                'foreignKey' => 'film_variant_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => 'FilmLink.id ASC',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            			),
	);

    var $hasOne = array(
            'Track' => array('className' => 'Track',
                                'foreignKey' => 'film_variant_id',
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

}
?>