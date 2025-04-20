<?php
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

// Get form data
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required']);
    exit;
}

try {
    // Check if user exists
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, password, phone, birth_date, address, art_style 
                           FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        exit;
    }
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        exit;
    }
    
    // Remove password from user data before sending
    unset($user['password']);
    
    // Return success with user data
    echo json_encode([
        'success' => true, 
        'message' => 'Login successful',
        'id' => $user['id'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'email' => $user['email'],
        'phone' => $user['phone'],
        'birth_date' => $user['birth_date'],
        'address' => $user['address'],
        'art_style' => $user['art_style']
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => "Login failed: " . $e->getMessage()]);
}

$conn = null;
?> 