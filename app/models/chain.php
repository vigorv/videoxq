<?php
/**
 * Модель звеньев цепочек переходов по страницам
 *
 */
class Chain extends AppModel {

	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Chain';

	public $hasMany = array(
		'Chain'	=> array(
			'className'		=> 'Chain',
			'conditions'	=> '',
			'order'			=> '',
			'limit'			=> '',
			'foreignKey'	=> 'parent_id',
			'dependent'		=> true,
			)
		);
}
?>