<?php
/**
 * Модель для таблицы смайликов форума
 *
 */
class Smile extends AppModel {

	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Smile';

    //public $useDbConfig = 'portal';
    public $useTable = 'smilie'; //имя таблицы в в структуре форума
    public $primaryKey = 'smilieid'; //имя поля первичного ключа
}
