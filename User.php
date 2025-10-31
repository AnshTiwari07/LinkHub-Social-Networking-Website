<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table_name = "users";
    
    public $id;
    public $full_name;
    public $email;
    public $password;
    public $age;
    public $profile_picture;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new user
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET full_name=:full_name, email=:email, password=:password, 
                      age=:age, profile_picture=:profile_picture";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->age = htmlspecialchars(strip_tags($this->age));
        $this->profile_picture = htmlspecialchars(strip_tags($this->profile_picture));
        
        // Hash password
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        
        // Bind values
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":age", $this->age);
        $stmt->bindParam(":profile_picture", $this->profile_picture);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Check if email exists
    public function emailExists() {
        $query = "SELECT id, full_name, password, age, profile_picture 
                  FROM " . $this->table_name . " 
                  WHERE email = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        
        $num = $stmt->rowCount();
        
        if($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->full_name = $row['full_name'];
            $this->password = $row['password'];
            $this->age = $row['age'];
            $this->profile_picture = $row['profile_picture'];
            return true;
        }
        return false;
    }

    // Login user
    public function login($email, $password) {
        $this->email = $email;
        
        if($this->emailExists()) {
            if(password_verify($password, $this->password)) {
                return true;
            }
        }
        return false;
    }

    // Get user by ID
    public function getUserById($id) {
        $query = "SELECT id, full_name, email, age, profile_picture, created_at 
                  FROM " . $this->table_name . " 
                  WHERE id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->full_name = $row['full_name'];
            $this->email = $row['email'];
            $this->age = $row['age'];
            $this->profile_picture = $row['profile_picture'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Update user profile
    public function updateProfile() {
        $query = "UPDATE " . $this->table_name . " 
                  SET full_name=:full_name, age=:age, profile_picture=:profile_picture 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->age = htmlspecialchars(strip_tags($this->age));
        $this->profile_picture = htmlspecialchars(strip_tags($this->profile_picture));
        
        // Bind values
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":age", $this->age);
        $stmt->bindParam(":profile_picture", $this->profile_picture);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Validate email format
    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Validate age
    public function validateAge($age) {
        return is_numeric($age) && $age > 0 && $age < 150;
    }

    // Validate password strength
    public function validatePassword($password) {
        return strlen($password) >= 6;
    }
}
?>
