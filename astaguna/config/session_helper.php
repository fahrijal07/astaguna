<?php
// config/session_helper.php

// Start session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate unique session ID untuk cart
function getCartSessionId() {
    if (!isset($_SESSION['cart_session_id'])) {
        $_SESSION['cart_session_id'] = uniqid('cart_', true);
    }
    return $_SESSION['cart_session_id'];
}

// Get cart count
function getCartCount() {
    if (isset($_SESSION['cart_count'])) {
        return $_SESSION['cart_count'];
    }
    return 0;
}

// Update cart count
function updateCartCount($count) {
    $_SESSION['cart_count'] = $count;
}
?>