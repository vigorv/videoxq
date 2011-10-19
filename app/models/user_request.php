<?php
class UserRequest extends AppModel
{
   var $name = 'UserRequest';
   var $useTable = 'userrequest';
   /**
    *
    * @param type $id
    * @param type $offset
    * @param type $count 
    * @return array
    */
   function GetRequestForUser($id,$offset=0,$count=20){
       $requestlist = $this->query('SELECT * FROM `userrequest` WHERE user_id=' . $id.' Limit '.$offset.','.$count);
       return $requestlist;
   }
   
   /**
    *
    * @param type $id
    * @param type $user_id
    * @return array
    */
   function GetRequestById($id,$user_id){
       $requestlist = $this->query('SELECT * FROM `userrequest` WHERE id ='.$id.' and user_id=' . $id.' Limit 1');
       return $requestlist;
   }
   
   
}