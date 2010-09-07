<?php
App::import('Model', 'MediaModel');
/**
 * Модель баннеров
 *
 */
class Banner extends MediaModel {
	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Banner';

    var $validate = array(
        'name' => VALID_NOT_EMPTY,
        'place' => VALID_NOT_EMPTY,
        'code' => VALID_NOT_EMPTY,
    );
}
