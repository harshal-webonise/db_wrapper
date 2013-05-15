<?php
class DbWrapper extends PDO
{
    private static $db;

    function __construct($hostName, $userName, $password, $databaseName)
    {
        return parent::__construct("mysql:host=$hostName;dbname=$databaseName", $userName, $password);
    }

    public static function getInstance($hostName, $userName, $password, $databaseName)
    {
        if (self::$db === null) {
            self::$db = new DbWrapper($hostName, $userName, $password, $databaseName);
        }
        return self::$db;
    }

}

?>