<?php
class UserWishlist extends AppModel
{
   var $name = 'UserWishlist';
   var $useTable = 'userwishlist';
   /**
    *
    * @param type $id
    * @param type $offset
    * @param type $count 
    * @return array
    */
   function GetWishForUser($id,$offset=0,$count=20){
       $wishlist = $this->query('SELECT * FROM `userwishlist` WHERE user_id=' . $id.' Limit '.$offset.','.$count);
       return $wishlist;
   }
   
   /**
    *
    * @param type $id
    * @param type $user_id
    * @return array
    */
   function GetWishById($id,$user_id){
       $wishlist = $this->query('SELECT * FROM `userwishlist` WHERE id ='.$id.' and user_id=' . $id.' Limit 1');
       return $wishlist;
   }
   
   
}