<?php

namespace FormViewCreation;
@session_start();
use Datainterface\Selection;

/**
 * This class is only for login and logout process
 */
class Logging
{
    /**
     * @param $username array of key/value where key is column name in database table
     * @param $password string value
     * @return boolean
     *
     */
   public static function signingIn(string $password, array $username){
       if(empty($username) || empty($password)){
           return false;
       }

       $user = Selection::selectById('users', $username);
       if(empty($user)){
           return false;
       }

       if($password === $user[0]['password']){
           $_SESSION['private_data']['current_user'] = $user;
           return true;
       }else{
           return false;
       }
   }

   public static function signingOut(){
       if(isset( $_SESSION['private_data']['current_user'])){
           $_SESSION['private_data']['current_user'] = array();
           return true;
       }
       return false;
   }
}