<?php
require_once __DIR__ . '/../config/database.php';

class Post {
    private $conn;
    private $table_name = "posts";
    private $reactions_table = "post_reactions";
    
    public $id;
    public $user_id;
    public $description;
    public $image;
    public $likes;
    public $dislikes;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new post
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET user_id=:user_id, description=:description, image=:image";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image = htmlspecialchars(strip_tags($this->image));
        
        // Bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":image", $this->image);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get all posts by user
    public function getPostsByUser($user_id) {
        $query = "SELECT p.*, u.full_name, u.profile_picture 
                  FROM " . $this->table_name . " p 
                  JOIN users u ON p.user_id = u.id 
                  WHERE p.user_id = ? 
                  ORDER BY p.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        
        return $stmt;
    }

    // Get single post
    public function getPostById($id) {
        $query = "SELECT p.*, u.full_name, u.profile_picture 
                  FROM " . $this->table_name . " p 
                  JOIN users u ON p.user_id = u.id 
                  WHERE p.id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->user_id = $row['user_id'];
            $this->description = $row['description'];
            $this->image = $row['image'];
            $this->likes = $row['likes'];
            $this->dislikes = $row['dislikes'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Delete post
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Add reaction (like/dislike)
    public function addReaction($user_id, $reaction_type) {
        // First, remove any existing reaction from this user
        $this->removeReaction($user_id);
        
        $query = "INSERT INTO " . $this->reactions_table . " 
                  SET post_id=:post_id, user_id=:user_id, reaction_type=:reaction_type";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":post_id", $this->id);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":reaction_type", $reaction_type);
        
        if($stmt->execute()) {
            $this->updateReactionCounts();
            return true;
        }
        return false;
    }

    // Remove reaction
    public function removeReaction($user_id) {
        $query = "DELETE FROM " . $this->reactions_table . " 
                  WHERE post_id = ? AND user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $user_id);
        
        if($stmt->execute()) {
            $this->updateReactionCounts();
            return true;
        }
        return false;
    }

    // Update reaction counts
    private function updateReactionCounts() {
        $query = "UPDATE " . $this->table_name . " 
                  SET likes = (SELECT COUNT(*) FROM " . $this->reactions_table . " 
                               WHERE post_id = ? AND reaction_type = 'like'),
                      dislikes = (SELECT COUNT(*) FROM " . $this->reactions_table . " 
                                 WHERE post_id = ? AND reaction_type = 'dislike')
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->id);
        $stmt->bindParam(3, $this->id);
        $stmt->execute();
    }

    // Check if user has reacted to post
    public function getUserReaction($user_id) {
        $query = "SELECT reaction_type FROM " . $this->reactions_table . " 
                  WHERE post_id = ? AND user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $user_id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['reaction_type'];
        }
        return null;
    }
}
?>
