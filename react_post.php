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

if($_POST && isset($_POST['post_id']) && isset($_POST['reaction_type'])) {
    $post->id = $_POST['post_id'];
    $reaction_type = $_POST['reaction_type'];
    
    // Verify the post exists
    if($post->getPostById($post->id)) {
        // Check current user reaction
        $current_reaction = $post->getUserReaction($_SESSION['user_id']);
        
        if($current_reaction == $reaction_type) {
            // Remove reaction if same as current
            if($post->removeReaction($_SESSION['user_id'])) {
                $response['success'] = true;
                $response['message'] = 'Reaction removed';
                $response['action'] = 'removed';
            } else {
                $response['message'] = 'Failed to remove reaction';
            }
        } else {
            // Add new reaction
            if($post->addReaction($_SESSION['user_id'], $reaction_type)) {
                $response['success'] = true;
                $response['message'] = 'Reaction added';
                $response['action'] = 'added';
            } else {
                $response['message'] = 'Failed to add reaction';
            }
        }
        
        // Get updated counts
        $post->getPostById($post->id);
        $response['likes'] = $post->likes;
        $response['dislikes'] = $post->dislikes;
        
    } else {
        $response['message'] = 'Post not found';
    }
} else {
    $response['message'] = 'Missing required parameters';
}

echo json_encode($response);
?>
