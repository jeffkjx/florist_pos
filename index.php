<?php
session_start();
require_once 'includes/functions.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
 
$items = get_all_items();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Florist POS System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Florist POS Management System</h1>
        <button id="adminBtn">Admin Login</button>
    </header>

    <main class="container">
        <!-- Item Panel -->
        <section class="items-panel">
            <?php foreach ($items as $item): 
                $itemId = $item['id'];
                $cartQty = $_SESSION['cart'][$itemId] ?? 0;
                $stockLeft = $item['stock'] - $cartQty;
            ?>
            <div class="item-card" data-id="<?= $itemId ?>">
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <img src="<?= $item['image_path'] ?>" alt="<?= $item['name'] ?>">

                <p>Stock: <span class="stock"><?= $stockLeft ?></span></p>

                <div class="quantity-controls">
                    <button class="minus" id="minus-<?= $itemId ?>" <?= $cartQty <= 0 ? 'disabled' : '' ?>>−</button>
                    <span>Cart: <span class="cart"><?= $cartQty ?></span></span>
                    <button class="plus" id="plus-<?= $itemId ?>">＋</button>
                </div>

                <p class="price">Price: $<?= number_format($item['price'], 2) ?></p>
            </div>
            <?php endforeach; ?>
        </section>

        <!-- Cart Panel -->
        <aside class="cart-panel">
            <div class="cart-header">
                <h2 class="cart-title">Cart</h2>
                <button id="clearCartBtn">Clear Cart</button>
            </div>
            <ul id="cartList">
                <!-- JS will populate -->
            </ul>
            <hr style="margin: 10px 0;">
            <p><strong>Total:</strong> <span id="totalPrice" style="color: #5f37ef; font-weight: bold;">$0.00</span></p>
            <br>
            <button id="checkoutBtn" disabled>Checkout</button>
        </aside>
    </main>

    <!-- Admin Modal -->
    <div id="adminModal" class="modal">
        <div class="modal-content">
            <span class="closeBtn">&times;</span>
            <h2>Admin Login</h2>
            <form id="adminLoginForm" method="POST" action="login.php">
                <label>Username:</label>
                <input type="text" name="username" required>
                <label>Password:</label>
                <input type="password" name="password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
