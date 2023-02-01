<?php

namespace FileHandler;

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
}