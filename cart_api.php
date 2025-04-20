<?php
session_start();
require_once 'cart_operations.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'add':
            $data = [
                'artist' => $_POST['artist'],
                'title' => $_POST['title'],
                'price' => $_POST['price'],
                'image' => $_POST['image']
            ];
            $result = addToCart($pdo, $data);
            echo json_encode($result);
            break;
            
        case 'get':
            $items = getCartItems($pdo);
            echo json_encode(['status' => 'success', 'items' => $items]);
            break;
            
        case 'remove':
            $result = removeFromCart($pdo, $_POST['item_id']);
            echo json_encode($result);
            break;
            
        case 'update':
            $result = updateCartItemQuantity($pdo, $_POST['item_id'], $_POST['quantity']);
            echo json_encode($result);
            break;
            
        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>