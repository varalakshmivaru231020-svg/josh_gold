<?php
// Direct MySQL connection test
$conn = new mysqli('127.0.0.1', 'root', '', '', 3306);

if ($conn->connect_error) {
    echo "Connection FAILED: " . $conn->connect_error;
    exit;
}

echo "✓ MySQL Connection SUCCESS\n";

// Create book database if not exists
$conn->query('CREATE DATABASE IF NOT EXISTS book');
echo "✓ Database 'book' ready\n";

// Use book database
$conn->select_db('book');
echo "✓ Selected database 'book'\n";

// Check what tables exist
$result = $conn->query("SHOW TABLES");
if ($result->num_rows > 0) {
    echo "✓ Tables found: " . $result->num_rows . "\n";
    while($row = $result->fetch_array()) {
        echo "  - " . $row[0] . "\n";
    }
} else {
    echo "ℹ No tables found yet - migrations need to run\n";
}

$conn->close();
?>
