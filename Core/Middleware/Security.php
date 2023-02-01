<?php

namespace MiddlewareSecurity;

use GlobalsFunctions\Globals;

class Security extends Globals
{
   private $currentUser;

   private $currentView;

   public function __construct(){

       $this->currentView = self::view();
       $this->currentUser = self::user();
   }

   public function checkViewAccess(){
       if(empty($this->currentView)){
           return "V-NULL";
       }

       //check access

       $values = array_values($this->currentView);
       if(in_array('public', $values)){
           return "V-PUBLIC";
       }

       if(in_array('private', $values)){
           return "V-PRIVATE";
       }

       if(in_array('administrator', $values)){
           return "V-PRIVATE";
       }
   }

   public function checkCurrentUser(){
       if(empty($this->currentUser)){
           return "U-NULL";
       }

       //check role
       elseif($this->currentUser[0]['role'] === "Admin"){
           return "U-Admin";
       }

       elseif($this->currentUser[0]['blocked'] === true){
           return "U-BLOCK";
       }

       elseif($this->currentUser['verified'] === true){
           return "V-VERIFIED";
       }
       return "U-COMMON";
   }
}