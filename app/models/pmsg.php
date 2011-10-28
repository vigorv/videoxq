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

    /**
     * получить список исходящих сообщений пользователя
     *
     * @param integer $userId - идентификатор пользователя
     * @return mixed
     */
	public function getOutMessages($userId, $page = 0, $perPage = 10)
	{
		$sql = '
			SELECT Pm.pmid, Pmsg.message, Pmsg.title, Pmsg.fromusername FROM pmtext AS Pmsg
				INNER JOIN pm AS Pm ON (Pm.pmtextid = Pmsg.pmtextid AND Pm.userid = ' . $userId . ')
				LIMIT ' . $page . ', ' . $perPage . '
		';
		$result = $this->query($sql);
		return $result;
	}

	public function getInMessages($userId, $page = 0, $perPage = 10)
	{
		$sql = '
			SELECT Pm.pmid, Pmsg.message, Pmsg.title, Pmsg.fromusername FROM pmtext AS Pmsg
				INNER JOIN pm AS Pm ON (Pm.pmtextid = Pmsg.pmtextid)
				INNER JOIN pmreceipt as PmR ON (Pm.pmid = PmR.pmid AND PmR.touserid = ' . $userId . ')
				LIMIT ' . $page . ', ' . $perPage . '
		';
		$result = $this->query($sql);
		return $result;
	}
}