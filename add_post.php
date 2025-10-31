<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Post.php';

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$database = new Database();
$db = $database->getConnection();
$post = new Post($db);

$response = ['success' => false, 'message' => ''];

if($_POST) {
    $post->user_id = $_SESSION['user_id'];
    $post->description = $_POST['description'];
    
    // Handle file upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if(in_array($_FILES['image']['type'], $allowed_types) && 
           $_FILES['image']['size'] <= $max_size) {
            
            $upload_dir = '../uploads/posts/';
            if(!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $post->image = 'uploads/posts/' . $new_filename;
            } else {
                $response['message'] = 'Failed to upload image';
                echo json_encode($response);
                exit();
            }
        } else {
            $response['message'] = 'Invalid file type or size';
            echo json_encode($response);
            exit();
        }
    }
    
    if($post->create()) {
        $response['success'] = true;
        $response['message'] = 'Post added successfully';
        
        // Get the created post data
        $post->getPostById($post->id);
        $response['post'] = [
            'id' => $post->id,
            'description' => $post->description,
            'image' => $post->image,
            'likes' => $post->likes,
            'dislikes' => $post->dislikes,
            'created_at' => $post->created_at
        ];
    } else {
        $response['message'] = 'Failed to add post';
    }
} else {
    $response['message'] = 'No data received';
}

echo json_encode($response);
?>
