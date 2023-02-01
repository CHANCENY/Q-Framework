<?php

namespace Datainterface;

use Alerts\Alerts;

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
}