<?php

namespace Modules;

use Datainterface\Database;

class CountriesModular
{
   public static function getAllCountries(){
       $con = Database::database();
       $stmt = $con->prepare("SELECT sortname as code, name as country, id as rowid FROM tbl_countries");
       $stmt->execute();
       return $stmt->fetchAll(\PDO::FETCH_ASSOC);

   }

   public static function getAllStates(){
       $con = Database::database();
       $stmt = $con->prepare("SELECT name as state, country_id as country, id as rowid FROM tbl_states");
       $stmt->execute();
       return $stmt->fetchAll(\PDO::FETCH_ASSOC);
   }

   public static function getAllCities(){
       $con = Database::database();
       $stmt = $con->prepare("SELECT name as city, state_id as state, id as rowid FROM tbl_cities");
       $stmt->execute();
       return $stmt->fetchAll(\PDO::FETCH_ASSOC);
   }

   public static function getStateByCountry($country){
       $con = Database::database();
       $stmt = $con->prepare("SELECT name as state, country_id as country, id as rowid FROM tbl_states WHERE country_id = :id");
       $country = htmlspecialchars(strip_tags($country));
       $stmt->bindParam(':id', $country);
       $stmt->execute();
       return $stmt->fetchAll(\PDO::FETCH_ASSOC);
   }

   public static function getCitiesByStates($state){
       $con = Database::database();
       $stmt = $con->prepare("SELECT name as city, state_id as state, id as rowid FROM tbl_cities WHERE state_id = :id");
       $state = htmlspecialchars(strip_tags($state));
       $stmt->bindParam(':id', $state);
       $stmt->execute();
       return $stmt->fetchAll(\PDO::FETCH_ASSOC);

   }

   public static function getCountryName($code){
       $con = Database::database();
       $stmt = $con->prepare("SELECT name as country FROM {tbl_countries} WHERE sortname = :code");
       $stmt->bindParam(':code', $code);
       $stmt->execute();

      return $stmt->fetchAll(\PDO::FETCH_ASSOC)[0]['country'];
   }
}