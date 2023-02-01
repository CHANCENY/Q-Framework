<?php

namespace Datainterface;

class Updating extends Database
{
    public static function update($tableName, $data = [], $keyValue = []): bool{
        $con = self::database();
        if(HelperClass::checkColumnsMatch($tableName, $data)){
            $query = "UPDATE TABLE {$tableName} SET ".HelperClass::lineSetQuery($data);
            $cond = HelperClass::lineSetQuery($keyValue);
            $query .= ' '.$cond;
            $stmt = HelperClass::binding($query.$cond,$data);
            if(HelperClass::runQuery($stmt)){
                return true;
            }
        }
        return false;
    }

}