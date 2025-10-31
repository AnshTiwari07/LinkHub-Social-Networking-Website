<?php
// PHP Installation Checker
echo "<!DOCTYPE html>";
echo "<html><head><title>PHP Installation Check</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
echo ".container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
echo ".success { color: #27ae60; font-weight: bold; }";
echo ".error { color: #e74c3c; font-weight: bold; }";
echo ".warning { color: #f39c12; font-weight: bold; }";
echo ".info { color: #3498db; }";
echo "h1 { color: #2c3e50; }";
echo "h2 { color: #34495e; border-bottom: 2px solid #3498db; padding-bottom: 5px; }";
echo ".btn { background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px; }";
echo ".btn:hover { background: #2980b9; }";
echo "</style></head><body>";

echo "<div class='container'>";
echo "<h1>🔍 PHP Installation Checker</h1>";

// Basic PHP Info
echo "<h2>1. PHP Version & Basic Info</h2>";
echo "<p class='info'><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p class='info'><strong>Server Software:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "<p class='info'><strong>Document Root:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</p>";

// Check PHP version
$php_version = phpversion();
$version_parts = explode('.', $php_version);
$major_version = (int)$version_parts[0];
$minor_version = (int)$version_parts[1];

if ($major_version >= 8) {
    echo "<p class='success'>✅ PHP 8.x - Excellent!</p>";
} elseif ($major_version >= 7 && $minor_version >= 4) {
    echo "<p class='success'>✅ PHP 7.4+ - Good!</p>";
} elseif ($major_version >= 7) {
    echo "<p class='warning'>⚠️ PHP 7.x - Acceptable but consider upgrading</p>";
} else {
    echo "<p class='error'>❌ PHP version too old - Please upgrade to PHP 7.4+</p>";
}

// Check required extensions
echo "<h2>2. Required Extensions</h2>";
$required_extensions = [
    'pdo' => 'PDO (PHP Data Objects)',
    'pdo_mysql' => 'PDO MySQL Driver',
    'mysqli' => 'MySQL Improved Extension',
    'gd' => 'GD Library (for image processing)',
    'fileinfo' => 'File Information',
    'json' => 'JSON Support',
    'session' => 'Session Support',
    'openssl' => 'OpenSSL Support'
];

$all_extensions_ok = true;
foreach ($required_extensions as $ext => $description) {
    if (extension_loaded($ext)) {
        echo "<p class='success'>✅ $description ($ext)</p>";
    } else {
        echo "<p class='error'>❌ Missing: $description ($ext)</p>";
        $all_extensions_ok = false;
    }
}

// Check file permissions
echo "<h2>3. File System Permissions</h2>";
$current_dir = getcwd();
if (is_readable($current_dir)) {
    echo "<p class='success'>✅ Current directory is readable</p>";
} else {
    echo "<p class='error'>❌ Current directory is not readable</p>";
}

if (is_writable($current_dir)) {
    echo "<p class='success'>✅ Current directory is writable</p>";
} else {
    echo "<p class='warning'>⚠️ Current directory is not writable (may cause issues)</p>";
}

// Check upload directories
$upload_dirs = ['uploads', 'uploads/profiles', 'uploads/posts'];
foreach ($upload_dirs as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "<p class='success'>✅ $dir is writable</p>";
        } else {
            echo "<p class='error'>❌ $dir is not writable</p>";
        }
    } else {
        echo "<p class='warning'>⚠️ $dir does not exist</p>";
    }
}

// Check configuration
echo "<h2>4. PHP Configuration</h2>";
$upload_max = ini_get('upload_max_filesize');
$post_max = ini_get('post_max_size');
$max_execution = ini_get('max_execution_time');
$memory_limit = ini_get('memory_limit');

echo "<p class='info'><strong>Upload Max Filesize:</strong> $upload_max</p>";
echo "<p class='info'><strong>Post Max Size:</strong> $post_max</p>";
echo "<p class='info'><strong>Max Execution Time:</strong> $max_execution seconds</p>";
echo "<p class='info'><strong>Memory Limit:</strong> $memory_limit</p>";

// Check if settings are adequate
if (intval($upload_max) >= 5) {
    echo "<p class='success'>✅ Upload limit is adequate</p>";
} else {
    echo "<p class='warning'>⚠️ Upload limit may be too low (current: $upload_max)</p>";
}

// Test database connection
echo "<h2>5. Database Connection Test</h2>";
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

// Overall status
echo "<h2>6. Overall Status</h2>";
if ($all_extensions_ok && $php_version >= '7.4') {
    echo "<p class='success'>🎉 Your PHP installation looks good!</p>";
    echo "<p class='info'>You can proceed with the project setup.</p>";
} else {
    echo "<p class='error'>❌ Your PHP installation needs attention.</p>";
    echo "<p class='info'>Please fix the issues above before proceeding.</p>";
}

// Recommendations
echo "<h2>7. Recommendations</h2>";
echo "<ul>";
echo "<li><strong>For Development:</strong> Use XAMPP or WAMP for easy setup</li>";
echo "<li><strong>For Production:</strong> Use a proper web server with PHP</li>";
echo "<li><strong>Security:</strong> Disable phpinfo() in production</li>";
echo "<li><strong>Performance:</strong> Enable OPcache for better performance</li>";
echo "</ul>";

// Next steps
echo "<h2>8. Next Steps</h2>";
echo "<div>";
echo "<a href='simple_index.php' class='btn'>Test Project</a>";
echo "<a href='init_database.php' class='btn'>Setup Database</a>";
echo "<a href='debug.php' class='btn'>Full Debug</a>";
echo "</div>";

echo "</div>";
echo "</body></html>";
?>





