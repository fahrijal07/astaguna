<?php
// cart.php
require_once __DIR__ . '/config/session_helper.php';
require_once __DIR__ . '/models/Cart.php';

$cart = new Cart();
$session_id = getCartSessionId();
$cartItems = $cart->getCartItems($session_id);
$cartTotal = $cart->getCartTotal($session_id);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Astaguna Jawa Catering</title>
    <link href="https://fonts.googleapis.com/css2?family=Salsa&family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <div class="logo">
            <img src="asset/plate.png" class="icon">
            Astaguna Jawa Catering
        </div>
        <a href="menu.php" class="back-link">
            <span>←</span> Kembali ke Menu
        </a>
    </div>
    
    <!-- MAIN CONTAINER -->
    <div class="cart-container">
        <h1 class="cart-title">Keranjang Belanja</h1>
        
        <?php if (empty($cartItems)): ?>
            <!-- EMPTY CART -->
            <div class="empty-cart">
                <div class="empty-icon">🛒</div>
                <h2>Keranjang Anda kosong</h2>
                <p>Tambahkan beberapa menu lezat ke keranjang Anda</p>
                <a href="menu.php" class="back-btn">
                    <span>🍽️</span> Jelajahi Menu
                </a>
            </div>
        <?php else: ?>
            <!-- CART ITEMS -->
            <div class="cart-items-container">
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item" id="cart-item-<?php echo $item['id']; ?>">
                        <div class="item-info">
                            <div class="item-name"><?php echo htmlspecialchars($item['item_name']); ?></div>
                            <div class="item-price">Rp <?php echo number_format($item['item_price'], 0, ',', '.'); ?> per item</div>
                        </div>
                        <div class="quantity-control">
                            <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, -1)">-</button>
                            <span class="quantity-display"><?php echo $item['quantity']; ?></span>
                            <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, 1)">+</button>
                        </div>
                        <div class="item-total">
                            Rp <?php echo number_format($item['item_price'] * $item['quantity'], 0, ',', '.'); ?>
                        </div>
                        <button class="remove-btn" onclick="removeFromCart(<?php echo $item['id']; ?>)">
                            Hapus
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- CART SUMMARY -->
            <div class="cart-summary">
                <div class="summary-row">
                    <div class="summary-label">Jumlah Item</div>
                    <div class="summary-value"><?php echo count($cartItems); ?> item</div>
                </div>
                <div class="summary-row total-row">
                    <div class="total-label">Total Belanja</div>
                    <div class="total-value">Rp <?php echo number_format($cartTotal, 0, ',', '.'); ?></div>
                </div>
            </div>
            
            <!-- CHECKOUT BUTTON -->
            <button class="checkout-btn" onclick="checkout()">
                Lanjut ke Pembayaran
            </button>
        <?php endif; ?>
    </div>
    
    <script src="js/cart.js"></script>
</body>
</html>