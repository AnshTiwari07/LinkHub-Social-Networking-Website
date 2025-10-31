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

if($_POST && isset($_POST['post_id'])) {
    $post->id = $_POST['post_id'];
    
    // Verify the post belongs to the current user
    if($post->getPostById($post->id) && $post->user_id == $_SESSION['user_id']) {
        if($post->delete()) {
            $response['success'] = true;
            $response['message'] = 'Post deleted successfully';
        } else {
            $response['message'] = 'Failed to delete post';
        }
    } else {
        $response['message'] = 'Post not found or access denied';
    }
} else {
    $response['message'] = 'Post ID not provided';
}

echo json_encode($response);
?>
