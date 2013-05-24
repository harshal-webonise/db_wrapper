<?php
class DbWrapper extends PDO {
    private static $db, $query;

    function __construct($hostName, $userName, $password, $databaseName) {
        try {
            parent::__construct("mysql:host=$hostName;dbname=$databaseName", $userName, $password);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function getInstance($hostName, $userName, $password, $databaseName) {
        if (self::$db === null) {
            self::$db = new DbWrapper($hostName, $userName, $password, $databaseName);
        }
        return self::$db;
    }

    public function select($fields) {
        self::$query .= 'select ' . implode(', ', $fields);
        return self::$db;
    }

    public function from($tableNames) {
        self::$query .= ' from ' . implode(', ', $tableNames);
        return self::$db;
    }

    public function where($conditions) {
        self::$query .= ' where';
        foreach ($conditions as $key => $condition) {
            self::$query .= " $key=" . $condition . ',';
        }
        self::$query = rtrim(self::$query, ',');
        return self::$db;
    }

    public function result() {
        try {
            return self::$db->query(self::$query . ';')->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}

?>