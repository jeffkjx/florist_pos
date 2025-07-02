<?php
session_start();
require_once 'includes/functions.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit;
}

$items = get_all_items(include_inactive: true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Florist POS - Admin Panel</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Florist POS Management System - Admin View</h1>
        <a href="index.php"><button>Return to Staff View</button></a>
    </header>

    <main class="container">
        <section class="items-panel">
            <?php foreach ($items as $item): ?>
            <div class="admin-card" data-id="<?= $item['id'] ?>">
                <form method="POST" action="update_stock.php" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">

                    <label>Item Name:</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>">

                    <label>Upload Image:</label>
                    <input type="file" name="image_file" accept="image/*">

                    <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="image-preview">

                    <label>Price ($):</label>
                    <input type="number" name="price" step="0.01" value="<?= $item['price'] ?>">

                    <label>Stock:</label>
                    <input type="number" name="stock" min="0" value="<?= $item['stock'] ?>">

                    <button type="submit" name="action" value="save" class="save-btn">Save Changes</button>

                    <?php if ($item['status'] === 'active'): ?>
                        <button type="submit" name="action" value="deactivate" class="deactivate-btn">Deactivate</button>
                    <?php else: ?>
                        <button type="submit" name="action" value="activate" class="activate-btn">Activate</button>
                    <?php endif; ?>
                </form>
            </div>
            <?php endforeach; ?>
        </section>

        <aside class="cart-panel">
            <h2>Admin Tools</h2>
            <form method="POST" action="export_csv.php">
                <button type="submit" class="export-btn">📁 Export Transactions to Excel</button>
            </form>

            <hr>
            <br>
            <h3>Add New Item</h3>
            <form method="POST" action="update_stock.php" enctype="multipart/form-data" onsubmit="return validateAddForm(this)">
                <label>Item Name:</label>
                <input type="text" name="new_name" required>

                <label>Upload Image:</label>
                <input type="file" name="new_image_file" accept="image/*" required>

                <label>Price ($):</label>
                <input type="number" name="new_price" step="0.01" required>

                <label>Stock:</label>
                <input type="number" name="new_stock" min="0" required>

                <button type="submit" name="action" value="add">Add New Item</button>
            </form>
        </aside>
    </main>

    <script>
    function validateAddForm(form) {
        return form.new_name.value && form.new_image_file.value && form.new_price.value && form.new_stock.value;
    }
    </script>
</body>
</html>