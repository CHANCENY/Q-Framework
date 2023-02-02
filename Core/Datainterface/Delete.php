<?php

namespace Datainterface;

class Delete
{
    public static function update($tableName, $keyValue = []): bool{
        $helper = new CrudHelper();
        $helper->setTableName($tableName);
        return $helper->delete($keyValue);
    }

}