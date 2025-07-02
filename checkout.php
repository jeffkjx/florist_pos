<?php
session_start();
require_once 'includes/functions.php';
header('Content-Type: application/json');

// Read raw JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['cart']) || !is_array($input['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid cart data']);
    exit;
}

$cart = $input['cart'];
$total = 0;
$pdo->beginTransaction();

try {
    $hasValidItem = false;

    // Calculate total and verify stock
    foreach ($cart as $itemId => $qty) {
        $item = get_item_by_id($itemId);
        if (!$item || $qty < 1) continue;

        $available = $item['stock'];
        if ($available < $qty) {
            throw new Exception("Not enough stock for item: " . $item['name']);
        }

        $total += $item['price'] * $qty;
        $hasValidItem = true;
    }

    if (!$hasValidItem || $total <= 0) {
        throw new Exception("Cart is empty or total is zero. Cannot checkout.");
    }

    // Insert transaction
    $stmt = $pdo->prepare("INSERT INTO transactions (datetime, total_amount) VALUES (NOW(), ?)");
    $stmt->execute([$total]);
    $transactionId = $pdo->lastInsertId();

    // Insert transaction items and update stock
    $stmtItem = $pdo->prepare("INSERT INTO transaction_items (transaction_id, item_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
    $stmtStock = $pdo->prepare("UPDATE items SET stock = stock - ? WHERE id = ?");

    foreach ($cart as $itemId => $qty) {
        $item = get_item_by_id($itemId);
        if (!$item || $qty < 1) continue;

        $stmtItem->execute([$transactionId, $itemId, $qty, $item['price']]);
        $stmtStock->execute([$qty, $itemId]);
    }

    $pdo->commit();
    $_SESSION['cart'] = []; // Clear cart

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
