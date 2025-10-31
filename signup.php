<?php
session_start();
require_once 'config/database.php';
require_once 'classes/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$message = '';
$error = '';

if($_POST) {
    $user->full_name = $_POST['full_name'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    $user->age = $_POST['age'];
    
    // Validate inputs
    if(!$user->validateEmail($user->email)) {
        $error = "Please enter a valid email address.";
    } elseif(!$user->validateAge($user->age)) {
        $error = "Please enter a valid age.";
    } elseif(!$user->validatePassword($user->password)) {
        $error = "Password must be at least 6 characters long.";
    } elseif($user->emailExists()) {
        $error = "Email already exists. Please use a different email.";
    } else {
        // Handle file upload
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
                } else {
                    $error = "Failed to upload profile picture.";
                }
            } else {
                $error = "Invalid file type or size. Please upload a JPEG, PNG, or GIF image under 5MB.";
            }
        } else {
            // Create a simple default profile picture if it doesn't exist
            $default_path = 'uploads/profiles/default.png';
            if (!file_exists($default_path)) {
                // Create a simple 1x1 transparent PNG
                $default_image = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
                file_put_contents($default_path, $default_image);
            }
            $user->profile_picture = $default_path;
        }
        
        if(empty($error)) {
            if($user->create()) {
                $message = "Registration successful! Please login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Social Network</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="auth-form">
            <h2>Sign Up</h2>
            
            <?php if($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" id="signupForm">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" required>
                    <span class="error" id="full_name_error"></span>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    <span class="error" id="email_error"></span>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <span class="error" id="password_error"></span>
                </div>
                
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" min="1" max="150" required>
                    <span class="error" id="age_error"></span>
                </div>
                
                <div class="form-group">
                    <label for="profile_picture">Profile Picture</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                    <span class="error" id="profile_picture_error"></span>
                </div>
                
                <button type="submit" class="btn btn-primary">Sign Up</button>
            </form>
            
            <p class="auth-link">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/validation.js"></script>
</body>
</html>
