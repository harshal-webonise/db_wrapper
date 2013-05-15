<?php
class DbWrapper
{

    private static $db;

    public static function getInstance()
    {
        if (self::$db === null) {
            self::$db = new DbWrapper();
        }
        return self::$db;
    }

}

?>