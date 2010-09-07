<?php
/**
 * баны пользователей (VBulletin)
 *
 */
class Userban extends AppModel {

	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Userban';
    public $useTable = 'userban'; //имя таблицы в вструктуре VB
    public $primaryKey = 'userid'; //имя поля первичного ключа
}
