<?php
class PersonComment extends MediaModel {

    var $name = 'PersonComment';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'Person' => array('className' => 'Person',
                                'foreignKey' => 'person_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
            'User' => array('className' => 'User',
                                'foreignKey' => 'user_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

}
?>