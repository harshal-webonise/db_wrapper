<?php
class DbWrapper extends PDO
{
    private static $db, $query;

    function __construct($hostName, $userName, $password, $databaseName)
    {
        try {

            parent::__construct("mysql:host=$hostName;dbname=$databaseName", $userName, $password);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function getInstance($hostName, $userName, $password, $databaseName)
    {
        if (self::$db === null) {
            self::$db = new DbWrapper($hostName, $userName, $password, $databaseName);
        }
        return self::$db;
    }

    public function select($fields)
    {
        self::$query = 'select ';
        foreach ($fields as $field) {
            self::$query .= $field . ',';
        }
        self::$query = rtrim(self::$query, ',');
        return self::$db;
    }

}

?>