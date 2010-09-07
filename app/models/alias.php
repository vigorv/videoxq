<?php
/**
 * Модель названий страниц (для системы учета переходов)
 *
 */
class Alias extends AppModel {

	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Alias';

	public $hasMany = array(
		'Referrer'	=> array(
			'className'		=> 'Referrer',
			'conditions'	=> '',
			'order'			=> '',
			'limit'			=> '',
			'foreignKey'	=> 'alias_id',
			'dependent'		=> true,
			)
		);
}
?>