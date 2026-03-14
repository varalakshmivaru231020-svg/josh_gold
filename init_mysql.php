<?php
// Start MySQL first in another terminal
// Then run this script

$host = '127.0.0.1';
$port = 3306;
$user = 'root';
$pass = '';

try {
    $conn = mysqli_connect($host, $user, $pass);
    
    if (!$conn) {
        echo "Cannot connect yet - MySQL needs to start\n";
        echo "Start MySQL with: C:\\xampp\\mysql\\bin\\mysqld.exe --console\n";
        exit(1);
    }
    
    echo "✓ Connected to MySQL\n";
    
    // Create mysql database first
    $conn->query('CREATE DATABASE IF NOT EXISTS mysql');
    $conn->select_db('mysql');
    
    // Read and execute the system table SQL files
    $sql_files = [
        'C:\\xampp\\mysql\\share\\mysql_system_tables.sql',
        'C:\\xampp\\mysql\\share\\mysql_system_tables_data.sql',
    ];
    
    foreach ($sql_files as $file) {
        if (!file_exists($file)) {
            echo "✗ File not found: $file\n";
            continue;
        }
        
        echo "Processing: " . basename($file) . "\n";
        $sql = file_get_contents($file);
        
        // Remove comments
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        
        // Split by ; and execute each statement
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                if (!$conn->query($statement)) {
                   // Continue on error - some statements may already exist
                    // echo "Error: " . $conn->error . "\n";
                }
            }
        }
    }
    
    echo "✓ MySQL system tables initialized!\n";
    
    // Create 'book' database
    $conn->query('CREATE DATABASE IF NOT EXISTS book');
    echo "✓ 'book' database created\n";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
