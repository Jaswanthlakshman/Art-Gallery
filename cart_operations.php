<?php
$host = 'localhost';
$dbname = 'art_gallery1';
$username = 'root'; 
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function addToCart($pdo, $data) {
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $sessionId = session_id();
    
    $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE 
                          (user_id = :user_id OR session_id = :session_id) AND 
                          title = :title");
    $stmt->execute([
        ':user_id' => $userId,
        ':session_id' => $sessionId,
        ':title' => $data['title']
    );
    $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingItem) {
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1 
                              WHERE id = :id");
        $stmt->execute([':id' => $existingItem['id']]);
        return ['status' => 'success', 'message' => 'Item quantity updated'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart_items 
                              (user_id, session_id, artist, title, price, image, quantity) 
                              VALUES (:user_id, :session_id, :artist, :title, :price, :image, :quantity)");
        $stmt->execute([
            ':user_id' => $userId,
            ':session_id' => $sessionId,
            ':artist' => $data['artist'],
            ':title' => $data['title'],
            ':price' => $data['price'],
            ':image' => $data['image'],
            ':quantity' => $data['quantity'] ?? 1
        ]);
        return ['status' => 'success', 'message' => 'Item added to cart'];
    }
}

function getCartItems($pdo) {
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $sessionId = session_id();
    
    $stmt = $pdo->prepare("SELECT * FROM cart_items 
                          WHERE user_id = :user_id OR session_id = :session_id");
    $stmt->execute([
        ':user_id' => $userId,
        ':session_id' => $sessionId
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function removeFromCart($pdo, $itemId) {
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = :id");
    $stmt->execute([':id' => $itemId]);
    return ['status' => 'success', 'message' => 'Item removed from cart'];
}

// Function to update cart item quantity
function updateCartItemQuantity($pdo, $itemId, $quantity) {
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = :quantity WHERE id = :id");
    $stmt->execute([
        ':quantity' => $quantity,
        ':id' => $itemId
    ]);
    return ['status' => 'success', 'message' => 'Item quantity updated'];
}
?>