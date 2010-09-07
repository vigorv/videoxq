<?php
class DoCategory extends AppModel {

    var $name = 'DoCategory';
    var $validate = array(
        'title' => array('/.+/')
    );

    var $actsAs = array('Sluggable' =>
                            array('slug' => 'url', 'overwrite' => true, 'translation' => 'utf-8'));


    var $belongsTo = array(
            'Parent' => array('className' => 'DoCategory',
                                'foreignKey' => 'parent_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

    var $hasMany = array(
            'Advert' => array('className' => 'Advert',
                                'foreignKey' => 'do_category_id',
                                'dependent' => false,
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