<?php
require_once __DIR__ . '/config/session_helper.php';
require_once __DIR__ . '/models/Order.php';

$session_id = getCartSessionId();
$orderModel = new Order();

$code = trim($_GET['code'] ?? '');
if ($code === '') {
    header('Location: status.php');
    exit();
}

$order = $orderModel->getOrderByCode($code, $session_id);
if (!$order) {
    die("Pesanan tidak ditemukan atau bukan milik sesi ini.");
}

$items = $orderModel->getOrderItems((int)$order['id']);

// Status dengan 4 tahap seperti di status.php
$statuses = [
    1 => [
        'id' => 'pending',
        'title' => 'Pending',
        'description' => 'Menunggu Konfirmasi',
        'icon' => '⏳'
    ],
    2 => [
        'id' => 'processing',
        'title' => 'Diproses',
        'description' => 'Pesanan sedang dibuat',
        'icon' => '👨‍🍳'
    ],
    3 => [
        'id' => 'delivering',
        'title' => 'Dikirim',
        'description' => 'Pesanan diantar',
        'icon' => '🚚'
    ],
    4 => [
        'id' => 'delivered',
        'title' => 'Selesai',
        'description' => 'Pesanan selesai',
        'icon' => '✅'
    ]
];

// Determine current status based on order time
$orderTime = strtotime($order['created_at']);
$currentTime = time();
$timeDiff = ($currentTime - $orderTime) / 3600; // hours

// Set status berdasarkan waktu (simulasi progress)
if ($timeDiff < 0.5) {
    $currentStatusId = 1; // Pending
} elseif ($timeDiff < 1.5) {
    $currentStatusId = 2; // Diproses
} elseif ($timeDiff < 3) {
    $currentStatusId = 3; // Dikirim
} else {
    $currentStatusId = 4; // Selesai
}

// Get current status
$currentStatus = $statuses[$currentStatusId];

