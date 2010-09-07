<?php
class FaqCategory extends AppModel {

    var $name = 'FaqCategory';
    var $validate = array(
        'title' => VALID_NOT_EMPTY
    );
    var $actsAs = array('Tree');
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasMany = array(
            'FaqItem' => array('className' => 'FaqItem',
                                'foreignKey' => 'faq_category_id',
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

    var $belongsTo = array(
            'FaqCategoryParent' => array('className' => 'FaqCategory',
                                'foreignKey' => 'parent_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

}
?>