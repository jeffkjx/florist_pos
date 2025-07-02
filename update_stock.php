<?php
session_start();
require_once 'includes/functions.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit;
}

function handle_image_upload($field_name, $prefix = '') {
    if (isset($_FILES[$field_name]) && $_FILES[$field_name]['error'] === 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $filename = basename($_FILES[$field_name]['name']);
        $targetPath = $uploadDir . $prefix . time() . '_' . $filename;
        if (move_uploaded_file($_FILES[$field_name]['tmp_name'], $targetPath)) {
            return $targetPath;
        }
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->beginTransaction();
    try {
        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $image = null;
        $price = $_POST['price'] ?? '';
        $stock = $_POST['stock'] ?? '';
        $change = $_POST['change'] ?? null;
        $action = $_POST['action'] ?? null;

        if ($action === 'add') {
            $newName = trim($_POST['new_name']);
            $newImage = handle_image_upload('new_image_file', 'new_');
            $newPrice = floatval($_POST['new_price']);
            $newStock = intval($_POST['new_stock']);

            if ($newName && $newImage && $newPrice >= 0 && $newStock >= 0) {
                $stmt = $pdo->prepare("INSERT INTO items (name, image_path, price, stock, status) VALUES (?, ?, ?, ?, 'active')");
                $stmt->execute([$newName, $newImage, $newPrice, $newStock]);
            }
        } elseif ($action === 'deactivate' && $id !== null) {
            $stmt = $pdo->prepare("UPDATE items SET status = 'inactive' WHERE id = ?");
            $stmt->execute([$id]);
        } elseif ($action === 'activate' && $id !== null) {
            $stmt = $pdo->prepare("UPDATE items SET status = 'active' WHERE id = ?");
            $stmt->execute([$id]);
        } elseif ($id !== null) {
            $item = get_item_by_id($id);
            if (!$item) {
                throw new Exception("Item not found.");
            }

            $image = handle_image_upload('image_file', 'edit_') ?? $item['image_path'];
            $stmt = $pdo->prepare("UPDATE items SET name = ?, image_path = ?, price = ?, stock = ? WHERE id = ?");
            $stmt->execute([$name, $image, $price, $stock, $id]);
        }

        $pdo->commit();
        header("Location: admin.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: admin.php");
    exit;
}