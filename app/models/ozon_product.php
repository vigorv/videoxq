<?php
App::import('Model', 'MediaModel');
class OzonProduct extends MediaModel {

    var $name = 'OzonProduct';

    var $hasAndBelongsToMany = array(
            'OzonCategory' => array('className' => 'OzonCategory',
                        'joinTable' => 'ozoncategories_ozonproducts',
                        'foreignKey' => 'product_id',
                        'associationForeignKey' => 'category_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            )
	);
    var $actsAs = array('Containable');
}
?>