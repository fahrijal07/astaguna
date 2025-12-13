<?php
// update_cart.php
require_once __DIR__ . '/config/session_helper.php';
require_once __DIR__ . '/models/Cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = $_POST['cart_id'] ?? null;
    $change = $_POST['change'] ?? 0;
    
    if ($cart_id) {
        $cart = new Cart();
        $session_id = getCartSessionId();
        
        // Get current quantity
        $items = $cart->getCartItems($session_id);
        $currentQuantity = 1;
        
        foreach ($items as $item) {
            if ($item['id'] == $cart_id) {
                $currentQuantity = $item['quantity'];
                break;
            }
        }
        
        $newQuantity = $currentQuantity + intval($change);
        
        if ($newQuantity < 1) {
            $newQuantity = 1;
        }
        
        $success = $cart->updateCartItem($cart_id, $newQuantity, $session_id);
        
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Quantity updated' : 'Failed to update quantity'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Cart ID not provided'
        ]);
    }
}
?>