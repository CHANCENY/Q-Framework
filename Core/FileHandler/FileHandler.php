<?php

namespace FileHandler;

use Datainterface\Database;
use Datainterface\Delete;
use Datainterface\Insertion;
use Datainterface\MysqlDynamicTables;
use Datainterface\Updating;

class FileHandler
{
  public static function saveFile($filename, $data, $type = "tmp"){

      $path = $_SERVER['DOCUMENT_ROOT'].'/Files';
      if(!is_dir($path)){
         mkdir($path, 777);
      }
      switch ($type){
          case 'tmp':
              $content = file_get_contents($data);
              top:
              if(file_exists($path.'/'.$filename)){
                  $list = explode('.', $filename);
                  $filename = uniqid().'.'.end($list);
                  goto top;
              }else{
                  if(file_put_contents($path.'/'.$filename, $content)){
                      return $path.'/'.$filename;
                  }
              }
          case 'binary':
              tops:
              if(file_exists($path.'/'.$filename)){
                  $list = explode('.', $filename);
                  $filename = uniqid().'.'.end($list);
                  goto tops;
              }else{
                  if(file_put_contents($path.'/'.$filename, $data)){
                      return $path.'/'.$filename;
                  }
              }
          default:
              return $path;

      }
  }


  public static function deleteFile($filename){
      if(empty($filename)){
          return false;
      }

      $base = $_SERVER['DOCUMENT_ROOT'].'/Files';
      if(is_dir($base)){
          $fileList = scandir($base);

          foreach ($fileList as $file){
              if($file === $filename){
                 return unlink($base.'/'.$filename);
              }
          }
      }
  }

  public static function renameFile($oldname, $newname){
      if(empty($newname) || empty($oldname)){
          return false;
      }
      return rename($_SERVER['DOCUMENT_ROOT'].'/Files/'.$oldname, $_SERVER['DOCUMENT_ROOT'].'/Files/'.$newname);
}

  public static function findFile($filname, $type = 'absolute'){
      if(empty($filname)){
          return false;
      }
      $base = $_SERVER['DOCUMENT_ROOT'].'/Files';

      if(is_dir($base)){
          $fileList = scandir($base);
          foreach ($fileList as $file){
              if($file === $filname){
                  if($type === "absolute"){
                      return $_SERVER['DOCUMENT_ROOT'].'/Files/'.$filname;
                  }else{
                      return 'Files/'.$filname;
                  }
              }
          }
      }
  }

 public static function dbSavingFile($data){
      $con = Database::database();
      $columns = ['fid', 'filename','filesize','tmp'];
      $attributes = [
          'fid'=>['INT(11)','AUTO_INCREMENT','PRIMARY KEY'],
          'filename'=>['VARCHAR(100)','NULL'],
          'filesize'=>['INT(11)', 'NULL'],
          'tmp'=>['LONG','BLOB']
      ];

      $maker = new MysqlDynamicTables();
      $maker->resolver($con,$columns,$attributes,'file_managed',false);

      return Insertion::insertRow('file_managed', $data);
 }

 public static function dbdeleteFile($keyValue){
      return Delete::delete('file_managed', $keyValue);
 }

 public static function dbupdateFile($keyValue, $data){
      return Updating::update('file_managed',$data, $keyValue);
 }
}