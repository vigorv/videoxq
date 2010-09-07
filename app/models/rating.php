<?php
class Rating extends AppModel {

	var $name = 'Rating';
	var $validate = array(
		'num_votes' => array('numeric'),
		'object_id' => array('numeric')
	);

}
?>