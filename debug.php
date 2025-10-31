<?php
// Debug script to identify issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Social Network Debug</h1>";

// Test 1: Check if we can include files
echo "<h2>1. File Inclusion Test</h2>";
try {
    require_once 'config/database.php';
    echo "✅ config/database.php loaded<br>";
} catch (Exception $e) {
    echo "❌ Error loading config/database.php: " . $e->getMessage() . "<br>";
}

try {
    require_once 'classes/User.php';
    echo "✅ classes/User.php loaded<br>";
} catch (Exception $e) {
    echo "❌ Error loading classes/User.php: " . $e->getMessage() . "<br>";
}

try {
    require_once 'classes/Post.php';
    echo "✅ classes/Post.php loaded<br>";
} catch (Exception $e) {
    echo "❌ Error loading classes/Post.php: " . $e->getMessage() . "<br>";
}

// Test 2: Database connection
echo "<h2>2. Database Connection Test</h2>";
try {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "✅ Database connection successful<br>";
        
        // Test if we can query
        $stmt = $db->query("SELECT 1 as test");
        $result = $stmt->fetch();
        if ($result['test'] == 1) {
            echo "✅ Database query successful<br>";
        } else {
            echo "❌ Database query failed<br>";
        }
    } else {
        echo "❌ Database connection failed<br>";
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// Test 3: Check if database and tables exist
echo "<h2>3. Database Structure Test</h2>";
try {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        // Check if database exists
        $stmt = $db->query("SELECT DATABASE() as db_name");
        $result = $stmt->fetch();
        echo "Current database: " . $result['db_name'] . "<br>";
        
        // Check tables
        $tables = ['users', 'posts', 'post_reactions'];
        foreach ($tables as $table) {
            $stmt = $db->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() > 0) {
                echo "✅ Table '$table' exists<br>";
            } else {
                echo "❌ Table '$table' does not exist<br>";
            }
        }
    }
} catch (Exception $e) {
    echo "❌ Database structure error: " . $e->getMessage() . "<br>";
}

// Test 4: File permissions
echo "<h2>4. File Permissions Test</h2>";
$dirs = ['uploads', 'uploads/profiles', 'uploads/posts'];
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "✅ Directory '$dir' is writable<br>";
        } else {
            echo "❌ Directory '$dir' is not writable<br>";
        }
    } else {
        echo "❌ Directory '$dir' does not exist<br>";
    }
}

// Test 5: Test User class methods
echo "<h2>5. User Class Test</h2>";
try {
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);
    
    // Test email validation
    if ($user->validateEmail('test@example.com')) {
        echo "✅ Email validation works<br>";
    } else {
        echo "❌ Email validation failed<br>";
    }
    
    // Test age validation
    if ($user->validateAge(25)) {
        echo "✅ Age validation works<br>";
    } else {
        echo "❌ Age validation failed<br>";
    }
    
    // Test password validation
    if ($user->validatePassword('password123')) {
        echo "✅ Password validation works<br>";
    } else {
        echo "❌ Password validation failed<br>";
    }
    
} catch (Exception $e) {
    echo "❌ User class error: " . $e->getMessage() . "<br>";
}

// Test 6: Session test
echo "<h2>6. Session Test</h2>";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
echo "✅ Session started<br>";

// Test 7: Check for common issues
echo "<h2>7. Common Issues Check</h2>";

// Check if we're in the right directory
if (file_exists('config/database.php')) {
    echo "✅ We're in the correct directory<br>";
} else {
    echo "❌ config/database.php not found - wrong directory?<br>";
}

// Check PHP version
if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
    echo "✅ PHP version is adequate: " . PHP_VERSION . "<br>";
} else {
    echo "❌ PHP version too old: " . PHP_VERSION . " (need 7.4+)<br>";
}

// Check for required extensions
$required_extensions = ['pdo', 'pdo_mysql', 'gd', 'fileinfo'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ Extension '$ext' is loaded<br>";
    } else {
        echo "❌ Extension '$ext' is not loaded<br>";
    }
}

echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li>If database connection failed, check your MySQL server is running</li>";
echo "<li>If tables don't exist, import sql/schema.sql</li>";
echo "<li>If file permissions failed, make uploads directories writable</li>";
echo "<li>If extensions are missing, install them via your PHP installation</li>";
echo "</ol>";

echo "<p><a href='index.php'>Go to Application</a> | <a href='setup.php'>Run Setup</a></p>";
?>





