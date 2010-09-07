<?php
/**
 * Модель для статистики выполнения поиска по транслиту
 *
 */
class Transtat extends AppModel {

	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Transtat';

    //public $useDbConfig = 'productionMedia';
    public $useTable = 'transtats'; //имя таблицы в вструктуре VB
    public $primaryKey = 'id'; //имя поля первичного ключа
}
