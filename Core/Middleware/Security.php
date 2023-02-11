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

       elseif($this->currentUser[0]['blocked'] === 1){
           return "U-BLOCK";
       }

       elseif($this->currentUser[0]['verified'] === 1){
           return "V-VERIFIED";
       }

       //check role
       elseif($this->currentUser[0]['role'] === "Admin"){
           return "U-Admin";
       }

       return "U-COMMON";
   }
}