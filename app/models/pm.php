<?php
/**
 * лог личных сообщений форума
 *
 */
class Pm extends AppModel {

	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Pm';

    public $useTable = 'pm'; //имя таблицы в вструктуре VB
    public $primaryKey = 'pmid'; //имя поля первичного ключа
}