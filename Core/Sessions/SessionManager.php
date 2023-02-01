<?php

namespace Sessions;

class SessionManager
{
   public static function setSession($sessionName, $data){
       $_SESSION[$sessionName] = $data;
   }

   public static function getSession($sessionName){
       return $_SESSION[$sessionName];
   }

   public static function clearSession($sessionName){
       $type = gettype($_SESSION[$sessionName]);
       switch ($type){
           case 'string':
               $_SESSION[$sessionName] = " ";
           case 'array':
               $_SESSION[$sessionName] = array();
           case 'integer':
               $_SESSION[$sessionName] = 0;
           case 'boolean':
               $_SESSION[$sessionName] = false;
           default:
               $_SESSION[$sessionName] = NULL;
       }
   }

   public static function sessions(){
       return $_SESSION;
   }

}