function rupiah($n) { return 'Rp ' . number_format((float)$n, 0, ',', '.'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Pesanan - <?php echo htmlspecialchars($order['order_code']); ?> - Astaguna Jawa Catering</title>
  <link href="https://fonts.googleapis.com/css2?family=Salsa&family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{
    font-family:'Roboto',sans-serif;
    background:#f5f5f5;
    min-height: 100vh;
  }

  /* ===== PAGE TITLE ===== */
  .page-title {
    background: #ff9f1c;
    color: white;
    padding: 30px 20px;
    text-align: center;
    border-bottom: 5px solid #e68a00;
  }

  .page-title h1 {
    font-family: 'Salsa', cursive;
    font-size: 36px;
    margin-bottom: 5px;
  }

  .page-title p {
    font-size: 18px;
    opacity: 0.95;
  }

  /* ===== STATUS CONTAINER ===== */
  .status-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 0 20px;
  }

  /* Back Button */
  .back-section {
    margin-bottom: 30px;
  }

  .back-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 25px;
    background: white;
    color: #ff9f1c;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    border: 2px solid #ff9f1c;
    transition: all 0.3s;
  }

  .back-btn:hover {
    background: #fffaf2;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 159, 28, 0.1);
  }

  /* ===== ORDER INFO CARD ===== */
  .order-info-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }

  .order-info-title {
    font-family: 'Salsa', cursive;
    font-size: 24px;
    color: #ff9f1c;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #eee;
  }

  .order-detail {
    margin-bottom: 20px;
  }

  .detail-row {
    display: flex;
    margin-bottom: 10px;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
  }

  .detail-label {
    width: 150px;
    font-weight: 500;
    color: #666;
  }

  .detail-value {
    flex: 1;
    color: #333;
  }

  .customer-name {
    font-size: 20px;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
  }

  .order-address {
    font-size: 16px;
    color: #666;
    line-height: 1.5;
    background: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    margin: 15px 0;
  }

  .order-id-section {
    background: #fff9f0;
    padding: 20px;
    border-radius: 10px;
    margin-top: 20px;
    border-left: 4px solid #ff9f1c;
    text-align: center;
    cursor: pointer;
    transition: transform 0.2s;
  }

  .order-id-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 159, 28, 0.2);
  }

  .order-id-label {
    font-size: 14px;
    color: #888;
    letter-spacing: 1px;
    margin-bottom: 5px;
  }

  .order-id-value {
    font-size: 24px;
    font-weight: bold;
    color: #ff9f1c;
    font-family: monospace;
  }

  /* ===== ORDER ITEMS ===== */
  .order-items-section {
    background: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }

  .order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
  }

  .item-info {
    flex: 1;
  }

  .item-name {
    font-weight: bold;
    font-size: 16px;
    margin-bottom: 5px;
  }

  .item-type {
    background: rgba(255, 159, 28, 0.1);
    color: #ff9f1c;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 12px;
    display: inline-block;
  }

  .item-price {
    color: #666;
    font-size: 14px;
  }

  .item-quantity {
    margin: 0 20px;
    font-weight: bold;
    color: #333;
    min-width: 40px;
    text-align: center;
  }

  .item-total {
    font-weight: bold;
    color: #ff9f1c;
    min-width: 100px;
    text-align: right;
  }

  .order-total {
    text-align: right;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #eee;
    font-size: 20px;
    font-weight: bold;
    color: #ff9f1c;
  }

  .total-breakdown {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-top: 20px;
  }

  .breakdown-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
  }

  .breakdown-row:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
  }

  .breakdown-label {
    color: #666;
  }

  .breakdown-value {
    font-weight: 500;
  }

  /* ===== STATUS TRACKER ===== */
  .status-tracker {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }

  .tracker-title {
    font-family: 'Salsa', cursive;
    font-size: 24px;
    color: #ff9f1c;
    margin-bottom: 30px;
    text-align: center;
  }

  /* ===== CIRCLE PROGRESS ===== */
  .circle-progress {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    max-width: 700px;
    margin: 0 auto;
    padding: 0 20px;
  }

  .circle-progress::before {
    content: '';
    position: absolute;
    top: 40px;
    left: 50px;
    right: 50px;
    height: 4px;
    background: #ddd;
    z-index: 1;
  }

  .circle-progress.completed-2::before {
    background: linear-gradient(to right, #4CAF50 25%, #ddd 25%);
  }

  .circle-progress.completed-3::before {
    background: linear-gradient(to right, #4CAF50 50%, #ddd 50%);
  }

  .circle-progress.completed-4::before {
    background: #4CAF50;
  }

  .circle-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    width: 100px;
  }

  .circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: white;
    border: 4px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    font-weight: bold;
    color: #999;
    margin-bottom: 15px;
    transition: all 0.3s;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  }

  .circle.active {
    border-color: #ff9f1c;
    background: #ff9f1c;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 6px 12px rgba(255, 159, 28, 0.3);
  }

  .circle.completed {
    border-color: #4CAF50;
    background: #4CAF50;
    color: white;
  }

  .circle-label {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
    text-align: center;
  }

  .circle-description {
    font-size: 14px;
    color: #666;
    text-align: center;
    max-width: 120px;
  }

  /* ===== CURRENT STATUS ===== */
  .current-status {
    text-align: center;
    margin-top: 40px;
    padding: 25px;
    background: #f8f9fa;
    border-radius: 12px;
    border-left: 5px solid #ff9f1c;
  }

  .status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
  }

  .status-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #ff9f1c;
    animation: pulse 1.5s infinite;
  }

  @keyframes pulse {
    0% { opacity: 0.5; }
    50% { opacity: 1; }
    100% { opacity: 0.5; }
  }

  .status-text {
    font-size: 20px;
    font-weight: bold;
    color: #333;
  }

  .status-time {
    font-size: 14px;
    color: #666;
    margin-top: 5px;
  }

  /* ===== NOTES SECTION ===== */
  .notes-section {
    background: white;
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }

  .notes-label {
    font-size: 14px;
    color: #888;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .notes-content {
    color: #333;
    font-size: 16px;
    line-height: 1.6;
    padding: 15px;
    background: #f9f9f9;
    border-radius: 8px;
    border-left: 3px solid #ff9f1c;
  }

  /* ===== ACTION BUTTONS ===== */
  .action-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 40px;
    margin-bottom: 50px;
    flex-wrap: wrap;
  }

  .action-btn {
    padding: 14px 30px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
    border: none;
    cursor: pointer;
    font-family: 'Roboto', sans-serif;
  }

  .btn-primary {
    background: #ff9f1c;
    color: white;
  }

  .btn-primary:hover {
    background: #e68a00;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(255, 159, 28, 0.3);
  }

  .btn-secondary {
    background: #f0f0f0;
    color: #333;
    border: 2px solid #ddd;
  }

  .btn-secondary:hover {
    background: #e0e0e0;
    transform: translateY(-2px);
  }

  .btn-icon {
    width: 20px;
    height: 20px;
  }

  /* ===== FOOTER ===== */
  .footer {
    background: white;
    border-top: 1px solid rgba(255, 159, 28, 0.15);
    padding: 30px 20px;
    margin-top: 50px;
  }

  .footer-container {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
  }

  .footer-logo {
    font-family: 'Salsa', cursive;
    font-size: 24px;
    color: #ff9f1c;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }

  .footer-copyright {
    color: #b8a993;
    font-size: 14px;
    margin-top: 15px;
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 768px) {
    .status-container {
      padding: 0 15px;
    }
    
    .order-info-card,
    .status-tracker,
    .order-items-section,
    .notes-section {
      padding: 20px;
    }
    
    .circle-progress {
      flex-direction: column;
      gap: 30px;
      padding: 0;
    }
    
    .circle-progress::before {
      display: none;
    }
    
    .circle-item {
      width: 100%;
      flex-direction: row;
      justify-content: flex-start;
      gap: 20px;
    }
    
    .circle {
      width: 60px;
      height: 60px;
      font-size: 24px;
      margin-bottom: 0;
    }
    
    .circle-info {
      text-align: left;
    }
    
    .circle-label,
    .circle-description {
      text-align: left;
      max-width: none;
    }
    
    .action-buttons {
      flex-direction: column;
      align-items: center;
    }
    
    .action-btn {
      width: 100%;
      max-width: 300px;
      justify-content: center;
    }
    
    .detail-row {
      flex-direction: column;
      gap: 5px;
    }
    
    .detail-label {
      width: 100%;
    }
    
    .order-item {
      flex-direction: column;
      align-items: flex-start;
      gap: 10px;
    }
    
    .item-quantity,
    .item-total {
      margin: 0;
      text-align: left;
    }
  }

  @media (max-width: 480px) {
    .page-title h1 {
      font-size: 28px;
    }
    
    .page-title p {
      font-size: 16px;
    }
    
    .order-info-title {
      font-size: 20px;
    }
    
    .customer-name {
      font-size: 18px;
    }
    
    .order-address {
      font-size: 14px;
    }
    
    .order-id-value {
      font-size: 20px;
    }
  }
  </style>
</head>

<body>

<section class="page-title">
  <h1>Detail Pesanan Anda</h1>
  <p>Kode: <?php echo htmlspecialchars($order['order_code']); ?></p>
</section>

<div class="status-container">
  
  <!-- Back Button -->
  <div class="back-section">
    <a href="status.php" class="back-btn">
      <i class="fas fa-arrow-left"></i> Kembali ke Semua Pesanan
    </a>
  </div>
  
  <!-- Order Information Card -->
  <div class="order-info-card">
    <h2 class="order-info-title">Detail Pelanggan</h2>
    
    <div class="order-detail">
      <div class="customer-name"><?php echo htmlspecialchars($order['customer_name']); ?></div>
      
      <div class="detail-row">
        <div class="detail-label">No. Telepon:</div>
        <div class="detail-value"><?php echo htmlspecialchars($order['customer_phone']); ?></div>
      </div>
      
      <div class="detail-row">
        <div class="detail-label">Email:</div>
        <div class="detail-value"><?php echo htmlspecialchars($order['customer_email'] ?: '-'); ?></div>
      </div>
      
      <div class="detail-row">
        <div class="detail-label">Metode Pembayaran:</div>
        <div class="detail-value"><?php echo htmlspecialchars($order['payment_method'] ?: '-'); ?></div>
      </div>
      
      <div class="detail-row">
        <div class="detail-label">Tanggal Pesan:</div>
        <div class="detail-value"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></div>
      </div>
      
      <div class="order-address">
        <strong>Alamat Pengiriman:</strong><br>
        <?php echo htmlspecialchars($order['delivery_address']); ?>
      </div>
    </div>
    
    <div class="order-id-section" onclick="copyOrderCode()">
      <div class="order-id-label">KODE PESANAN</div>
      <div class="order-id-value"><?php echo $order['order_code']; ?></div>
      <small style="color: #888; margin-top: 5px; display: block;">Klik untuk menyalin</small>
    </div>
  </div>
  
  <!-- Order Items -->
  <div class="order-items-section">
    <h2 class="order-info-title">Detail Pesanan</h2>
    
    <div class="order-items">
      <?php 
      $subtotal = 0;
      foreach ($items as $item): 
        $itemSubtotal = $item['item_price'] * $item['qty'];
        $subtotal += $itemSubtotal;
      ?>
      <div class="order-item">
        <div class="item-info">
          <div class="item-name"><?php echo htmlspecialchars($item['item_name']); ?></div>
          <div class="item-type"><?php echo htmlspecialchars($item['item_type']); ?></div>
          <div class="item-price"><?php echo rupiah($item['item_price']); ?> per item</div>
        </div>
        <div class="item-quantity">× <?php echo (int)$item['qty']; ?></div>
        <div class="item-total"><?php echo rupiah($itemSubtotal); ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    
    <div class="total-breakdown">
      <div class="breakdown-row">
        <div class="breakdown-label">Subtotal</div>
        <div class="breakdown-value"><?php echo rupiah($order['cart_total']); ?></div>
      </div>
      <div class="breakdown-row">
        <div class="breakdown-label">Ongkir</div>
        <div class="breakdown-value">Rp 10.000</div>
      </div>
      <div class="breakdown-row">
        <div class="breakdown-label">Biaya Layanan</div>
        <div class="breakdown-value">Rp 2.000</div>
      </div>
    </div>
    
    <div class="order-total">
      <div style="font-size: 24px; color: #ff9f1c;">
        Total: <?php echo rupiah($order['grand_total']); ?>
      </div>
    </div>
  </div>
  
  <!-- Notes Section -->
  <?php if (!empty($order['notes'])): ?>
  <div class="notes-section">
    <div class="notes-label">
      <i class="fas fa-sticky-note"></i> Catatan Pesanan
    </div>
    <div class="notes-content">
      <?php echo htmlspecialchars($order['notes']); ?>
    </div>
  </div>
  <?php endif; ?>
  
  <!-- Status Tracker -->
  <div class="status-tracker">
    <h2 class="tracker-title">Status Pengiriman</h2>
    
    <div class="circle-progress completed-<?php echo $currentStatusId; ?>">
      <?php foreach ($statuses as $number => $status): ?>
        <div class="circle-item">
          <div class="circle 
            <?php echo $number == $currentStatusId ? 'active' : ''; ?>
            <?php echo $number < $currentStatusId ? 'completed' : ''; ?>">
            <?php echo $status['icon']; ?>
          </div>
          <div class="circle-info">
            <div class="circle-label"><?php echo $status['title']; ?></div>
            <div class="circle-description"><?php echo $status['description']; ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    
    <!-- Current Status Display -->
    <div class="current-status">
      <div class="status-indicator">
        <div class="status-dot"></div>
        <div class="status-text">Status Saat Ini: <?php echo $currentStatus['title']; ?></div>
      </div>
      <div class="status-time">
        Diperbarui: <?php echo date('d/m/Y H:i', $currentTime); ?> WIB
        <br>
        <small>Estimasi selesai: <?php 
          $estimatedTime = strtotime($order['created_at']) + (3 * 3600); // +3 jam
          echo date('H:i', $estimatedTime); 
        ?> WIB</small>
      </div>
    </div>
  </div>
  
  <!-- Action Buttons -->
  <div class="action-buttons">
    <button class="action-btn btn-secondary" onclick="location.href='status.php'">
      <svg class="btn-icon" fill="currentColor" viewBox="0 0 24 24">
        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
      </svg>
      Semua Pesanan
    </button>
    
    <button class="action-btn btn-primary" onclick="location.href='menu.php'">
      <svg class="btn-icon" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
      </svg>
      Pesan Lagi
    </button>
    
    <button class="action-btn btn-secondary" onclick="contactWhatsApp()">
      <svg class="btn-icon" fill="currentColor" viewBox="0 0 24 24">
        <path d="M16.75 13.96c.25.13.41.2.46.3.06.11.04.61-.21 1.18-.2.56-1.24 1.1-1.7 1.12-.46.02-.47.36-2.96-.73-2.49-1.09-3.99-3.75-4.11-3.92-.12-.17-.96-1.38-.92-2.61.05-1.22.69-1.8.95-2.04.24-.26.51-.29.68-.26h.47c.15 0 .36-.06.55.45l.69 1.87c.06.13.1.28.01.44l-.27.41-.39.42c-.12.12-.26.25-.12.5.12.26.62 1.09 1.32 1.78.91.88 1.71 1.17 1.95 1.3.24.14.39.12.54-.04l.81-.94c.19-.25.35-.19.58-.11l1.67.88M12 2a10 10 0 0 1 10 10 10 10 0 0 1-10 10c-1.97 0-3.8-.57-5.35-1.55L2 22l1.55-4.65A9.969 9.969 0 0 1 2 12 10 10 0 0 1 12 2m0 2a8 8 0 0 0-8 8c0 1.72.54 3.31 1.46 4.61L4.5 19.5l2.89-.96A7.95 7.95 0 0 0 12 20a8 8 0 0 0 8-8 8 8 0 0 0-8-8z"/>
      </svg>
      Hubungi WhatsApp
    </button>
  </div>
  
</div>

<!-- Footer -->
<div class="footer">
  <div class="footer-container">
    <div class="footer-logo">
      <img src="asset/plate.png" style="width:24px;height:24px;">
      Astaguna Jawa Catering
    </div>
    <div class="footer-copyright">
      © <?php echo date('Y'); ?> Astaguna Jawa Catering. Hak Cipta Dilindungi.
    </div>
  </div>
</div>

<script>
function contactWhatsApp() {
  const orderCode = '<?php echo $order['order_code']; ?>';
  const customerName = '<?php echo addslashes($order['customer_name']); ?>';
  const message = `Halo Astaguna Catering,\n\nSaya ${customerName} ingin menanyakan pesanan dengan kode:\n${orderCode}\n\nStatus pesanan saat ini bagaimana?`;
  const url = `https://wa.me/628815377808?text=${encodeURIComponent(message)}`;
  window.open(url, '_blank');
}

// Copy order code to clipboard
function copyOrderCode() {
  const orderCode = '<?php echo $order['order_code']; ?>';
  navigator.clipboard.writeText(orderCode).then(function() {
    alert('✅ Kode pesanan telah disalin:\n' + orderCode);
  }).catch(function() {
    // Fallback untuk browser lama
    const tempInput = document.createElement('input');
    tempInput.value = orderCode;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    alert('✅ Kode pesanan telah disalin:\n' + orderCode);
  });
}

// Add animation to current status circle
const currentCircle = document.querySelector('.circle.active');
if (currentCircle) {
  setInterval(function() {
    currentCircle.style.transform = currentCircle.style.transform === 'scale(1.1)' 
      ? 'scale(1.05)' 
      : 'scale(1.1)';
  }, 1000);
}

// Auto-refresh status every 60 seconds
setTimeout(function() {
  location.reload();
}, 60000);
</script>

</body>
</html>