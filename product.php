<?php
class product {
    private $conn;
    private $table = "products";  // Tabla en inglés
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $image;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function read() {
        $query = "SELECT p.*, c.name as category_name FROM " . $this->table . " p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.id DESC";  // Sin created_at
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function readById($id) {
        $query = "SELECT p.*, c.name as category_name FROM " . $this->table . " p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                 SET name=:name, description=:description, price=:price, category_id=:category_id, image=:image";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":image", $this->image);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function update() {
        $query = "UPDATE " . $this->table . " 
                 SET name=:name, description=:description, price=:price, category_id=:category_id, image=:image 
                 WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":id", $this->id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function uploadImage($file) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $new_filename;
        
        $check = getimagesize($file["tmp_name"]);
        if($check === false) {
            return false;
        }
        
        if ($file["size"] > 2000000) {
            return false;
        }
        
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            return false;
        }
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $new_filename;
        }
        return false;
    }
}
?>