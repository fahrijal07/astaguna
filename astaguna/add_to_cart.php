<?php
// add_to_cart.php
require_once __DIR__ . '/config/session_helper.php';
require_once __DIR__ . '/models/Cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'] ?? null;
    $item_type = $_POST['item_type'] ?? null;
    
    if ($item_id && $item_type) {
        $cart = new Cart();
        $session_id = getCartSessionId();
        $success = $cart->addToCart($session_id, $item_type, $item_id);
        
        if ($success) {
            $cartCount = $cart->getCartCount($session_id);
            echo json_encode([
                'success' => true,
                'cart_count' => $cartCount,
                'message' => 'Item berhasil ditambahkan ke keranjang'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menambahkan item ke keranjang'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak lengkap'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Metode request tidak valid'
    ]);
}
?>