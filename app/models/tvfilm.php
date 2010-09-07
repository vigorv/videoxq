<?php
App::import('Model', 'MediaModel');
class Tvfilm extends MediaModel {

    var $name = 'Tvfilm';

    var $belongsTo = array(
            'Tvcategory' => array('className' => 'Tvcategory',
                                'foreignKey' => 'tvcategory_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
            'Tvchannel' => array('className' => 'Tvchannel',
                                'foreignKey' => 'tvchannel_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );
    var $actsAs = array('Containable');
}
?>