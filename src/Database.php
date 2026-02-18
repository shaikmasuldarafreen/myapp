<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'php_learning';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function __construct() {
        $this->dbConnection();
    }

    public function dbConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            // Don't display sensitive error information to users
            error_log("Database Connection Error: " . $exception->getMessage());
            die("Database connection failed. Please check your configuration or contact support.");
        }

        return $this->conn;
    }

    /**
     * Execute a query and return results as associative array
     */
    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        
        // If it's a SELECT query, return results
        if (stripos(trim($sql), 'SELECT') === 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return true;
    }

    /**
     * Insert data into a table
     */
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        return $this->query($sql, array_values($data));
    }

    /**
     * Update data in a table
     */
    public function update($table, $data, $condition, $params) {
        $set = implode(', ', array_map(function($key) {
            return "$key = ?";
        }, array_keys($data)));
        
        $sql = "UPDATE $table SET $set WHERE $condition";
        $values = array_merge(array_values($data), $params);
        
        return $this->query($sql, $values);
    }

    /**
     * Delete from a table
     */
    public function delete($table, $condition, $params) {
        $sql = "DELETE FROM $table WHERE $condition";
        
        return $this->query($sql, $params);
    }

    public function closeConnection() {
        $this->conn = null;
    }
}
?>