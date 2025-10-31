<?php
session_start();
require_once 'config/database.php';
require_once 'classes/User.php';
require_once 'classes/Post.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$post = new Post($db);

// Get user data
$user->getUserById($_SESSION['user_id']);

$message = '';
$error = '';

// Handle profile update
if($_POST && isset($_POST['action']) && $_POST['action'] == 'update_profile') {
    $user->full_name = $_POST['full_name'];
    $user->age = $_POST['age'];
    
    // Handle profile picture upload
    if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if(in_array($_FILES['profile_picture']['type'], $allowed_types) && 
           $_FILES['profile_picture']['size'] <= $max_size) {
            
            $upload_dir = 'uploads/profiles/';
            if(!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if(move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                $user->profile_picture = $upload_path;
            }
        } else {
            $error = "Invalid file type or size. Please upload a JPEG, PNG, or GIF image under 5MB.";
        }
    }
    
    if(empty($error)) {
        if($user->updateProfile()) {
            $message = "Profile updated successfully!";
        } else {
            $error = "Failed to update profile.";
        }
    }
}

// Get user posts
$posts_stmt = $post->getPostsByUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Social Network</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Social Network</h1>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($user->full_name); ?>!</span>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </header>
        
        <div class="profile-section">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-picture">
                        <img src="<?php echo htmlspecialchars($user->profile_picture ?: 'uploads/profiles/default.png'); ?>" 
                             alt="Profile Picture" id="profile-img">
                    </div>
                    <div class="profile-info">
                        <h2 id="profile-name"><?php echo htmlspecialchars($user->full_name); ?></h2>
                        <p id="profile-email"><?php echo htmlspecialchars($user->email); ?></p>
                        <p id="profile-age">Age: <span id="age-value"><?php echo htmlspecialchars($user->age); ?></span></p>
                    </div>
                </div>
                
                <div class="profile-actions">
                    <button class="btn btn-primary" id="edit-profile-btn">Edit Profile</button>
                </div>
            </div>
            
            <!-- Edit Profile Modal -->
            <div class="modal" id="edit-profile-modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>Edit Profile</h3>
                    
                    <?php if($message): ?>
                        <div class="alert alert-success"><?php echo $message; ?></div>
                    <?php endif; ?>
                    
                    <?php if($error): ?>
                        <div class="alert alert-error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data" id="edit-profile-form">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="form-group">
                            <label for="edit_full_name">Full Name</label>
                            <input type="text" id="edit_full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($user->full_name); ?>" required>
                            <span class="error" id="edit_full_name_error"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_age">Age</label>
                            <input type="number" id="edit_age" name="age" 
                                   value="<?php echo htmlspecialchars($user->age); ?>" 
                                   min="1" max="150" required>
                            <span class="error" id="edit_age_error"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_profile_picture">Profile Picture</label>
                            <input type="file" id="edit_profile_picture" name="profile_picture" accept="image/*">
                            <span class="error" id="edit_profile_picture_error"></span>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="posts-section">
            <div class="posts-header">
                <h3>My Posts</h3>
                <button class="btn btn-primary" id="add-post-btn">Add New Post</button>
            </div>
            
            <!-- Add Post Modal -->
            <div class="modal" id="add-post-modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>Add New Post</h3>
                    
                    <form id="add-post-form" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="post_description">Description</label>
                            <textarea id="post_description" name="description" rows="4" required></textarea>
                            <span class="error" id="post_description_error"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="post_image">Image (Optional)</label>
                            <input type="file" id="post_image" name="image" accept="image/*">
                            <span class="error" id="post_image_error"></span>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Add Post</button>
                    </form>
                </div>
            </div>
            
            <div class="posts-container" id="posts-container">
                <?php while($post_row = $posts_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="post-card" data-post-id="<?php echo $post_row['id']; ?>">
                        <div class="post-header">
                            <div class="post-user-info">
                                <img src="<?php echo htmlspecialchars($post_row['profile_picture']); ?>" 
                                     alt="Profile Picture" class="post-user-avatar">
                                <div>
                                    <h4><?php echo htmlspecialchars($post_row['full_name']); ?></h4>
                                    <small><?php echo date('M j, Y g:i A', strtotime($post_row['created_at'])); ?></small>
                                </div>
                            </div>
                            <button class="btn btn-danger btn-sm delete-post" data-post-id="<?php echo $post_row['id']; ?>">Delete</button>
                        </div>
                        
                        <div class="post-content">
                            <p><?php echo nl2br(htmlspecialchars($post_row['description'])); ?></p>
                            <?php if($post_row['image']): ?>
                                <img src="<?php echo htmlspecialchars($post_row['image']); ?>" 
                                     alt="Post Image" class="post-image">
                            <?php endif; ?>
                        </div>
                        
                        <div class="post-actions">
                            <button class="btn btn-like" data-post-id="<?php echo $post_row['id']; ?>" 
                                    data-reaction="like">
                                👍 Like (<span class="like-count"><?php echo $post_row['likes']; ?></span>)
                            </button>
                            <button class="btn btn-dislike" data-post-id="<?php echo $post_row['id']; ?>" 
                                    data-reaction="dislike">
                                👎 Dislike (<span class="dislike-count"><?php echo $post_row['dislikes']; ?></span>)
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/profile.js"></script>
</body>
</html>
