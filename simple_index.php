<?php
// Simple index file to test basic functionality
header('Content-Type: text/html; charset=UTF-8');

echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Social Network - Test</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }";
echo ".container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
echo "h1 { color: #2c3e50; }";
echo ".success { color: #27ae60; }";
echo ".error { color: #e74c3c; }";
echo ".btn { background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }";
echo ".btn:hover { background: #2980b9; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<div class='container'>";
echo "<h1>🚀 Social Network - System Test</h1>";

// Test 1: Basic PHP
echo "<h2>1. PHP Test</h2>";
echo "<p class='success'>✅ PHP is working! Version: " . phpversion() . "</p>";

// Test 2: File system
echo "<h2>2. File System Test</h2>";
if (is_readable('.')) {
    echo "<p class='success'>✅ Current directory is readable</p>";
} else {
    echo "<p class='error'>❌ Current directory is not readable</p>";
}

// Test 3: Required files
echo "<h2>3. Required Files Test</h2>";
$required_files = [
    'config/database.php' => 'Database configuration',
    'classes/User.php' => 'User class',
    'classes/Post.php' => 'Post class',
    'signup.php' => 'Signup page',
    'login.php' => 'Login page',
    'profile.php' => 'Profile page'
];

foreach ($required_files as $file => $description) {
    if (file_exists($file)) {
        echo "<p class='success'>✅ $description ($file)</p>";
    } else {
        echo "<p class='error'>❌ Missing: $description ($file)</p>";
    }
}

// Test 4: Database connection
echo "<h2>4. Database Connection Test</h2>";
try {
    require_once 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "<p class='success'>✅ Database connection successful</p>";
        
        // Test query
        $stmt = $db->query("SELECT 1 as test");
        $result = $stmt->fetch();
        if ($result['test'] == 1) {
            echo "<p class='success'>✅ Database query successful</p>";
        } else {
            echo "<p class='error'>❌ Database query failed</p>";
        }
    } else {
        echo "<p class='error'>❌ Database connection failed</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Database error: " . $e->getMessage() . "</p>";
}

// Test 5: Upload directories
echo "<h2>5. Upload Directories Test</h2>";
$upload_dirs = ['uploads', 'uploads/profiles', 'uploads/posts'];
foreach ($upload_dirs as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "<p class='success'>✅ $dir is writable</p>";
        } else {
            echo "<p class='error'>❌ $dir is not writable</p>";
        }
    } else {
        echo "<p class='error'>❌ $dir does not exist</p>";
    }
}

// Test 6: Extensions
echo "<h2>6. PHP Extensions Test</h2>";
$required_extensions = ['pdo', 'pdo_mysql'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p class='success'>✅ $ext extension loaded</p>";
    } else {
        echo "<p class='error'>❌ $ext extension not loaded</p>";
    }
}

echo "<h2>🎯 Next Steps</h2>";
echo "<p>If all tests pass, your system is ready!</p>";

echo "<div style='margin-top: 30px;'>";
echo "<a href='index.html' class='btn'>🏠 Go to HTML Version</a>";
echo "<a href='index.php' class='btn'>🚀 Go to PHP Version</a>";
echo "<a href='debug.php' class='btn'>🔧 Run Full Debug</a>";
echo "<a href='init_database.php' class='btn'>🗄️ Initialize Database</a>";
echo "</div>";

echo "</div>";
echo "</body>";
echo "</html>";
?>





