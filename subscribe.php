<?php
header('Content-Type: application/json');

// Database configuration - REPLACE THESE WITH YOUR ACTUAL VALUES
$host = 'localhost';
$dbname = 'art_gallery1';
$username = 'root';
$password = '';

try {
    // Create connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get email from POST data
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    
    if (!$email) {
        throw new Exception("Please provide a valid email address.");
    }
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM newsletter_subscribers WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'This email is already subscribed.']);
    } else {
        // Insert new subscriber
        $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (:email)");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        echo json_encode(['status' => 'success', 'message' => 'Thank you for subscribing!']);
    }
    
} catch(PDOException $e) {
    // More detailed error reporting for debugging
    $errorInfo = [
        'status' => 'error',
        'message' => 'Database error',
        'details' => [
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ];
    echo json_encode($errorInfo);
    
} catch(Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>