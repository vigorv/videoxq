<?php
/**
 * ветки форума обсуждения фильмов (VBulletin)
 *
 */
class Thread extends AppModel {

	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Thread';
    public $useTable = 'thread'; //имя таблицы в вструктуре VB
    public $primaryKey = 'threadid'; //имя поля первичного ключа

    public $hasMany = array(
            'Vbpost' => array('className' => 'Vbpost',
                                'foreignKey' => 'threadid',
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
