<?php
class PersonPicture extends MediaModel {

    var $name = 'PersonPicture';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'Person' => array('className' => 'Person',
                                'foreignKey' => 'person_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

}
?>