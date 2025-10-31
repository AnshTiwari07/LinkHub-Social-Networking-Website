<?php
// Simple test to check if PHP is working
echo "<h1>PHP is Working!</h1>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test basic functionality
if (function_exists('mysqli_connect')) {
    echo "<p>✅ MySQL extension is available</p>";
} else {
    echo "<p>❌ MySQL extension not found</p>";
}

if (class_exists('PDO')) {
    echo "<p>✅ PDO is available</p>";
} else {
    echo "<p>❌ PDO not found</p>";
}

echo "<p><a href='index.php'>Try index.php</a></p>";
echo "<p><a href='debug.php'>Run debug script</a></p>";
?>





