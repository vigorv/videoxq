<?php
class Advert extends AppModel {

    var $name = 'Advert';
    var $validate = array(
        'do_category_id' => array('numeric'),
        'username' => array('/.+/'),
        'title' => array('/.+/'),
        'text' => array('/.+/'),
        'email' => array('email'),
        'captcha'  => array(array('rule' => VALID_HAS_PAIR, 'on' => 'create'))
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'User' => array('className' => 'User',
                                'foreignKey' => 'user_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
            'DoCategory' => array('className' => 'DoCategory',
                                'foreignKey' => 'do_category_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

}
?>