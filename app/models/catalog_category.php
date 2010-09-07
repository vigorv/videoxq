<?php
class CatalogCategory extends AppModel {

    var $name = 'CatalogCategory';
    var $validate = array(
        'title' => VALID_NOT_EMPTY
    );
    var $actsAs = array('Tree');
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasMany = array(
            'CatalogItem' => array('className' => 'CatalogItem',
                                'foreignKey' => 'catalog_category_id',
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
            'CatalogCategoryParent' => array('className' => 'CatalogCategory',
                                'foreignKey' => 'parent_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

}
?>