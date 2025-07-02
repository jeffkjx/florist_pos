<?php
session_start();
require_once 'includes/functions.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit;
}

// Set headers to trigger download
$filename = "transactions_" . date("Y-m-d_H-i-s") . ".csv";
header('Content-Type: text/csv');
header("Content-Disposition: attachment; filename=\"$filename\"");

// Open PHP output stream
$output = fopen("php://output", "w");

// CSV Header row
fputcsv($output, ['Transaction ID', 'Date & Time', 'Total Amount', 'Item Name', 'Quantity', 'Unit Price']);

// Fetch transaction data
$stmt = $pdo->query("
    SELECT t.id AS transaction_id, t.datetime, t.total_amount,
           i.name AS item_name, ti.quantity, ti.unit_price
    FROM transactions t
    JOIN transaction_items ti ON t.id = ti.transaction_id
    JOIN items i ON ti.item_id = i.id
    ORDER BY t.id DESC
");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['transaction_id'],
        $row['datetime'],
        $row['total_amount'],
        $row['item_name'],
        $row['quantity'],
        $row['unit_price']
    ]);
}

fclose($output);
exit;
