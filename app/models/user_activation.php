<?php
class UserActivation extends AppModel
{
    var $name = 'UserActivation';
    var $primaryKey = 'useractivationid';
    var $useTable = 'useractivation';

    function createActivationId($userid, $usergroupid)
    {
        if ($usergroupid == 3 OR $usergroupid == 0)
        { // stop them getting stuck in email confirmation group forever :)
            $usergroupid = 2;
        }

        $this->del(array('userid' => $userid));

        $activateid = mt_rand(0,100000000);
        /*insert query*/
        $this->query("
            REPLACE INTO useractivation
                (userid, dateline, activationid, type, usergroupid, emailchange)
            VALUES
                ($userid, " . time() . ", $activateid , 0, $usergroupid, 0)");

        return $activateid;
    }

    function removeActivation($activationId)
    {
        $this->query('DELETE FROM useractivation WHERE activationid = ' . $activationId);
    }

    function removeActivationByUid($uId)
    {
        $this->query('DELETE FROM useractivation WHERE userid = ' . $uId);
    }

}
?>