<?php

namespace Assest;

class Assest
{
   /**
    * This class consist of method to load and cache javascript and css in web
    */
   public static function loadJavaScript($groupName){

       $path = $_SERVER['DOCUMENT_ROOT'].'/Js';
       $foundFiles = [];
       if(is_dir($path)){
           $list = scandir($path);
           foreach ($list as $l){
               if($l !== '.' && $l !== '..'){
                   $split = explode('.', $l);
                   if(strtolower(end($split)) === 'js'){
                       $foundFiles[] = 'Js/' . $l;
                   }
               }
           }
       }
       if(!empty($foundFiles)){
           foreach ($foundFiles as $file){
               echo "<script src='{$file}'></script>";
           }
       }
   }

   public static function loadStyleSheets(){
       $path = $_SERVER['DOCUMENT_ROOT'].'/Css';
       $foundFiles = [];
       if(is_dir($path)){
           $list = scandir($path);
           foreach ($list as $l){
               if($l !== '.' && $l !== '..'){
                   $split = explode('.', $l);
                   if(strtolower(end($split)) === 'css'){
                       $foundFiles[] = 'Js/' . $l;
                   }
               }
           }
       }
       if(!empty($foundFiles)){
           foreach ($foundFiles as $file){
               echo "<link rel='stylesheet' href='{$file}'>";
           }
       }
   }

   public static function loadJavaScriptByFileName($filename){

       $base = $_SERVER['DOCUMENT_ROOT'].'/Js';
       $foundFiles = [];
       if(is_dir($base)){
           $list = scandir($base);

           foreach ($list as $file){
               if($filename === $file){
                   $foundFiles[] = 'Js/' . $file;
               }
           }
       }

       if(!empty($foundFiles)){
           foreach ($foundFiles as $file){
               echo "<script src='{$file}'></script>";
           }
       }
   }

   public static function loadStyleSheetByFilename($filename){
       $base = $_SERVER['DOCUMENT_ROOT'].'/Css';
       $foundFiles = [];
       if(is_dir($base)){
           $list = scandir($base);

           foreach ($list as $file){
               if($filename === $file){
                   $foundFiles[] = 'Css/' . $file;
               }
           }
       }

       if(!empty($foundFiles)){
           foreach ($foundFiles as $file){
               echo "<link rel='stylesheet' href='{$file}'>";
           }
       }
   }
}