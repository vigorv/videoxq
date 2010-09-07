<?php
class Pay extends AppModel {

	public $name = 'Pay';

    public $belongsTo = array(
            'User' => array('className' => 'User',
                                'foreignKey' => 'user_id',
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