<?php
App::import('Model', 'MediaModel');
class FilmComment extends MediaModel {

    var $name = 'FilmComment';




    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'Film' => array('className' => 'Film',
                                'foreignKey' => 'film_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
            'User' => array('className' => 'User',
                                'foreignKey' => 'user_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );
   function __construct($id = false, $table = null, $ds = null)
   {
    $this->validate = array(
        'text' => 'notEmpty',
        'username' => 'notEmpty',
        'email' => 'email',
        'film_id' => 'notEmpty',
        'captcha'  => array(
    					array(
    							'rule' => VALID_HAS_PAIR,  
    							'message' => __('Проверочный код неправильный', true),
    							 'on' => 'create'
    							)
    						)
        
    );
   parent::__construct($id, $table, $ds);
   
   }
}
?>