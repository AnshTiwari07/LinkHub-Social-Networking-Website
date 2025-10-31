<?php
// Fix for HTTP 405 Error
echo "<h1>HTTP 405 Error Fix</h1>";

// Check server information
echo "<h2>Server Information:</h2>";
echo "<p><strong>Server Software:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p><strong>Request Method:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
echo "<p><strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>HTTP Host:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";

// Check if we can access files
echo "<h2>File Access Test:</h2>";
$files_to_check = [
    'index.php',
    'index.html', 
    'login.php',
    'signup.php',
    'profile.php',
    'config/database.php',
    'classes/User.php'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "<p>✅ $file exists</p>";
    } else {
        echo "<p>❌ $file missing</p>";
    }
}

// Check directory permissions
echo "<h2>Directory Permissions:</h2>";
$dirs_to_check = [
    '.',
    'uploads',
    'uploads/profiles',
    'uploads/posts',
    'config',
    'classes',
    'api'
];

foreach ($dirs_to_check as $dir) {
    if (is_dir($dir)) {
        if (is_readable($dir)) {
            echo "<p>✅ $dir is readable</p>";
        } else {
            echo "<p>❌ $dir is not readable</p>";
        }
    } else {
        echo "<p>❌ $dir is not a directory</p>";
    }
}

// Test basic PHP functionality
echo "<h2>PHP Functionality Test:</h2>";
try {
    require_once 'config/database.php';
    echo "<p>✅ Database config loaded</p>";
} catch (Exception $e) {
    echo "<p>❌ Database config error: " . $e->getMessage() . "</p>";
}

// Check if we can create a simple file
$test_file = 'test_write.txt';
if (file_put_contents($test_file, 'test') !== false) {
    echo "<p>✅ Can write files</p>";
    unlink($test_file);
} else {
    echo "<p>❌ Cannot write files</p>";
}

echo "<h2>Solutions:</h2>";
echo "<ol>";
echo "<li><strong>If using Apache:</strong> Check .htaccess file is present and correct</li>";
echo "<li><strong>If using IIS:</strong> Check web.config file is present and correct</li>";
echo "<li><strong>Check file permissions:</strong> Ensure web server can read all files</li>";
echo "<li><strong>Check PHP configuration:</strong> Ensure PHP is properly configured</li>";
echo "<li><strong>Check web server logs:</strong> Look for specific error messages</li>";
echo "</ol>";

echo "<h2>Quick Tests:</h2>";
echo "<p><a href='test.php'>Test PHP</a></p>";
echo "<p><a href='index.html'>Test HTML</a></p>";
echo "<p><a href='index.php'>Test PHP Index</a></p>";

// Check for common issues
echo "<h2>Common Issues:</h2>";

if (!file_exists('.htaccess') && !file_exists('web.config')) {
    echo "<p>⚠️ No server configuration file found</p>";
}

if (!is_dir('uploads')) {
    echo "<p>⚠️ Uploads directory missing</p>";
}

if (!function_exists('mysqli_connect') && !class_exists('PDO')) {
    echo "<p>⚠️ No database extensions found</p>";
}

echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Try accessing <a href='test.php'>test.php</a> first</li>";
echo "<li>If test.php works, try <a href='index.html'>index.html</a></li>";
echo "<li>If HTML works, try <a href='index.php'>index.php</a></li>";
echo "<li>Check your web server configuration</li>";
echo "<li>Check PHP error logs</li>";
echo "</ol>";
?>


