<?php
// pesan.php - checkout + ringkasan keranjang
require_once __DIR__ . '/config/session_helper.php';
require_once __DIR__ . '/models/Cart.php';
require_once __DIR__ . '/models/Menu.php';

$cart = new Cart();
$menu = new Menu();
$session_id = getCartSessionId();

// Tambah item dari bulk (pilih banyak dulu)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_add']) && is_array($_POST['items'])) {
    foreach ($_POST['items'] as $it) {
        $item_id = isset($it['id']) ? (int)$it['id'] : 0;
        $qty     = isset($it['qty']) ? (int)$it['qty'] : 0;
        $type    = isset($it['type']) ? $it['type'] : '';

        if ($item_id > 0 && $qty > 0 && in_array($type, ['item', 'paket'], true)) {
            $cart->addToCart($session_id, $type, $item_id, $qty);
        }
    }
    header('Location: pesan.php');
    exit();
}

// Tambah item dari tombol Pesan Sekarang (?add=1...)
if (isset($_GET['add'], $_GET['item_id'], $_GET['item_type'])) {
    $item_id   = (int)$_GET['item_id'];
    $item_type = $_GET['item_type'];
    $qty       = isset($_GET['qty']) ? max(1, (int)$_GET['qty']) : 1;

    if ($item_id > 0 && in_array($item_type, ['item', 'paket'], true)) {
        $cart->addToCart($session_id, $item_type, $item_id, $qty);
    }
    header('Location: pesan.php');
    exit();
}

$cartItems = $cart->getCartItems($session_id);
$cartTotal = $cart->getCartTotal($session_id);

if (empty($cartItems)) {
    header('Location: menu.php');
    exit();
}

// Hitung total
$ongkir = 10000;
$biayaLayanan = 2000;
$grandTotal = $cartTotal + $ongkir + $biayaLayanan;

