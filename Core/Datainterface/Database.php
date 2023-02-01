<?php

namespace Datainterface;

use Alerts\Alerts;
use Sessions\SessionManager;

class Database
{
   /**
    * Mysql is being used please change these variables value to you database crenditial
    */
   private static $user = "root";

    /**
     * @return string
     */
    public static function getUser()
    {
        return self::$user;
    }

    /**
     * @param string $user
     */
    public static function setUser($user)
    {
        self::$user = $user;
    }

    /**
     * @return null
     */
    public static function getPassword()
    {
        return self::$password;
    }

    /**
     * @param null $password
     */
    public static function setPassword($password)
    {
        self::$password = $password;
    }

    /**
     * @return string
     */
    public static function getDbname()
    {
        return self::$dbname;
    }

    /**
     * @param string $dbname
     */
    public static function setDbname($dbname)
    {
        self::$dbname = $dbname;
    }

    /**
     * @return string
     */
    public static function getHost()
    {
        return self::$host;
    }

    /**
     * @param string $host
     */
    public static function setHost($host)
    {
        self::$host = $host;
    }
   private static $password = NULL;
   private static $dbname = "blogdb";
   private static $host = "localhost";

   public static function database(){
       $dsn  = "mysql:host=".self::$host.";dbname=".self::$dbname;

       try {
           return new \PDO($dsn, self::$user, self::$password);
       }catch (\PDOException $e){
           echo Alerts::alert('info',$e->getMessage());
           die();
       }
   }

   public static function installer(){

       $con = self::database();

       $maker = new MysqlDynamicTables();
       $columns = ['uid','firstname','lastname','mail','phone','password','address','role','verified','blocked'];
       $attributes = [
         'uid'=>['INT(11)','AUTO_INCREMENT','PRIMARY KEY'],
         'firstname'=>['VARCHAR(100)','NOT NULL'],
         'lastname'=>['VARCHAR(100)', 'NOT NULL'],
         'mail'=>['VARCHAR(100)','NOT NULL'],
         'phone'=>['VARCHAR(20)', 'NULL'],
           'password'=>['VARCHAR(100)', 'NOT NULL'],
         'address'=>['TEXT','NULL'],
         'role'=>['VARCHAR(20)','NOT NULL'],
           'verified'=>['BOOLEAN'],
           'blocked'=>['BOOLEAN']
       ];

       $maker->resolver($con,$columns,$attributes,'users',false);

       try{
           $conn = self::database();
           $stmt = $conn->prepare("SELECT 1 FROM tbl_cities LIMIT 1");
           $stmt->execute();
       }catch (\Exception $e){

         $path = $_SERVER["DOCUMENT_ROOT"].'/Core/Temps/tbl_cities.sql';
         self::importTable($path);
       }

       try{
           $conn = self::database();
           $stmt = $conn->prepare("SELECT 1 FROM tbl_countries LIMIT 1");
           $stmt->execute();
       }catch (\Exception $e){

           $path = $_SERVER["DOCUMENT_ROOT"].'/Core/Temps/tbl_countries.sql';
           self::importTable($path);
       }

       try{
           $conn = self::database();
           $stmt = $conn->prepare("SELECT 1 FROM tbl_states LIMIT 1");
           $stmt->execute();
       }catch (\Exception $e){

           $path = $_SERVER["DOCUMENT_ROOT"].'/Core/Temps/tbl_states.sql';
           self::importTable($path);
       }

       $user = Selection::selectById('users', ['role'=>'Admin']);
       if(empty($user)){
           SessionManager::setSession('site', false);
       }else{
           SessionManager::setSession('site', true);
       }
   }

   public static function importTable($file){
       $con = self::database();
       $query = file_get_contents($file);
       if(!empty($query)){
          $stmt = $con->prepare($query);
           return $stmt->execute();
       }
   }
}