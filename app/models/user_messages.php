<?php

class UserMessages extends AppModel {

    var $name = 'UserMessages';
    var $useTable = 'usermessages';

    /**
     *
     * @param int $id 
     * @param int $offset
     * @param int $count
     * @return array $messages
     */
    function getMessagesForUser($id, $page=1, $count=20) {
        if ($page > 1)
            $offset = ($page - 1) * $per_page;
        else
            $offset = 0;
        $messages = $this->query('SELECT * FROM usermessages 
LEFT JOIN user as tou on tou.userid= usermessages.to_id
LEFT JOIN user as user on user.userid = usermessages.from_id
WHERE usermessages.to_id =' . $id . ' Limit ' . $offset . ',' . $count);
        return $messages;
    }

    function getMessagesFromUser($id, $page=1, $count=20) {
        if ($page > 1)
            $offset = ($page - 1) * $per_page;
        else
            $offset = 0;
        $messages = $this->query('SELECT * FROM usermessages 
LEFT JOIN user as tou on tou.userid= usermessages.to_id
LEFT JOIN user as user on user.userid = usermessages.from_id
WHERE usermessages.from_id =' . $id . ' Limit ' . $offset . ',' . $count);
        return $messages;
    }
    
    function CreateMessageForUser($from_id,$to_id,$txt){
        $data['from_id']=$from_id;
        $data['to_id']=$to_id;
        $data['txt']=$txt;
        return $this->save($data);        
    }

}