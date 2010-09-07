<?php
class CatalogItem extends AppModel {

    var $name = 'CatalogItem';
    var $validate = array(
        'title' => VALID_NOT_EMPTY,
        'text' => VALID_NOT_EMPTY,
        'url' => VALID_NOT_EMPTY
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'CatalogCategory' => array('className' => 'CatalogCategory',
                                'foreignKey' => 'catalog_category_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );
}
?>