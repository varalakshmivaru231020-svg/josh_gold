<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    echo "✓ MySQL Connection: SUCCESS\n";
    
    // Try to create database
    $pdo->exec('CREATE DATABASE IF NOT EXISTS book');
    echo "✓ Database 'book' created/exists\n";
    
} catch(Exception $e) {
    echo "✗ MySQL Connection FAILED:\n";
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
