<?php
App::import('Model', 'MediaModel');
class CopyrightholdersPicture extends MediaModel {

    var $name = 'CopyrightholdersPicture';

    var $belongsTo = array(
            'Copyrightholder' => array('className' => 'Copyrightholder',
                                'foreignKey' => 'copyrightholder_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

}
?>