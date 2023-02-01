<?php

namespace FormViewCreation;


use ConfigurationSetting\ConfigureSetting;
use Datainterface\Database;
use Datainterface\Selection;
use Mailling\Mails;

class ForgotPassword
{
    private $data;
    /**
     * @param $data
     * @return void
     *
     * This  function requires new password, email used to register account and possible old password
     */

  public static function forgotPassword($data =[]){
      if(empty($data)){
          return false;
      }

      //send me with code
      return Mails::send($data);

  }


  public static function changePassword($newpassword, $userid){
      $con = Database::database();
      $user = Selection::selectById('users', ['uid'=>$userid]);
      $stmt = $con->prepare('UPDATE users SET password = :password WHERE uid = :uid');
      $stmt->bindParam(':password', $newpassword);
      $stmt->bindParam(':uid', $userid);
      if($stmt->execute()){
          $user2 = Selection::selectById('users', ['uid'=>$userid]);
          if(!empty($user2) && !empty($user)){
              if($user2[0]['password'] !== $user[0]['password']){
                  return true;
              }
          }
      }
      return false;
  }
}