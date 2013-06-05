<?php

require_once('config/database.php');

class DbWrapper {
    private static $db, $instance;
    private $query;

    private function __construct() {
    }

    /**
     * Get instance of DbWrapper class
     * @return object DbWrapper
     */
    public static function getInstance() {
        if (self::$instance === null) {
            try {
                $dsn = DB_CONFIG::$dbParams['datasource'].':dbname='.DB_CONFIG::$dbParams['database'].';host='.DB_CONFIG::$dbParams['host'];
                $user = DB_CONFIG::$dbParams['login'];
                $password = DB_CONFIG::$dbParams['password'];

                self::$db = new PDO($dsn,$user,$password);
                self::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // disable emulation of prepared statement
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set error reporting for PDO
                self::$instance = new DbWrapper();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        return self::$instance;
    }

    /**
     * Create select statement
     * @param array of select fields or empty
     * @return object DbWrapper
     */
    public function select($fields = '*') {
        if (is_array($fields)) {
            $this->query = 'SELECT ' . implode(', ', $fields);
        } else {
            $this->query = 'SELECT *';
        }
        return $this;
    }

    /**
     * Create from statement
     * @param array of table names
     * @return object DbWrapper
     */
    public function from($tableNames) {
        $this->query .= ' FROM ' . implode(', ', $tableNames);
        return $this;
    }

    /**
     * Create where string
     * @param array of conditions
     * @return object DbWrapper
     */
    public function where($conditions) {
        $this->query .= $this->getWhereString($conditions);
        return $this;
    }

    /**
     * Append limit and offset to query
     * @param integer limit
     * @param integer offset
     * @return object DbWrapper
     */
    public function limit($limit, $offset = null) {
        settype($limit, 'integer'); //to prevent sql injection
        $offset != null ? settype($offset, 'integer') : ''; //to prevent sql injection
        $this->query .= " LIMIT $limit" . ($offset != null ? ", $offset" : '');
        return $this;
    }

    /**
     * Append order by to query
     * @param string field name
     * @return object DbWrapper
     */
    public function orderBy($fieldName, $order = 'ASC') {
        $this->query .= " ORDER BY $fieldName $order";
        return $this;
    }

    /**
     * Return current query
     * @return string query
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * Get result of query
     * @param string query
     * @return array result
     */
    public function result($query = null) {
        try {
            $query = $query == null ? $this->query . ';' : $query;
            $start = microtime();
            $stmt = self::$db->query($query);
            echo 'query took ' . (microtime() - $start) * 1000 . ' ms<br>';
            echo $query;
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Run update/insert query
     * @param string table name
     * @param array params
     * @param array conditions
     */
    public function save($table, $params, $conditions = null) {
        if ($conditions) {
            $this->update($table, $params, $conditions);
        } else {
            $this->insert($table, $params);
        }

    }

    /**
     * Run delete query
     * @param string table name
     * @param array conditions
     * @return true on success
     */
    public function delete($table, $conditions = null) {
        $this->query = "DELETE FROM $table" . ($conditions != null ? $this->getWhereString($conditions) : '');
        echo $this->query;
        try {
            $stmt = self::$db->prepare($this->query);
            if (!$stmt->execute()) {
                throw new Exception('Error in deleting.');
            }
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Get where string
     * @param array conditions
     * @return string
     */
    function getWhereString($conditions) {
        $whereStr = ' WHERE ';
        $operator = 'AND';
        $cnd = $conditions;

        if (isset($conditions['OR'])) {
            $cnd = $conditions['OR'];
            $operator = 'OR';
        }
        if (isset($conditions['between'])) {
            $whereStr .= $conditions['between'][0] . " BETWEEN " . $conditions['between'][1] . ' AND ' . $conditions['between'][2];
            return $whereStr;
        }
        foreach ($cnd as $key => $condition) {
            $whereStr .= $key . $condition . " $operator ";
        }
        return rtrim($whereStr, " $operator");
    }

    /**
     * Run insert query
     * @param string table name
     * @param array params
     * @return true on success
     */
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
            $stmt->bindValue(":{$key}", $param);
        }
        if (!$stmt->execute()) {
            throw new Exception('Error in saving.');
        }
        return true;
    }

    /**
     * Run update query
     * @param string table name
     * @param array params
     * @param array conditions
     * @return true on success
     */
    function update($table, $params, $conditions) {
        $this->query = "UPDATE $table set ";
        foreach ($params as $key => $param) {
            $this->query .= $key . $param . ' ,';
        }
        $this->query = rtrim($this->query, ',');
        $this->query .= $this->getWhereString($conditions);
        echo $this->query;
        try {
            $stmt = self::$db->prepare($this->query);
            if (!$stmt->execute()) {
                throw new Exception('Error in saving.');
            }
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Get group by query
     * @param string field name
     * @return string
     */
    public function groupBy($field) {
        $this->query .= " group by $field";
        return $this;
    }
}

?>