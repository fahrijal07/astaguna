<?php
require_once __DIR__ . '/config/session_helper.php';
require_once __DIR__ . '/models/Order.php';

$session_id = getCartSessionId();
$orderModel = new Order();

$orders = $orderModel->getOrdersBySession($session_id);

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$lastCode = $_SESSION['last_order_code'] ?? null;

function rupiah($n) { return 'Rp ' . number_format((float)$n, 0, ',', '.'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Status Pesanan - Astaguna Jawa Catering</title>
<link href="https://fonts.googleapis.com/css2?family=Salsa&family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{
  font-family:'Roboto',sans-serif;
  background:#fff3e0; /* Background oren susu */
  color:#5c4b37;
}

/* ===== HEADER ===== */
.header {
  background: white;
  box-shadow: 0 2px 10px rgba(255, 159, 28, 0.1);
  padding: 15px 20px;
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  font-family: 'Salsa', cursive;
  font-size: 22px;
  font-weight: 700;
  color: #ff9f1c;
  display: flex;
  align-items: center;
  gap: 10px;
}

.logo-icon {
  width: 30px;
  height: 30px;
  filter: invert(62%) sepia(87%) saturate(423%) hue-rotate(358deg) brightness(101%) contrast(101%);
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 20px;
}

.cart-link {
  position: relative;
  text-decoration: none;
  color: #5c4b37;
}

.cart-count {
  background: #ff9f1c;
  color: white;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  font-size: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: -8px;
  right: -8px;
}

/* ===== MAIN CONTENT ===== */
.main-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 30px 20px;
}

.page-header {
  text-align: center;
  margin-bottom: 40px;
  padding: 0 20px;
}

.page-title {
  font-family: 'Salsa', cursive;
  font-size: 36px;
  color: #5c4b37;
  margin-bottom: 10px;
  position: relative;
  padding-bottom: 15px;
}

.page-title::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, #ff9f1c 0%, #ff8c00 100%);
  border-radius: 2px;
}

.page-subtitle {
  color: #b8a993;
  font-size: 16px;
  max-width: 600px;
  margin: 0 auto;
  line-height: 1.5;
}

/* ===== ORDERS SECTION ===== */
.orders-section {
  background: white;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 25px rgba(255, 159, 28, 0.1);
  border: 1px solid rgba(255, 159, 28, 0.15);
}

.section-title {
  font-family: 'Salsa', cursive;
  font-size: 24px;
  color: #5c4b37;
  margin-bottom: 25px;
  padding-bottom: 15px;
  border-bottom: 2px solid #fdfaf5;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 50px 20px;
}

.empty-icon {
  font-size: 64px;
  color: #f0e6d3;
  margin-bottom: 20px;
}

.empty-title {
  font-size: 20px;
  color: #5c4b37;
  margin-bottom: 10px;
  font-weight: 600;
}

.empty-subtitle {
  color: #b8a993;
  margin-bottom: 25px;
  font-size: 15px;
}

