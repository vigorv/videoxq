<?php
class FaqComment extends AppModel {

    var $name = 'FaqComment';
    var $validate = array(
        'text' => 'notempty',
        'username' => 'notempty',
        'email' => 'email'
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'FaqItem' => array('className' => 'FaqItem',
                                'foreignKey' => 'faq_item_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
            'User'        => array ('className' => 'User',
                                'foreignKey' => 'user_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

}
?>