<?php

class UserLoginza extends AppModel {

    var $name = 'UserLoginza';
    var $primaryKey = 'id';
    var $useTable = 'userloginza';

    //Find UserByProvider
    function FindUserByProvider(&$data=null) {
        if (!empty($data)) {
            $provider = filter_var($data->provider, FILTER_SANITIZE_STRING);
            $uid = filter_var($data->uid, FILTER_SANITIZE_STRING);
            $user = $this->query('Select * from userloginza
                        INNER JOIN user on userloginza.loginza_user_id = user.userid
                        Where provider ="' . $provider . '" and uid= "' . $uid . '" 
                   LIMIT 1
               ');
            return $user;
        }
        return null;
    }

    function AssignProviderToUser($id=0, &$data=null) {
        if (!empty($data) && ($id > 0)) {
            $provider = filter_var($data->provider, FILTER_SANITIZE_STRING);
            $uid = filter_var($data->uid, FILTER_SANITIZE_STRING);
            return $userProvider = $this->query("INSERT INTO userloginza (loginza_user_id,provider,uid) VALUES (" . $id . "," . $provider . "," . $uid . ")");
        }
        else
            return false;
    }

    function NewUserByProvider(&$data=null, $pwd=null) {

        function rand_string($length) {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

            $size = strlen($chars);
            $str = '';
            for ($i = 0; $i < $length; $i++) {
                $str .= $chars[rand(0, $size - 1)];
            }

            return $str;
        }

        if (!empty($data) && ($pwd <> null)) {
            $provider = filter_var($data->provider, FILTER_SANITIZE_STRING);
            $uid = filter_var($data->uid, FILTER_SANITIZE_STRING);
            $email = filter_var($data->email, FILTER_SANITIZE_STRING);
            $uname = filter_var($data->name->first_name, FILTER_SANITIZE_STRING);

            $user_info['User']['email'] = $email;
            $user_info['User']['username'] = $uname . '_in' .md5($email.$provider);
            $user_info['User']['password'] = $pwd;
            $user_info['User']['usergroupid'] = Configure::read('App.defaultUserGroup');
            

            $user = new User;
            $user->create();
            $res = $user->save($user_info);

            
            if ($res) {
                $userid =$res['User']['userid'] ;
                $this->query("Insert into groups_users (group_id,user_id) 
                        VALUES (". $user_info['User']['usergroupid'].",".$userid.')');
                $userProvider = $this->query("INSERT INTO userloginza (loginza_user_id,provider,uid)
                    VALUES (" .$userid . ',"' . $provider . '","' . $uid . '")');

                return $res;
            }
            return null;
        }
        return null;
    }

    function LoginzaCheck(&$data=null) {
        if (!isset($data))
            return false;
        if (!isset($data->uid))
            return false;
        if (!isset($data->email))
            return false;
        if (!isset($data->provider))
            return false;
        if (isset($data->error_type))
            return false;
        return true;
    }

}

?>
