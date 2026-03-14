<?php
// Connect without password first
try {
    // Try local socket connection
    $conn = mysqli_connect('127.0.0.1', 'root', '', '', 3306);
    
    if (!$conn) {
        echo "Trying different connection method...\n";
        $conn = mysqli_connect('localhost', 'root', '');
        
        if (!$conn) {
            echo "✗ Cannot connect to MySQL\n";
            echo "Error: " . mysqli_connect_error() . "\n";
            exit(1);
        }
    }
    
    echo "✓ Connected to MySQL\n";
    
    // Create book database
    if (!$conn->query('CREATE DATABASE IF NOT EXISTS book')) {
        echo "Error creating database: " . $conn->error . "\n";
    } else {
        echo "✓ Database 'book' ready\n";
    }
    
    // Ensure root has no password (allow connections from localhost with no password)
    $conn->query("GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' IDENTIFIED BY '' WITH GRANT OPTION");
    $conn->query("GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY '' WITH GRANT OPTION");
    $conn->query("FLUSH PRIVILEGES");
    
    echo "✓ Permissions set for root user\n";
    
    $conn->close();
    echo "\n✓ MySQL is ready for Laravel!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
