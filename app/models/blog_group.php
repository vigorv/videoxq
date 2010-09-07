<?php
class BlogGroup extends AppModel {

	var $name = 'BlogGroup';
	var $validate = array(
		'title' => array('notempty')
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Blog' => array('className' => 'Blog',
								'foreignKey' => 'blog_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

	var $hasAndBelongsToMany = array(
			'User' => array('className' => 'User',
						'joinTable' => 'blog_groups_users',
						'foreignKey' => 'blog_group_id',
						'associationForeignKey' => 'user_id',
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

}
?>