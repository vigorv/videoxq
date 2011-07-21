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
            $user = $this->query("Select * from userloginza
               Where provider = " . $provider . " and uid= " . $uid . "
                   INNER JOIN user on userloginza.loginza_user_id = user.userid
                   LIMIT 1
               ");
            return $user;
        }
        return null;
    }

    function AssignProviderToUser($id=0, &$data=null) {
        if (!empty($data) && ($id>0) ){
            $provider = filter_var($data->provider, FILTER_SANITIZE_STRING);
            $uid = filter_var($data->uid, FILTER_SANITIZE_STRING);
            return    $userProvider = $this->query("INSERT INTO userloginza (loginza_user_id,provider,uid) VALUES (".$id.",". $provider . "," . $uid . ")");
        }
        else return false;
    }

    function NewUserByProvider(&$data=null) {
        if (!empty($data)) {
            $provider = filter_var($data->provider, FILTER_SANITIZE_STRING);
            $uid = filter_var($data->uid, FILTER_SANITIZE_STRING);
            $email = filter_var($data->email, FILTER_SANITIZE_STRING);
            //$user->
          //  return    $userProvider = $this->query("INSERT INTO userloginza (loginza_user_id,provider,uid) VALUES (".$id.",". $provider . "," . $uid . ")");
        }
        return false;       
    }

    function CreateNewProvider() {
        
    }

    function LoginzaCheck(&$data=null) {
        if (!exist($data))
            return false;
        if (!exist($data->uid))
            return false;
        if (!exist($data->email))
            return false;
        if (!exist($data->provider))
            return false;
        if (exist($data->error_type))
            return false;
        return true;
    }

}

?>
