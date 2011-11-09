<?php
/**
 * тексты личных сообщений форума
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
	 * подсчет количества входящих сообщений
	 *
	 * @param int $userId - идентификатор пользователя
	 */
	public function getCountInMessages($userId)
	{
		$sql = '
                    SELECT count(Pmsg.pmtextid) as msg_count FROM pmtext AS Pmsg
                        INNER JOIN pm AS Pm ON (Pm.folderid = 0 AND Pm.pmtextid = Pmsg.pmtextid AND Pm.userid = ' . $userId . ')
		';
		$result = $this->query($sql);
                $result = $result[0][0]['msg_count'];
		return $result;
	}        
        
	/**
	 * подсчет количества исходящих сообщений
	 *
	 * @param int $userId - идентификатор пользователя
	 */
	public function getCountOutMessages($userId)
	{
		$sql = '
                    SELECT count(Pmsg.pmtextid) as msg_count FROM pmtext AS Pmsg
                        INNER JOIN pm AS Pm ON (Pm.folderid = -1 AND Pm.pmtextid = Pmsg.pmtextid AND Pm.userid = ' . $userId . ')
                ';
		$result = $this->query($sql);
                $result = $result[0][0]['msg_count'];
		return $result;
	}                
    /**
     * получить список исходящих сообщений пользователя
     *
     * @param integer $userId - идентификатор пользователя
     * @return mixed
     */
	public function getOutMessages($userId, $page = 1, $perPage = 10)
	{
		$sql = '
			SELECT Pm.pmid, Pm.messageread, Pmsg.message, Pmsg.title, Pmsg.fromusername, Pmsg.touserarray, Pmsg.dateline FROM pmtext AS Pmsg
				INNER JOIN pm AS Pm ON (Pm.folderid = -1 AND Pm.pmtextid = Pmsg.pmtextid AND Pm.userid = ' . $userId . ' )
				LIMIT ' . ($page-1)*$perPage . ', ' . $perPage . '
		';
		$result = $this->query($sql);
		return $result;
        //WHERE Pm.userid = IF EXISTS (SELECT userid FROM customprofilepic) ELSE (SELECT Pm.userid FROM pm) , Av.dateline as ava_date , customprofilepic as Av
	}

	public function getInMessages($userId, $page = 1, $perPage = 10)
	{
		$sql = '
			SELECT Pm.pmid, Pm.messageread, Pmsg.message, Pmsg.title, Pmsg.fromusername, Pmsg.dateline FROM pmtext AS Pmsg
				INNER JOIN pm AS Pm ON (Pm.folderid = 0 AND Pm.pmtextid = Pmsg.pmtextid AND Pm.userid = ' . $userId . ')
				LIMIT ' . ($page-1)*$perPage . ', ' . $perPage . '
		';
//				INNER JOIN pmreceipt as PmR ON (Pm.pmid = PmR.pmid AND PmR.touserid = ' . $userId . ')
		$result = $this->query($sql);
		return $result;
	}

	/**
	 * Отправить личное сообщение
	 *
	 * @param string $fromUserName	- логин отправителя
	 * @param string $toUserName	- логин получателя
	 * @param string $title			- тема сообщения
	 * @param strinh $msg			- текст сообщения
	 * @return boolean 				- результат - отправлено или нет
	 */
	public function sendMessage($fromUserName, $toUserName, $title, $msg)
	{
		$result = false;
		App::import('Model','Pm');
		$Pm = new Pm();
		App::import('Model','Pmreceipt');
		$Pmreceipt = new Pmreceipt();
		App::import('Model','User');
		$User = new User();

		$fromUser = $User->find(array('User.username' => $fromUserName), array('User.userid', 'User.username'), null, 0);
		$toUser = $User->find(array('User.username' => $toUserName), array('User.userid', 'User.username'), null, 0);

		if (!empty($fromUser) && (!empty($toUser)))
		{
		//СОХРАНЯЕМ ТЕКСТ СООБЩЕНИЯ
			$this->create();
			$info = array(
				'Pmsg' => array(
					'fromuserid'	=> $fromUser['User']['userid'],
					'fromusername'	=> $fromUserName,
					'title'			=> $title,
					'message'		=> $msg,
					'touserarray'	=> serialize(array('cc' =>array($toUser['User']['username']))),
					'iconid'		=> 0,
					'dateline'		=> time(),
					'showsignature'	=> 0,
					'allowsmilie'	=> 1
				)
			);
			if ($this->save($info))
			{
				$pmTextId = $this->getLastInsertID();

				$Pm->create();
				$logMessage = array(//ИСХОДЯЩЕЕ
					'Pm' => array(
						'pmtextid'		=> $pmTextId,
						'userid'		=> $fromUser['User']['userid'],
						'folderid'		=> -1,
						'messageread'	=> 1
					)
				);
				$Pm->save($logMessage);
				$pmId = $Pm->getLastInsertID();

				$Pm->create();
				$logMessage = array(//ВХОДЯЩИЕ
					'Pm' => array(
						'pmtextid'		=> $pmTextId,
						'userid'		=> $toUser['User']['userid'],
						'folderid'		=> 0,
						'messageread'	=> 0
					)
				);
				$Pm->save($logMessage);
				$pmId = $Pm->getLastInsertID();
				//в Pmreceipt пока не сохраняем
				$result = true;
			}
		}
		return $result;
	}

	/**
	 * установить метку о прочтении сообщения
	 *
	 * @param int $pmId - идентификатор личного сообщения
	 */
	public function setMessageRead($pmId)
	{
		App::import('Model','Pm');
		$Pm = new Pm();

		$info = array('Pm' => array('pmid' => intval($pmId), 'messageread' => 1));
		$Pm->save($info);
	}
        
	/**
	 * получение полных данных сообщения с pmid=$pmId, для пользователя с 
         * id = $userid 
	 *
	 * @param int $pmId - идентификатор личного сообщения
         * @param int $userid  - идентификатор пользователя
         * 
         * return mixed $data
	 */        
        public function getMessageFull($userId=0, $pmId=0){
            $sql = '
                    SELECT Pm.pmid, Pm.messageread, Pmsg.message, Pmsg.title, Pmsg.fromusername, Pmsg.touserarray, Pmsg.dateline  FROM pmtext AS Pmsg
                            INNER JOIN pm AS Pm ON (Pm.pmtextid = Pmsg.pmtextid AND Pm.userid = ' . $userId . ' AND Pm.pmid = ' . $pmId . ')
                            LIMIT 1
            ';
            $result = $this->query($sql);
            return $result;            
        }

	/**
	 * Удаление списка исходящих сообщений , пользователя с id = $userid 
	 *
	 * @param int $msg_id_arr - массив идентификаторо личных сообщений
         * @param int $userid  - идентификатор пользователя
         * 
         * return boolean $result
	 */         
        public function delOutMessages($userid=0, $msg_id_arr=array()){
            return true;
        }
	/**
	 * Удаление сообщения , для пользователя с id = $userid 
	 *
	 * @param int $msgid - массив идентификаторо личных сообщений
         * @param int $userid  - идентификатор пользователя
         * 
         * return boolean $result
	 */                 
        public function delMessage($userid=0, $msgid=0){
            return true;
        }                
	/**
	 * Удаление списка входящих сообщений , для пользователя с id = $userid 
	 *
	 * @param int $msg_id_arr - массив идентификаторо личных сообщений
         * @param int $userid  - идентификатор пользователя
         * 
         * return boolean $result
	 */                 
        public function delInMessages($userid=0, $msg_id_arr=array()){
            return true;
        }        
	/**
	 * Удаление всех исходящих сообщений , пользователя с id = $userid 
	 *
         * @param int $userid  - идентификатор пользователя
         * 
         * return boolean $result
	 */                         
        public function delAllOutMessages($userid=0){
            return true;
        }
	/**
	 * Удаление всех входящих сообщений , для пользователя с id = $userid 
	 *
         * @param int $userid  - идентификатор пользователя
         * 
         * return boolean $result
	 */                                 
        public function delAllInMessages($userid=0){
            return true;
        }        
        
}