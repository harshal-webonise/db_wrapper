<?php
class DbWrapper {
    private static $db,$instance;
    private $query;

    private function __construct() { }

    public static function getInstance() {
        if (self::$instance === null) {
            try{
                ini_set('display_errors',1);
                self::$db = new PDO("mysql:host=localhost;dbname=test", 'root', 'root');
                self::$instance = new DbWrapper();
               } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        return self::$instance;
    }

    public function select($fields = '*') {
        if (is_array($fields)) {
            $this->query = 'SELECT ' . implode(', ', $fields);
        } else {
            $this->query = 'SELECT *';
        }
        return $this;
    }

    public function from($tableNames) {
        $this->query .= ' FROM ' . implode(', ', $tableNames);
        return $this;
    }

    public function where($conditions) {
        $this->query .= ' WHERE';
        foreach ($conditions as $key => $condition) {
            $this->query .= " $key" . $condition . ' AND';
        }
        $this->query = rtrim($this->query, 'AND');
        return $this;
    }

    public function limit($limit, $offset = null) {
        $this->query .= " LIMIT $limit" . ($offset != null ? ", $offset" : '');
        return $this;
    }

    public function orderBy($fieldName, $order = 'ASC') {
        $this->query .= " ORDER BY $fieldName $order";
        return $this;
    }

    public function get() {
        return $this->query;
    }

    public function result($query = null) {
        try {
            $query = $query == null ? $this->query . ';' : $query;
            $start = microtime();
            $stmt = self::$db->query($query);
            echo 'query took ' . (microtime() - $start) * 1000 . ' ms';
            echo $query;
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function save($table, $params, $conditions) {

    }

    public function delete($table, $conditions) {

    }

}

?>