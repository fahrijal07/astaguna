<?php
require_once __DIR__ . '/config/session_helper.php';
require_once __DIR__ . '/models/Cart.php';
require_once __DIR__ . '/models/Order.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: menu.php');
    exit();
}

$cart = new Cart();
$orderModel = new Order();
$session_id = getCartSessionId();

$cartItems = $cart->getCartItems($session_id);
if (empty($cartItems)) {
    header('Location: menu.php');
    exit();
}

function clean($s) { return trim((string)$s); }

$customer_name    = clean($_POST['customer_name'] ?? '');
$customer_phone   = clean($_POST['customer_phone'] ?? '');
$customer_email   = clean($_POST['customer_email'] ?? '');
$delivery_address = clean($_POST['delivery_address'] ?? '');
$delivery_date    = clean($_POST['delivery_date'] ?? '');
$delivery_time    = clean($_POST['delivery_time'] ?? '');
$notes            = clean($_POST['notes'] ?? '');
$payment_method   = clean($_POST['payment_method'] ?? 'cod');
$bank             = clean($_POST['bank'] ?? '');

if ($customer_name === '' || $customer_phone === '' || $delivery_address === '') {
    die("Data pemesan belum lengkap.");
}

$cartTotal = (float)$cart->getCartTotal($session_id);
$serviceFee = 0; // kalau ada biaya layanan, ubah di sini
$grandTotal = $cartTotal + $serviceFee;

// kode pesanan
$orderCode = 'AC-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

try {
    // simpan header order
    $created = $orderModel->createOrder([
        'session_id'       => $session_id,
        'order_code'       => $orderCode,
        'customer_name'    => $customer_name,
        'customer_phone'   => $customer_phone,
        'customer_email'   => $customer_email ?: null,
        'delivery_address' => $delivery_address,
        'delivery_date'    => $delivery_date ?: null,
        'delivery_time'    => $delivery_time ?: null,
        'notes'            => $notes ?: null,
        'payment_method'   => $payment_method ?: 'cod',
        'bank'             => $bank ?: null,
        'cart_total'       => $cartTotal,
        'service_fee'      => $serviceFee,
        'grand_total'      => $grandTotal,
        'status'           => 'Menunggu Konfirmasi'
    ]);

    $orderId = (int)$created['id'];

    // simpan detail item
    foreach ($cartItems as $it) {
        $orderModel->addOrderItem($orderId, $it);
    }

    // simpan order terakhir ke session (biar status langsung tampil)
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['last_order_code'] = $orderCode;

    // kosongkan cart
    $cart->clearCart($session_id);

    header('Location: status.php');
    exit();

} catch (Throwable $e) {
    die("Gagal membuat pesanan: " . $e->getMessage());
}
