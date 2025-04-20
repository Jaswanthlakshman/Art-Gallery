<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "art_gallery1";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => "Connection failed: " . $e->getMessage()]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data
        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $birthDate = $_POST['birthDate'] ?? '';
        $address = $_POST['address'] ?? '';
        $artStyle = $_POST['artStyle'] ?? '';
        
        // Log received data
        error_log("Received registration data: " . print_r($_POST, true));
        
        // Validate input
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'First name, last name, email, and password are required']);
            exit;
        }
        
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            exit;
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, phone, birth_date, address, art_style) 
                               VALUES (:firstName, :lastName, :email, :password, :phone, :birthDate, :address, :artStyle)");
        
        $stmt->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':phone' => $phone,
            ':birthDate' => $birthDate ?: null,
            ':address' => $address,
            ':artStyle' => $artStyle
        ]);
        
        // Return success
        echo json_encode([
            'success' => true, 
            'message' => 'Registration successful! You can now login.'
        ]);
        
    } catch(PDOException $e) {
        error_log("Registration Error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => "Registration failed: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn = null;
?> 