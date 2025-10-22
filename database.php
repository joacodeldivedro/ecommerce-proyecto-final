<?php
class Database {
    private $host = "localhost";
    private $db_name = "ecommerce";
    private $username = "root";
    private $password = "";
    public $conn;
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
class PageCounter {
    private $conn;
    private $table = "page_views";
    public function __construct($db) {
        $this->conn = $db;
    }
    public function recordView($page_name) {
        $query = "INSERT INTO " . $this->table . " (page_name, viewed_at) VALUES (:page_name, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":page_name", $page_name);
        return $stmt->execute();
    }
    public function getTotalViews() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    public function getPageViews($page_name) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE page_name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $page_name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>