// helper aman
function rupiah($n) {
    return 'Rp ' . number_format((float)$n, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Pemesanan - Astaguna Catering</title>

  <!-- CSS GLOBAL -->
  <link href="https://fonts.googleapis.com/css2?family=Salsa&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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
      margin-bottom: 10px;
    }

    .page-title p {
      font-size: 18px;
      opacity: 0.9;
    }

    /* ===== CHECKOUT CONTAINER ===== */
    .checkout-container {
      max-width: 1200px;
      margin: 40px auto;
      padding: 0 20px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
    }

    /* ===== CARD STYLES ===== */
    .card {
      background: white;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }

    .section-title {
      font-family: 'Salsa', cursive;
      font-size: 24px;
      color: #ff9f1c;
      margin-bottom: 25px;
      padding-bottom: 10px;
      border-bottom: 2px solid #eee;
    }

    /* ===== CART ITEMS STYLES ===== */
    .cart-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 0;
      border-bottom: 1px solid #eee;
    }

    .cart-item:last-child {
      border-bottom: none;
    }

    .item-info {
      flex: 1;
    }

    .item-name {
      font-weight: bold;
      font-size: 16px;
      margin-bottom: 5px;
    }

    .item-details {
      color: #666;
      font-size: 14px;
    }

    .item-details .muted {
      font-size: 13px;
      color: #6b7280;
    }

    .price-info {
      text-align: right;
      white-space: nowrap;
    }

    .item-price {
      font-weight: bold;
      color: #ff9f1c;
      font-size: 16px;
    }

    .item-subtotal {
      color: #666;
      font-size: 13px;
    }

    /* ===== TOTAL SECTION ===== */
    .total-section {
      background: #f9f9f9;
      padding: 25px;
      border-radius: 10px;
      border-left: 4px solid #ff9f1c;
      margin-top: 20px;
    }

    .total-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      font-size: 16px;
    }

    .total-row.grand-total {
      font-size: 20px;
      font-weight: bold;
      color: #ff9f1c;
      margin-top: 15px;
      padding-top: 15px;
      border-top: 2px solid #eee;
    }

    .total-label {
      color: #666;
    }

    .total-value {
      font-weight: 500;
    }

    /* ===== FORM STYLES ===== */
    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #333;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="tel"],
    .form-group input[type="date"],
    .form-group input[type="time"],
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 12px 15px;
      border: 2px solid #ddd;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.3s;
      font-family: inherit;
    }

    .form-group textarea {
      min-height: 100px;
      resize: vertical;
    }

    .form-group input[type="text"]:focus,
    .form-group input[type="email"]:focus,
    .form-group input[type="tel"]:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      border-color: #ff9f1c;
      outline: none;
    }

    .required {
      color: #ff4444;
    }

    /* ===== GRID LAYOUT ===== */
    .row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
    }

    /* ===== BUTTON STYLES ===== */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
      padding: 12px 24px;
      font-weight: bold;
      cursor: pointer;
      text-decoration: none;
      border: none;
      transition: all 0.3s;
      font-size: 16px;
    }

    .btn-primary {
      background: #2ed573;
      color: white;
      width: 100%;
    }

    .btn-primary:hover {
      background: #25c46a;
    }

    .btn-outline {
      background: white;
      color: #ff9f1c;
      border: 2px solid #ff9f1c;
    }

    .btn-outline:hover {
      background: #fff9f0;
    }

    /* ===== TOPBAR STYLES ===== */
    .topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
      margin: 30px 0;
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }

    .topbar-info {
      flex: 1;
    }

    .topbar-title {
      font-family: 'Salsa', cursive;
      font-size: 24px;
      color: #ff9f1c;
      margin-bottom: 5px;
    }

    .topbar-subtitle {
      color: #666;
      font-size: 14px;
    }

    .top-actions {
      display: flex;
      gap: 10px;
    }

    /* ===== BACK BUTTON ===== */
    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 20px;
      background: #ff9f1c;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      transition: background 0.3s;
    }

    .back-btn:hover {
      background: #e68a00;
    }

    /* ===== PAYMENT OPTIONS ===== */
    .payment-options {
      margin: 20px 0;
    }

    .payment-choice {
      display: flex;
      align-items: center;
      padding: 15px;
      border: 2px solid #ddd;
      border-radius: 10px;
      margin-bottom: 10px;
      cursor: pointer;
      transition: all 0.3s;
    }

    .payment-choice:hover {
      border-color: #ff9f1c;
      background: #fff9f0;
    }

    .payment-choice.selected {
      border-color: #ff9f1c;
      background: #ff9f1c;
      color: white;
    }

    .payment-choice.selected .payment-text {
      color: white;
      font-weight: bold;
    }

    .payment-choice input[type="radio"] {
      margin-right: 10px;
      transform: scale(1.2);
    }

    .payment-text {
      font-size: 16px;
      font-weight: 500;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
      .checkout-container {
        grid-template-columns: 1fr;
      }
      
      .row {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 768px) {
      .checkout-container {
        padding: 0 15px;
      }
      
      .card {
        padding: 20px;
      }
      
      .topbar {
        flex-direction: column;
        text-align: center;
      }
      
      .top-actions {
        flex-direction: column;
        width: 100%;
      }
      
      .btn-outline {
        width: 100%;
      }
    }

    @media (max-width: 480px) {
      .page-title h1 {
        font-size: 28px;
      }
      
      .page-title p {
        font-size: 16px;
      }
      
      .section-title {
        font-size: 20px;
      }
    }
  </style>
</head>

<body>

<section class="page-title">
  <h1>Pemesanan</h1>
  <p>Periksa pesanan dan lengkapi data pengiriman</p>
</section>

<div class="checkout-container">
  
  <!-- Left Column: Ringkasan Keranjang -->
  <div class="left-column">
    <div class="topbar">
      <div class="topbar-info">
        <div class="topbar-title">Ringkasan Keranjang</div>
        <div class="topbar-subtitle">Periksa pesanan kamu sebelum checkout</div>
      </div>
      <div class="top-actions">
        <a class="btn btn-outline" href="menu.php">
          <i class="fas fa-plus"></i> Tambah Menu
        </a>
        <a class="btn btn-outline" href="cart.php">
          <i class="fas fa-shopping-cart"></i> Kelola Keranjang
        </a>
      </div>
    </div>
    
    <div class="card">
      <h2 class="section-title">Detail Pesanan</h2>
      
      <div class="order-items">
        <?php foreach ($cartItems as $it): ?>
          <div class="cart-item">
            <div class="item-info">
              <div class="item-name"><?php echo htmlspecialchars($it['item_name']); ?></div>
              <div class="item-details">
                <span class="muted"><?php echo htmlspecialchars($it['item_type']); ?> • Qty: <?php echo (int)$it['quantity']; ?></span>
                <?php if (!empty($it['notes'])): ?>
                  <div class="muted" style="margin-top: 5px;">
                    <i class="fas fa-sticky-note"></i> Catatan: <?php echo htmlspecialchars($it['notes']); ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <div class="price-info">
              <div class="item-price"><?php echo rupiah($it['item_price']); ?></div>
              <div class="item-subtotal">Subtotal: <?php echo rupiah(((float)$it['item_price'] * (int)$it['quantity'])); ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      
      <div class="total-section">
        <div class="total-row">
          <span class="total-label">Subtotal</span>
          <span class="total-value"><?php echo rupiah($cartTotal); ?></span>
        </div>
        <div class="total-row">
          <span class="total-label">Ongkir</span>
          <span class="total-value"><?php echo rupiah($ongkir); ?></span>
        </div>
        <div class="total-row">
          <span class="total-label">Biaya Layanan</span>
          <span class="total-value"><?php echo rupiah($biayaLayanan); ?></span>
        </div>
        <div class="total-row grand-total">
          <span class="total-label">Total Pembayaran</span>
          <span class="total-value"><?php echo rupiah($grandTotal); ?></span>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Right Column: Form Pemesan -->
  <div class="right-column">
    <div class="card">
      <h2 class="section-title">Data Pemesan</h2>
      
      <form action="proses_order.php" method="POST" autocomplete="on" id="orderForm">
        <div class="form-group">
          <label>Nama Lengkap <span class="required">*</span></label>
          <input type="text" name="customer_name" required>
        </div>

        <div class="row">
          <div class="form-group">
            <label>No. HP / WhatsApp <span class="required">*</span></label>
            <input type="tel" name="customer_phone" required>
          </div>
          
          <div class="form-group">
            <label>Email (opsional)</label>
            <input type="email" name="customer_email">
          </div>
        </div>

        <div class="form-group">
          <label>Alamat Pengantaran <span class="required">*</span></label>
          <textarea name="delivery_address" rows="3" placeholder="Nama jalan, RT/RW, patokan..." required></textarea>
        </div>

        <div class="row">
          <div class="form-group">
            <label>Tanggal Antar (opsional)</label>
            <input type="date" name="delivery_date">
          </div>
          <div class="form-group">
            <label>Jam Antar (opsional)</label>
            <input type="time" name="delivery_time">
          </div>
        </div>

        <div class="form-group">
          <label>Catatan Pesanan (opsional)</label>
          <textarea name="notes" rows="2" placeholder="Contoh: pedas sedang, tanpa bawang, tambah nasi..."></textarea>
        </div>

        <div class="form-group">
          <label>Metode Pembayaran <span class="required">*</span></label>
          <div class="payment-options">
            <div class="payment-choice" onclick="selectPayment('cod')">
              <input type="radio" id="cod" name="payment_method" value="cod" checked>
              <div class="payment-text">COD (Bayar di Tempat)</div>
            </div>
            
            <div class="payment-choice" onclick="selectPayment('transfer')">
              <input type="radio" id="transfer" name="payment_method" value="transfer">
              <div class="payment-text">Transfer Bank</div>
            </div>
          </div>
        </div>

        <div class="form-group" id="bankField">
          <label>Bank (jika transfer)</label>
          <input name="bank" placeholder="Contoh: BCA / BRI / Mandiri">
        </div>

        <input type="hidden" name="csrf" value="1">
        <input type="hidden" name="cart_total" value="<?php echo $cartTotal; ?>">
        <input type="hidden" name="grand_total" value="<?php echo $grandTotal; ?>">
        
        <button class="btn btn-primary" type="submit">
          <i class="fas fa-check-circle"></i> Buat Pesanan (<?php echo rupiah($grandTotal); ?>)
        </button>
      </form>
    </div>
  </div>
</div>

<script>
const orderForm = document.getElementById('orderForm');
const bankField = document.getElementById('bankField');

// Sembunyikan bank field secara default
bankField.style.display = 'none';

// Fungsi untuk pilih pembayaran
function selectPayment(method) {
  // Hapus selected dari semua
  document.querySelectorAll('.payment-choice').forEach(choice => {
    choice.classList.remove('selected');
  });
  
  // Tambah selected ke yang dipilih
  const selectedChoice = document.querySelector(`[value="${method}"]`).closest('.payment-choice');
  selectedChoice.classList.add('selected');
  
  // Set radio button checked
  document.querySelector(`[value="${method}"]`).checked = true;
  
  // Tampilkan/sembunyikan bank field
  if (method === 'transfer') {
    bankField.style.display = 'block';
  } else {
    bankField.style.display = 'none';
  }
}

// Phone number formatting
const phoneInput = document.querySelector('input[name="customer_phone"]');
if (phoneInput) {
  phoneInput.addEventListener('input', function(e) {
    let value = e.target.value;
    value = value.replace(/\D/g, '');
    
    if (!value.startsWith('0')) {
      value = '0' + value;
    }
    
    if (value.length > 13) {
      value = value.substring(0, 13);
    }
    
    e.target.value = value;
  });
}

// Form validation
orderForm.addEventListener('submit', function(e) {
  e.preventDefault();
  
  let isValid = true;
  const errorMessages = [];
  
  // Validate required fields
  const requiredFields = ['customer_name', 'customer_phone', 'delivery_address'];
  requiredFields.forEach(fieldName => {
    const field = document.querySelector(`[name="${fieldName}"]`);
    if (field && field.value.trim() === '') {
      field.style.borderColor = '#ff4444';
      isValid = false;
      errorMessages.push(`Harap isi ${fieldName.replace('_', ' ')}`);
    } else if (field) {
      field.style.borderColor = '#ddd';
    }
  });
  
  // Validate phone number
  if (phoneInput && phoneInput.value.trim().length < 10) {
    phoneInput.style.borderColor = '#ff4444';
    isValid = false;
    errorMessages.push('Nomor telepon minimal 10 digit');
  }
  
  // Validate payment method
  const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
  if (!paymentMethod) {
    isValid = false;
    errorMessages.push('Pilih metode pembayaran');
  }
  
  // Jika transfer dipilih tapi bank kosong
  if (paymentMethod && paymentMethod.value === 'transfer') {
    const bankInput = document.querySelector('input[name="bank"]');
    if (!bankInput || bankInput.value.trim() === '') {
      bankInput.style.borderColor = '#ff4444';
      isValid = false;
      errorMessages.push('Harap isi nama bank untuk transfer');
    } else if (bankInput) {
      bankInput.style.borderColor = '#ddd';
    }
  }
  
  if (!isValid) {
    alert('Harap perbaiki kesalahan berikut:\n\n' + errorMessages.join('\n'));
    
    // Scroll to first error
    const firstError = document.querySelector('[style*="border-color: #ff4444"]');
    if (firstError) {
      firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    return;
  }
  
  // Tampilkan konfirmasi
  const nama = document.querySelector('[name="customer_name"]').value;
  const metode = paymentMethod.value === 'cod' ? 'COD (Bayar di Tempat)' : 'Transfer Bank';
  const konfirmasi = `Konfirmasi Pesanan:\n\nNama: ${nama}\nMetode: ${metode}\nTotal: <?php echo rupiah($grandTotal); ?>\n\nLanjutkan?`;
  
  if (confirm(konfirmasi)) {
    // Submit form
    this.submit();
  }
});

// Inisialisasi saat halaman dimuat
window.onload = function() {
  // Auto select COD
  selectPayment('cod');
  
  // Set tanggal minimal untuk hari ini
  const today = new Date().toISOString().split('T')[0];
  const dateInput = document.querySelector('input[name="delivery_date"]');
  if (dateInput) {
    dateInput.min = today;
  }
  
  // Pre-fill form jika ada data session
  <?php if (isset($_SESSION['order'])): ?>
    const orderData = <?php echo json_encode($_SESSION['order']); ?>;
    
    const fields = {
      'customer_name': orderData.customer_name || '',
      'customer_phone': orderData.customer_phone || '',
      'customer_email': orderData.customer_email || '',
      'delivery_address': orderData.delivery_address || '',
      'delivery_date': orderData.delivery_date || '',
      'delivery_time': orderData.delivery_time || '',
      'notes': orderData.notes || '',
      'bank': orderData.bank || ''
    };
    
    Object.keys(fields).forEach(fieldName => {
      const field = document.querySelector(`[name="${fieldName}"]`);
      if (field) {
        field.value = fields[fieldName];
      }
    });
    
    if (orderData.payment_method) {
      selectPayment(orderData.payment_method);
    }
  <?php endif; ?>
};
</script>

</body>
</html>