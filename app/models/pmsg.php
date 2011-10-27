<?php
/**
 * личные сообщения форума
 *
 */
class Pmsg extends AppModel {

	/**
	 * Название модели
	 *
	 * @var string
	 */
    public $name = 'Pmsg';

    public $useTable = 'pmtext'; //имя таблицы в вструктуре VB
    public $primaryKey = 'pmtextid'; //имя поля первичного ключа

    public $useDbConfig = 'connCP1251';

	public function getOutMessages($userId)
	{
		$sql = '
			SELECT Pm.pmid, Pmsg.message, Pmsg.title, Pmsg.fromusername FROM pmtext AS Pmsg
				INNER JOIN pm AS Pm ON (Pm.pmtextid = Pmsg.pmtextid AND Pm.userid = ' . $userId . ')
		';
		$result = $this->query($sql);
		return $result;
	}
}