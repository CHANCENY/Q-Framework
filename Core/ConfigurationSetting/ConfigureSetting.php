<?php

namespace ConfigurationSetting;

class ConfigureSetting
{
   private static $configs = [
          "mail"=>[
              "smtp"=>"smtp.gmail.com",
              "user"=>"chance.svinfotech@gmail.com",
              "password"=>"ggyjvxmcqvfyouwh"
          ]
       ];

   public static function getConfig($name){
       return self::$configs[$name];
   }
}