<?php

/**
 * Database Configuration
 *
 * This file defines the database connection constants and establishes a connection
 * to the database using PDO (PHP Data Objects).
 */

// Database credentials
define('DB_HOST', 'localhost'); // Your database host (e.g., 'localhost' or '127.0.0.1')
define('DB_NAME', 'php_crud_db');    // Your database name
define('DB_USER', 'root');      // Your database username
define('DB_PASS', '');      // Your database password
define('DB_CHAR', 'utf8mb4');   // The character set

// Data Source Name (DSN)
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch results as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}