.btn-primary {
  background: linear-gradient(135deg, #ff9f1c 0%, #ff8c00 100%);
  color: white;
  border: none;
  padding: 12px 30px;
  border-radius: 10px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  display: inline-block;
  box-shadow: 0 4px 12px rgba(255, 159, 28, 0.2);
}

.btn-primary:hover {
  background: linear-gradient(135deg, #e68a00 0%, #d67c00 100%);
}

/* Orders Grid */
.orders-grid {
  display: grid;
  gap: 20px;
}

.order-card {
  background: white;
  border-radius: 15px;
  padding: 20px;
  border: 2px solid rgba(255, 159, 28, 0.1);
  text-decoration: none;
  color: inherit;
  display: block;
}

.order-card:hover {
  border-color: #ff9f1c;
  box-shadow: 0 5px 15px rgba(255, 159, 28, 0.1);
}

.order-card.highlight {
  border-color: #ff9f1c;
  background: #fffaf2;
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.order-code {
  font-size: 18px;
  font-weight: 700;
  color: #5c4b37;
}

.order-status {
  background: rgba(255, 159, 28, 0.1);
  color: #ff9f1c;
  padding: 6px 15px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
  border: 1px solid rgba(255, 159, 28, 0.2);
}

.order-details {
  margin-bottom: 15px;
}

.customer-name {
  font-weight: 600;
  color: #5c4b37;
  margin-bottom: 5px;
}

.order-meta {
  display: flex;
  gap: 20px;
  color: #b8a993;
  font-size: 14px;
  margin-bottom: 5px;
}

.delivery-address {
  color: #b8a993;
  font-size: 13px;
  line-height: 1.4;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.order-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 15px;
  border-top: 1px solid #fdfaf5;
}

.order-total {
  font-size: 18px;
  font-weight: 700;
  color: #ff9f1c;
}

.view-detail {
  color: #ff9f1c;
  font-size: 14px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 5px;
}

/* ===== FOOTER ===== */
.footer {
  background: white;
  border-top: 1px solid rgba(255, 159, 28, 0.15);
  padding: 30px 20px;
  margin-top: 50px;
}

.footer-container {
  max-width: 1200px;
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
  .page-title {
    font-size: 28px;
  }
  
  .orders-section {
    padding: 20px;
  }
  
  .order-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
  
  .order-footer {
    flex-direction: column;
    gap: 15px;
    align-items: flex-start;
  }
  
  .order-meta {
    flex-direction: column;
    gap: 5px;
  }
}

@media (max-width: 480px) {
  .header-container {
    flex-direction: column;
    gap: 15px;
    text-align: center;
  }
  
  .main-container {
    padding: 20px 15px;
  }
  
  .page-header {
    padding: 0;
  }
}
</style>
</head>
<body>

<!-- Header -->
<div class="header">
  <div class="header-container">
    <div class="logo">
      <img src="asset/plate.png" class="logo-icon">
      Astaguna Jawa Catering
    </div>
    
    <div class="header-actions">
      <a href="menu.php" class="btn-primary">Menu</a>
      <a href="cart.php" class="cart-link">
        <i class="fas fa-shopping-cart fa-lg"></i>
        <?php $cartCount = getCartCount(); if ($cartCount > 0): ?>
        <span class="cart-count"><?php echo $cartCount; ?></span>
        <?php endif; ?>
      </a>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="main-container">
  <div class="page-header">
    <h1 class="page-title">Status Pesanan</h1>
    <p class="page-subtitle">Pesanan yang kamu buat akan muncul di bawah ini. Klik untuk lihat detail.</p>
  </div>

  <div class="orders-section">
    <h2 class="section-title">Riwayat Pesanan</h2>
    
    <?php if (empty($orders)): ?>
      <div class="empty-state">
        <div class="empty-icon">
          <i class="fas fa-clipboard-list"></i>
        </div>
        <h3 class="empty-title">Belum ada pesanan</h3>
        <p class="empty-subtitle">Silakan pesan dari halaman Menu untuk memulai.</p>
        <a href="menu.php" class="btn-primary">
          <i class="fas fa-utensils"></i> Jelajahi Menu
        </a>
      </div>
    <?php else: ?>
      <div class="orders-grid">
        <?php foreach ($orders as $o): ?>
          <?php $isLast = ($lastCode && $o['order_code'] === $lastCode); ?>
          <a href="detail_pesanan.php?code=<?php echo urlencode($o['order_code']); ?>" 
             class="order-card <?php echo $isLast ? 'highlight' : ''; ?>">
            
            <div class="order-header">
              <div class="order-code"><?php echo htmlspecialchars($o['order_code']); ?></div>
              <div class="order-status"><?php echo htmlspecialchars($o['status'] ?? 'Diproses'); ?></div>
            </div>
            
            <div class="order-details">
              <div class="customer-name"><?php echo htmlspecialchars($o['customer_name']); ?></div>
              <div class="order-meta">
                <span><?php echo htmlspecialchars($o['customer_phone']); ?></span>
                <span>•</span>
                <span><?php echo date('d/m/Y', strtotime($o['created_at'])); ?></span>
              </div>
              <div class="delivery-address"><?php echo htmlspecialchars($o['delivery_address']); ?></div>
            </div>
            
            <div class="order-footer">
              <div class="order-total">Rp <?php echo number_format((float)$o['grand_total'], 0, ',', '.'); ?></div>
              <div class="view-detail">
                Lihat Detail <i class="fas fa-arrow-right"></i>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
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

</body>
</html>