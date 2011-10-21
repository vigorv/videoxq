<?php
/**
 * Хранение опций, настроек, состояний пользовательского профиля (Личного Кабинета)
 *
 */
class UserOption extends AppModel {
    public $name = 'UserOption';
    public $useTable = 'user_options'; //имя таблицы
    public $primaryKey = 'user_id'; //имя поля первичного ключа

	/**
     * чтение опций юзера из БД
     *
     * @param integer $userId
	 * @return mixed - возврат несерализованного массива
	*/
    public function getOptions($userId)
    {
    	$info = $this->read(null, $userId);
    	if (empty($info))
    	{
    		return array();
    	}
    	return unserialize($info['User']['options']);
    }

    /**
     * сохранить/обновить опции пользователя
     *
     * @param integer $userId - идентификатор пользователя
     * @param mixed $options - массив опций
     * @return boolean - результат сохранения/обновления опций
     */
    public function setOptions($userId, $options)
    {
    	return $this->save(array('UserOption' =>
    		array(
    			'user_id' => $userId,
    			'options' => serialize($options)
    		))
    	);
    }
}
