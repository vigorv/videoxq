<?php

class UserFriends extends AppModel {

    var $name = 'UserFriends';
    var $useTable = 'userfriends';

    function getUserFriends($id, $page=1, $per_page=20) {
        if ($page > 1)
            $offset = ($page - 1) * $per_page;
        else
            $offset = 0;
        $myfriends = $this->query('Select userfriends.friend_id,user.username,user.userid From `userfriends`
            INNER JOIN userfriends as userif ON userif.friend_id=userfriends.user_id 
            LEFT JOIN user ON user.userid= userfriends.friend_id            
            WHERE userfriends.user_id=' . $id . " LIMIT $offset,$per_page");
        return $myfriends;
    }

    function isUserFriend($user_id, $friend_id) {
        $result = $this->query('Select COUNT(*) From userfriends
            INNER JOIN userfriends as userif ON userif.friend_id=userfriends.user_id  WHERE userfriends.user_id=' . $user_id . ' AND userif.user_id =' . $friend_id);
        if ($result[0][0]['COUNT(*)'])
            return TRUE;
        else
            return FALSE;
    }

    function isRequestedAsFriend($user_id, $friend_id) {
        $result = $this->query('Select COUNT(*) From userfriends as USERFRIEND
              WHERE user_id=' . $user_id . ' AND friend_id =' . $friend_id);
        if ($result[0][0]['COUNT(*)'])
            return TRUE;
        else
            return FALSE;
    }

    function getOutRequestsForUser($id) {
        $myOutRequests = $this->query('Select userfriends.friend_id,user.username,user.userid From `userfriends`
            LEFT JOIN userfriends as userif ON userif.friend_id=userfriends.user_id  
            LEFT JOIN user ON user.userid= userfriends.friend_id
            WHERE userfriends.user_id=' . $id);
        return $myOutRequests;
    }

    function getInRequestsForUser($id) {
        $myInRequests = $this->query('Select userMain.user_id,user.username,user.userid,userfriends.user_id From `userfriends`
            LEFT  JOIN userfriends as userMain ON userMain.user_id=userfriends.friend_id 
            LEFT JOIN user ON user.userid= userfriends.user_id
            WHERE userfriends.friend_id=' . $id);
        return $myInRequests;
    }

    function RequestFriend($user_id, $request_user_id, $request_user_group=0) {
        $data['user_id'] = $user_id;
        $data['friend_id'] = $request_user_id;
        //$data['friend_group']=$request_user_group
        return $this->save($data);
    }
    
    function RequestDelete($user_id,$request_user_id){
        return $this->query("DELETE  FROM userfriends where user_id =".$user_id.' AND friend_id ='.$request_user_id);
    }
    

}