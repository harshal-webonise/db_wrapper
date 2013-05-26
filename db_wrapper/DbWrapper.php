<?php
class DbWrapper extends PDO {
    private static $db, $query, $instance = 0;

    function __construct($hostName, $userName, $password, $databaseName) {
        try {

            if (self::$instance == 1) {
                throw new Exception('Instance already exist');
            }
            self::$instance = 1;

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

    public function select($fields = '*') {
        if (is_array($fields)) {
            self::$query = 'SELECT ' . implode(', ', $fields);
        } else {
            self::$query = 'SELECT *';
        }
        return self::$db;
    }

    public function from($tableNames) {
        self::$query .= ' FROM ' . implode(', ', $tableNames);
        return self::$db;
    }

    public function where($conditions) {
        self::$query .= ' WHERE';
        foreach ($conditions as $key => $condition) {
            self::$query .= " $key=" . $condition . ',';
        }
        self::$query = rtrim(self::$query, ',');
        return self::$db;
    }

    public function limit($limit, $offset = null) {
        self::$query .= " LIMIT $limit" . ($offset != null ? ", $offset" : '');
        return self::$db;
    }

    public function orderBy($fieldName, $order = 'ASC') {
        self::$query .= " ORDER BY $fieldName $order";
        return self::$db;
    }

    public function get() {
        return self::$query;
    }

    public function result($query = null) {
        try {
            $query = $query == null ? self::$query . ';' : $query;
            return self::$db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function save() {

    }

    public function delete() {

    }

}

?>