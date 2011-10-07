<?php

/**
 * @property Auth2 $Auth2
 * @property Cookie $Cookie
 * @property Session $Session
 */
class UserFast extends AppModel {

    var $name = 'UserFast';
    var $useTable = 'user';
    var $authUser = null;

    function Login($mail='', $pass='') {
        if ($mail == '')
            return null;
        $salt = $this->query('select salt from user where email ="' . $mail . '"');
        if (empty($salt))
            return null;
        $hash = md5(md5($pass) . $salt[0]['user']['salt']);
        $user = $this->query('SELECT User.username,User.userid,userprofile.* from user as User
    LEFT JOIN userprofile ON userprofile.id = User.userid     
    where User.email = "' . $mail . '" AND User.password = "' . $hash . '"');
        if (!empty($user)) {
            $authUser = $user[0]['User'];
            return $authUser;
        }
        return null;
    }

    function logout() {
        
    }

    function SearchUserByName($name, $limit=5) {
        $users = $this->query("SELECT username,userid from user Where username LIKE '%$name%' ORDER BY userid LIMIT $limit");
        return $users;
    }

    function GetUserByEmail($email) {
        $user = $this->query('SELECT userid from user WHERE email = "' . $email . '"');
        return $user;
    }

    function GetUserById($id) {
        $user = $this->query("SELECT userid,username,userprofile.* from user
                  LEFT JOIN userprofile ON userprofile.id = user.userid
                  WHERE userid=$id");
        return $user;
    }

    function UserExists($id) {
        $result = $this->query("SELECT COUNT(*) from user WHERE userid=$id LIMIT 1");
        if ($result[0][0]['COUNT(*)'])
            return TRUE;
        else
            return FALSE;
    }

    function GetUserList($cond, $page, $per_page) {
        $ftags = '';
        $order = 'ORDER BY username';
        if ($page > 1)
            $offset = ($page - 1) * $per_page;
        else
            $offset = 0;
        $per_page = (int) $per_page;
        if (($per_page < 1) || $per_page > 50)
            $per_page = 20;
        if (isset($cond['order'])) {
            switch ($cond['order']) {
                default:
                case 'username': $order = 'ORDER BY username';
            }
        }
        if ($this->authUser) {
            $ftags = ' LEFT JOIN 
                    ( Select COUNT(*) From userfriends
                     INNER JOIN userfriends as userif ON userif.friend_id=userfriends.user_id
                     WHERE userfriends.user_id=' . $this->authUser['userid'] . ' AND userif.user_id =user.userid) Friend )
userfriends ON userfriends.user_id = user.userid AND userfriends.friend_id = ' . $this->authUser['id'] . ' 
                LEFT JOIN userfriends AS userif ON userif.friend_id = user.userid';
        }
        $users = $this->query("SELECT
                user.userid,user.username,user.skype,user.icq,user.email,user.usertitle,user.lastactivity 
                FROM user $ftags $order Limit $offset,$per_page");
        return $users;
    }

    function GetUserListCount($cond=array()) {
        $users = $this->query("SELECT COUNT(*) FROM user ");
        return $users[0][0]['COUNT(*)'];
    }

}

