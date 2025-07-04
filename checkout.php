<?php
session_start();
date_default_timezone_set('Asia/Kuala_Lumpur');
require_once 'includes/functions.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['cart']) || !is_array($input['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid cart data']);
    exit;
}

$cart = $input['cart'];
$total = 0;
$pdo->beginTransaction();

try {
    $itemsSummary = [];
    foreach ($cart as $itemId => $qty) {
        $item = get_item_by_id($itemId);
        if (!$item || $qty < 1) continue;

        $available = $item['stock'];
        if ($available < $qty) {
            throw new Exception("Not enough stock for item: " . $item['name']);
        }

        $total += $item['price'] * $qty;
        $itemsSummary[] = [
            'name' => $item['name'],
            'qty' => $qty,
            'unit_price' => $item['price']
        ];
    }

    if (empty($itemsSummary)) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty']);
        exit;
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
    $_SESSION['cart'] = [];

    // 🧾 Generate centered receipt with item prices
    $lineWidth = 34;

    function centerLine($text, $width = 30) {
        return str_pad($text, $width + floor((strlen($text) - strlen(trim($text))) / 2), " ", STR_PAD_BOTH);
    }

    $receiptLines = [];
    $receiptLines[] = str_repeat("-", $lineWidth);
    $receiptLines[] = centerLine("Bloom Florist", $lineWidth);
    $receiptLines[] = "";
    $receiptLines[] = centerLine(date("d.m.Y H:i"), $lineWidth);
    $receiptLines[] = str_repeat("-", $lineWidth);
    $receiptLines[] = centerLine("RECEIPT", $lineWidth);
    $receiptLines[] = str_repeat("-", $lineWidth);
    $receiptLines[] = "";

    foreach ($itemsSummary as $item) {
        $line = "{$item['name']}(" . number_format($item['unit_price'], 2) . ") x {$item['qty']} = " . number_format($item['qty'] * $item['unit_price'], 2);
        $receiptLines[] = centerLine($line, $lineWidth);
    }

    $receiptLines[] = "";
    $receiptLines[] = centerLine("Total: " . number_format($total, 2), $lineWidth);
    $receiptLines[] = "";
    $receiptLines[] = str_repeat("-", $lineWidth);
    $receiptLines[] = centerLine("Thank you", $lineWidth);
    $receiptLines[] = centerLine("Please come again", $lineWidth);
    $receiptLines[] = str_repeat("-", $lineWidth);

    $receiptText = implode("\n", $receiptLines);


    // Save receipt to file
    $receiptFile = "receipts/receipt_{$transactionId}.txt";
    if (!is_dir("receipts")) {
        mkdir("receipts", 0755, true);
    }
    file_put_contents($receiptFile, $receiptText);

    echo json_encode([
        'success' => true,
        'receipt_url' => $receiptFile,
        'filename' => basename($receiptFile)
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
