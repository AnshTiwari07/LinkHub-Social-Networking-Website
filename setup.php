<?php
// Setup script for Social Network Project
echo "<h2>Social Network Setup</h2>";

// Check PHP version
echo "<p>PHP Version: " . phpversion() . "</p>";

// Check if PDO is available
if (extension_loaded('pdo')) {
    echo "<p>✅ PDO extension is loaded</p>";
} else {
    echo "<p>❌ PDO extension is not loaded</p>";
}

// Check if PDO MySQL is available
if (extension_loaded('pdo_mysql')) {
    echo "<p>✅ PDO MySQL extension is loaded</p>";
} else {
    echo "<p>❌ PDO MySQL extension is not loaded</p>";
}

// Test database connection
try {
    require_once 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "<p>✅ Database connection successful</p>";
        
        // Check if database exists
        $stmt = $db->query("SELECT DATABASE() as db_name");
        $result = $stmt->fetch();
        echo "<p>Connected to database: " . $result['db_name'] . "</p>";
        
        // Check if tables exist
        $tables = ['users', 'posts', 'post_reactions'];
        foreach ($tables as $table) {
            $stmt = $db->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() > 0) {
                echo "<p>✅ Table '$table' exists</p>";
            } else {
                echo "<p>❌ Table '$table' does not exist</p>";
            }
        }
        
    } else {
        echo "<p>❌ Database connection failed</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
}

// Check file permissions
$upload_dirs = ['uploads', 'uploads/profiles', 'uploads/posts'];
foreach ($upload_dirs as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "<p>✅ Directory '$dir' is writable</p>";
        } else {
            echo "<p>❌ Directory '$dir' is not writable</p>";
        }
    } else {
        echo "<p>❌ Directory '$dir' does not exist</p>";
    }
}

// Check required files
$required_files = [
    'config/database.php',
    'classes/User.php',
    'classes/Post.php',
    'signup.php',
    'login.php',
    'profile.php',
    'assets/css/style.css',
    'assets/js/validation.js',
    'assets/js/profile.js'
];

echo "<h3>Required Files Check:</h3>";
foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "<p>✅ $file exists</p>";
    } else {
        echo "<p>❌ $file is missing</p>";
    }
}

echo "<h3>Setup Instructions:</h3>";
echo "<ol>";
echo "<li>Make sure MySQL is running</li>";
echo "<li>Import the database schema from sql/schema.sql</li>";
echo "<li>Update database credentials in config/database.php if needed</li>";
echo "<li>Ensure uploads directories are writable</li>";
echo "<li>Access index.php to start using the application</li>";
echo "</ol>";

echo "<p><a href='index.php'>Go to Application</a></p>";
?>





