<?php
class Bookmark extends AppModel {

    var $name = 'Bookmark';
    var $actsAs = array('Bindable' => array('notices' => true));
    var $belongsTo = array(
            'User' => array('className' => 'User',
                            'foreignKey' => 'user_id',
                            'conditions' => '',
                            'fields' => '',
                            'order' => ''
            )
    );

    var $validate = array(
        'title' => VALID_NOT_EMPTY,
        'url' => VALID_NOT_EMPTY,
    );

}
?>