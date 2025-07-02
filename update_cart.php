<?php
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id']) || !isset($input['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$itemId = (int)$input['id'];
$quantity = (int)$input['quantity'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($quantity > 0) {
    $_SESSION['cart'][$itemId] = $quantity;
} else {
    unset($_SESSION['cart'][$itemId]);
}

echo json_encode(['success' => true]);
