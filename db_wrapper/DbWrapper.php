<?php

require_once('config/database.php');

class DbWrapper {
    private static $db, $instance;
    private $query;

    private function __construct() {
    }

    public static function getInstance() {
        if (self::$instance === null) {
            try {
                ini_set('display_errors', 1);

                $dbConfig = new DB_CONFIG();

                self::$db = new PDO(
                    "{$dbConfig::$dbParams['datasource']}:
                    host={$dbConfig::$dbParams['host']};
                    dbname={$dbConfig::$dbParams['database']}",
                    $dbConfig::$dbParams['login'],
                    $dbConfig::$dbParams['password']
                );
                self::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // disable emulation of prepared statement
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set error reporting for PDO
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
        $this->query .= $this->getWhereString($conditions);
        return $this;
    }

    public function limit($limit, $offset = null) {
        settype($limit, 'integer'); //to prevent sql injection
        $offset != null ? settype($offset, 'integer') : ''; //to prevent sql injection
        $this->query .= " LIMIT $limit" . ($offset != null ? ", $offset" : '');
        return $this;
    }

    public function orderBy($fieldName, $order = 'ASC') {
        $this->query .= " ORDER BY $fieldName $order";
        return $this;
    }

    public function getQuery() {
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

    public function save($table, $params, $conditions = null) {
        if ($conditions) {
            $this->update($table, $params, $conditions);
        } else {
            $this->insert($table, $params);
        }

    }

    public function delete($table, $conditions = null) {
        $this->query = "DELETE FROM $table" . ($conditions != null ? $this->getWhereString($conditions) : '');
        return $this;
    }

    function getWhereString($conditions) {
        $whereStr = ' WHERE ';
        $operator = 'AND';
        $cnd = $conditions;

        if (isset($conditions['OR'])) {
            $cnd = $conditions['OR'];
        }
        foreach ($cnd as $key => $condition) {
            $whereStr .= $key . $condition . " $operator ";
        }
        return rtrim($whereStr, " $operator");
    }

    function insert($table, $params) {
        $this->query = "INSERT INTO $table ";
        $values = "";
        foreach ($params as $key => $param) {
            $values .= ":$key ,";
        }
        $cols = implode(array_keys($params), ',');
        $values = rtrim($values, ' ,');
        $this->query .= " ($cols) VALUES ($values);";
        $stmt = self::$db->prepare($this->query);
        foreach ($params as $key => $param) {
            $stmt->bindParam(":{$key}", $param);
        }
        if($stmt->execute()){
            throw new Exception('Error in saving.');
        }
        return true;
    }

    function update($table, $params, $conditions) {
        $this->query = "UPDATE $table set ";
        foreach ($params as $key => $param) {
            $this->query .= $key . $param . ' ,';
        }
        $this->query = rtrim($this->query, ',');
        $this->query .= $this->getWhereString($conditions);
        echo $this->query;
    }

}

?>