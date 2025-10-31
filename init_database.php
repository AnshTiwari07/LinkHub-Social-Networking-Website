<?php
// Database initialization script
require_once 'config/database.php';

echo "<h2>Database Initialization</h2>";

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception("Database connection failed");
    }
    
    echo "✅ Database connection successful<br>";
    
    // Read and execute schema
    $schema = file_get_contents('sql/schema.sql');
    if (!$schema) {
        throw new Exception("Could not read schema file");
    }
    
    // Split into individual statements
    $statements = explode(';', $schema);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            try {
                $db->exec($statement);
                echo "✅ Executed: " . substr($statement, 0, 50) . "...<br>";
            } catch (PDOException $e) {
                // Ignore "table already exists" errors
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "⚠️ Warning: " . $e->getMessage() . "<br>";
                }
            }
        }
    }
    
    echo "<br>✅ Database initialization complete!<br>";
    echo "<p><a href='index.php'>Go to Application</a></p>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "<p>Please check your database configuration in config/database.php</p>";
}
?>





