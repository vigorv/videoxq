<?php
/**
 * форум обсуждений фильмов (VBulletin)
 *
 */
class Forum extends AppModel {

	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Forum';

    public $useTable = 'forum'; //имя таблицы в вструктуре VB
    public $primaryKey = 'forumid'; //имя поля первичного ключа
}
