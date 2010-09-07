<?php
/**
 * посты (сообщения) ветки форума обсуждений фильмов (VBulletin)
 *
 */
class Vbpost extends AppModel {

	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Vbpost';

    public $useTable = 'post'; //имя таблицы в вструктуре VB
    public $primaryKey = 'postid'; //имя поля первичного ключа
}
