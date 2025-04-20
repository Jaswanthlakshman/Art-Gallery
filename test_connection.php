<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_connect.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connection successful!";
    
    // Test if the users table exists
    $result = $conn->query("SHOW TABLES LIKE 'users'");
    if ($result->num_rows > 0) {
        echo "<br>Users table exists!";
        
        // Show table structure
        $result = $conn->query("DESCRIBE users");
        echo "<br><br>Table structure:<br>";
        while ($row = $result->fetch_assoc()) {
            echo $row['Field'] . " - " . $row['Type'] . "<br>";
        }
    } else {
        echo "<br>Users table does not exist!";
    }
}

$conn->close();
?> 