<?php
namespace GlobalsFunctions;
class Globals
{
  public static function titleView(){
      return isset($_SESSION['public_data']['view']['view_name']) ? $_SESSION['public_data']['view']['view_name'] : "View not found";
  }

  public static function host(){
      return isset($_SESSION['public_data']['host']) ? $_SESSION['public_data']['host'] : "host not found";
  }

  public static function url(){
      return isset($_SESSION['public_data']['path']) ? $_SESSION['public_data']['path'] : "Path not found";
  }

  public static function view(){
      return isset($_SESSION['public_data']['view']) ? $_SESSION['public_data']['view'] : "View data not found";
  }

  public static function queryline(){
      return isset($_SESSION['public_data']['query']) ? $_SESSION['public_data']['query'] : "Querystring not found";
  }

  public static function params(){
      return isset($_SESSION['public_data']['params']) ? $_SESSION['public_data']['params'] : "Params not set";
  }

  public static function user(){
      return isset($_SESSION['private_data']['current_user']) ? $_SESSION['private_data']['current_user'] : [];
  }

  public static function menus(){

      $menus = [];
      if(isset($_SESSION['viewsstorage'])){
          foreach ($_SESSION['viewsstorage'] as $view){
              if($view['view_role_access'] === 'administrator'){
                  $menus[] = $view;
              }
          }
      }

     return $menus;
  }

  public static function findViewByUrl($url){

      if(isset($_SESSION['viewsstorage'])){
          foreach ($_SESSION['viewsstorage'] as $view){
              if($view['view_url'] === $url){
                  return $view;
              }
          }
      }

  }
}