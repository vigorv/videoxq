<?php
class PollSnow extends AppModel {
	var $name = 'PollSnow';

        function GetPollById($id) {
        $user = $this->query("SELECT userid,username,userprofile.* from user
                  LEFT JOIN userprofile ON userprofile.id = user.userid
                  WHERE userid=$id");
        return $user;
    }
}
?>