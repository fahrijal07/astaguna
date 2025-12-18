<?php
// remove_from_cart.php
require_once __DIR__ . '/config/session_helper.php';
require_once __DIR__ . '/models/Cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = $_POST['cart_id'] ?? null;
    
    if ($cart_id) {
        $cart = new Cart();
        $session_id = getCartSessionId();
        $success = $cart->removeFromCart($cart_id, $session_id);
        
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Item removed from cart' : 'Failed to remove item'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Cart ID not provided'
        ]);
    }
}
?>