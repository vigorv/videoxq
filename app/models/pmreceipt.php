<?php
/**
 * получатели личных сообщений форума
 *
 */
class Pmreceipt extends AppModel {

	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Pmreceipt';

    public $useTable = 'pmreceipt'; //имя таблицы в вструктуре VB
    public $primaryKey = 'pmid'; //имя поля первичного ключа
}