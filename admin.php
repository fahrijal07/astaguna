<?php
// admin.php
session_start();

// Kredensial login
$valid_username = 'admin';
$valid_password = 'admin';

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $is_logged_in = true;
    } else {
        $error_message = 'Username atau password salah!';
    }
}

// Proses logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Jika belum login, tampilkan form login
if (!$is_logged_in):
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Astaguna Catering</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-header">
            <h1>Login Admin</h1>
            <p>Astaguna Jawa Catering</p>
        </div>
        
        <div class="login-body">
            <?php if (isset($error_message)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-control" 
                           placeholder="Masukkan username" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Masukkan password" 
                           required>
                </div>
                
                <button type="submit" name="login" class="login-btn">
                    Masuk ke Dashboard
                </button>
            </form>
            
            <div class="login-footer">
                <p>Login hanya untuk administrator yang berwenang</p>
            </div>
        </div>
    </div>
    
    <script>
        // Auto-focus pada input username
        document.getElementById('username').focus();
        
        // Tambahkan efek pada form
        const formControls = document.querySelectorAll('.form-control');
        formControls.forEach(control => {
            control.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            control.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
    </script>
</body>
</html>
<?php
else:
// Jika sudah login, tampilkan dashboard penjualan
require_once __DIR__ . '/models/Order.php';

$orderModel = new Order();
$top = $orderModel->getSalesTopItems(10);

$labels = [];
$qtys = [];
$revenues = [];

foreach ($top as $t) {
    $labels[] = $t['item_name'];
    $qtys[] = (int)$t['total_qty'];
    $revenues[] = (float)$t['total_revenue'];
}

// Hitung total
$total_qty = array_sum($qtys);
$total_revenue = array_sum($revenues);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Astaguna Catering</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/admin.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="dashboard-page">
<div class="container">
  
  <!-- Admin Header -->
  <div class="admin-header">
    <div class="admin-info">
      <div class="admin-avatar">A</div>
      <div class="admin-details">
        <h2>Administrator</h2>
        <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
      </div>
    </div>
    <a href="admin.php?logout=true" class="logout-btn">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>
  
  <!-- Admin Menu -->
  <div class="admin-menu">
    <a href="admin.php" class="menu-item">
      <div class="menu-icon">
        <i class="fas fa-chart-bar"></i>
      </div>
      <div class="menu-text">
        <h3>Dashboard</h3>
        <p>Statistik penjualan</p>
      </div>
    </a>
    
    <div class="menu-item" onclick="showModal('pesanan')">
      <div class="menu-icon">
        <i class="fas fa-box"></i>
      </div>
      <div class="menu-text">
        <h3>Pesanan</h3>
        <p>Kelola pesanan</p>
      </div>
    </div>
    
    <div class="menu-item" onclick="showModal('menu')">
      <div class="menu-icon">
        <i class="fas fa-utensils"></i>
      </div>
      <div class="menu-text">
        <h3>Menu</h3>
        <p>Kelola menu</p>
      </div>
    </div>
    
    <div class="menu-item" onclick="showModal('pelanggan')">
      <div class="menu-icon">
        <i class="fas fa-users"></i>
      </div>
      <div class="menu-text">
        <h3>Pelanggan</h3>
        <p>Data pelanggan</p>
      </div>
    </div>
  </div>
  
  <h1 class="page-title">Dashboard Penjualan</h1>
  
  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-box"></i>
      </div>
      <div class="stat-value"><?php echo number_format($total_qty, 0, ',', '.'); ?></div>
      <div class="stat-label">Total Item Terjual</div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-money-bill-wave"></i>
      </div>
      <div class="stat-value">Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></div>
      <div class="stat-label">Total Omzet</div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-star"></i>
      </div>
      <div class="stat-value"><?php echo count($top); ?></div>
      <div class="stat-label">Item Terlaris</div>
    </div>
  </div>
  
  <!-- Main Content -->
  <div class="content-grid">
    
    <!-- Chart -->
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">
          <i class="fas fa-chart-pie"></i> Diagram Penjualan
        </h2>
      </div>
      <div class="chart-container">
        <canvas id="salesChart"></canvas>
      </div>
      <div class="chart-legend" id="chartLegend">
        <!-- Legend akan diisi oleh JavaScript -->
      </div>
    </div>
    
    <!-- Table -->
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">
          <i class="fas fa-list-ol"></i> 10 Item Terlaris
        </h2>
      </div>
      <div class="table-container">
        <?php if (empty($top)): ?>
          <div class="empty-state">
            <i class="fas fa-chart-pie"></i>
            <p>Belum ada data penjualan</p>
          </div>
        <?php else: ?>
          <table class="sales-table">
            <thead>
              <tr>
                <th class="rank">#</th>
                <th>Nama Item</th>
                <th class="quantity">Qty</th>
                <th class="revenue">Omzet</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($top as $index => $t): ?>
                <?php $rank = $index + 1; ?>
                <tr>
                  <td class="rank">
                    <?php if ($rank <= 3): ?>
                      <span style="color:#ff9f1c;"><?php echo $rank; ?></span>
                    <?php else: ?>
                      <?php echo $rank; ?>
                    <?php endif; ?>
                  </td>
                  <td class="item-name">
                    <?php echo htmlspecialchars($t['item_name']); ?>
                    <?php if ($rank <= 3): ?>
                      <span class="badge">TOP</span>
                    <?php endif; ?>
                  </td>
                  <td class="quantity">
                    <?php echo number_format((int)$t['total_qty'], 0, ',', '.'); ?>
                  </td>
                  <td class="revenue">
                    Rp <?php echo number_format((float)$t['total_revenue'], 0, ',', '.'); ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
    
  </div>
  
  <!-- Footer -->
  <div class="footer">
    <p>© <?php echo date('Y'); ?> Astaguna Catering • Data per <?php echo date('d/m/Y'); ?> • Admin Panel v1.0</p>
  </div>
  
  <!-- Modal Popup -->
  <div class="modal-overlay" id="comingSoonModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="modalTitle">Fitur Akan Segera Hadir</h2>
        <p id="modalSubtitle">Dalam Pengembangan</p>
      </div>
      <div class="modal-body">
        <div class="modal-icon">
          <i class="fas fa-tools" id="modalIcon"></i>
        </div>
        <p class="modal-message" id="modalMessage">Fitur ini akan tersedia nanti</p>
        <p class="modal-note">
          Kami sedang bekerja keras untuk menghadirkan fitur ini secepatnya.
          Terima kasih atas kesabaran Anda.
        </p>
      </div>
      <div class="modal-footer">
        <button class="modal-btn" onclick="hideModal()">Mengerti</button>
      </div>
    </div>
  </div>
  
</div>

<script>
// Data dari PHP
const labels = <?php echo json_encode($labels, JSON_UNESCAPED_UNICODE); ?>;
const dataQty = <?php echo json_encode($qtys); ?>;
const totalQty = <?php echo $total_qty; ?>;
</script>
<script src="js/admin.js"></script>
</body>
</html>
<?php
endif;
?>