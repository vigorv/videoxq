<?php
class Source extends MediaModel {

    var $name = 'Source';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasMany = array(
            'SourceLink' => array('className' => 'SourceLink',
                                'foreignKey' => 'source_id',
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