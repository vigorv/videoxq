<?php
class SourceLink extends MediaModel {

    var $name = 'SourceLink';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'Film' => array('className' => 'Film',
                                'foreignKey' => 'film_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
            'Source' => array('className' => 'Source',
                                'foreignKey' => 'source_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

}
?>