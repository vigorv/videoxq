<?php
class FaqItem extends AppModel {

    var $name = 'FaqItem';
    var $validate = array(
        'title' => VALID_NOT_EMPTY,
        'text' => VALID_NOT_EMPTY
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'FaqCategory' => array('className' => 'FaqCategory',
                                'foreignKey' => 'faq_category_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

    var $hasMany = array(
            'FaqComment' => array('className' => 'FaqComment',
                                'foreignKey' => 'faq_item_id',
                                'dependent' => true,
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