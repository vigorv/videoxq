<?php
/**
 * Модель для таблицы чата форума
 *
 */
class CybChat extends AppModel {

	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'CybChat';

    //public $useDbConfig = 'portal';
    public $useTable = 'cyb_chatbox'; //имя таблицы в в структуре форума
    public $primaryKey = 'id'; //имя поля первичного ключа
//*
    public $belongsTo = array(
            'User' => array('className' => 'User',
                                'foreignKey' => 'userid',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );
//*/
}
