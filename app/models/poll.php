<?php
class Poll extends AppModel {

	var $name = 'Poll';
	var $validate = array(
		'title' => array('notempty'),
		'answers' => array('notempty')
	);

}
?>