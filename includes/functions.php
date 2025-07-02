<?php
// includes/functions.php
require_once 'db.php';


function get_all_items($include_inactive = false) {
    global $pdo;
    if ($include_inactive) {
        $stmt = $pdo->query("SELECT * FROM items ORDER BY id ASC");
    } else {
        $stmt = $pdo->query("SELECT * FROM items WHERE status = 'active' ORDER BY id ASC");
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function get_item_by_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function update_item_stock($id, $newStock) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE items SET stock = ? WHERE id = ?");
    $stmt->execute([$newStock, $id]);
}

function validate_admin_login($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($admin && hash('sha256', $password) === $admin['password_hash']) {
        return $admin;
    }
    return false;
}
?>
