<?php
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
  <title>Detail Penjualan - Astaguna Catering</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    
    body {
      background-color: #f8f9fa;
      padding: 20px;
      min-height: 100vh;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
    }
    
    /* Header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 15px;
      border-bottom: 2px solid #e9ecef;
    }
    
    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      background: #ff9f1c;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 600;
      transition: background 0.2s;
    }
    
    .back-btn:hover {
      background: #e68a00;
    }
    
    .page-title {
      font-size: 28px;
      font-weight: 700;
      color: #333;
    }
    
    /* Stats Grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-bottom: 30px;
    }
    
    @media (max-width: 768px) {
      .stats-grid {
        grid-template-columns: 1fr;
      }
    }
    
    .stat-card {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      border-top: 4px solid #ff9f1c;
    }
    
    .stat-icon {
      font-size: 32px;
      color: #ff9f1c;
      margin-bottom: 15px;
    }
    
    .stat-value {
      font-size: 28px;
      font-weight: 700;
      color: #333;
      margin-bottom: 5px;
    }
    
    .stat-label {
      color: #666;
      font-size: 14px;
      font-weight: 500;
    }
    
    /* Content Grid */
    .content-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
      margin-bottom: 40px;
    }
    
    @media (max-width: 992px) {
      .content-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }
    }
    
    .card {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
      padding-bottom: 15px;
      border-bottom: 2px solid #f0f0f0;
    }
    
    .card-title {
      font-size: 20px;
      font-weight: 700;
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .card-title i {
      color: #ff9f1c;
    }
    
    /* Chart Container */
    .chart-container {
      height: 320px;
      width: 100%;
      position: relative;
    }
    
    /* Chart Legend */
    .chart-legend {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 20px;
      max-height: 120px;
      overflow-y: auto;
      padding: 10px;
      background: #f9f9f9;
      border-radius: 8px;
    }
    
    .legend-item {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 6px 12px;
      background: white;
      border-radius: 6px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      font-size: 12px;
      flex: 1 0 calc(50% - 10px);
      min-width: 140px;
    }
    
    .legend-color {
      width: 16px;
      height: 16px;
      border-radius: 4px;
      flex-shrink: 0;
    }
    
    .legend-text {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      flex-grow: 1;
    }
    
    .legend-value {
      font-weight: 600;
      color: #333;
      flex-shrink: 0;
    }
    
    /* Table */
    .table-container {
      overflow-x: auto;
    }
    
    .sales-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    
    .sales-table thead {
      background: #ff9f1c;
    }
    
    .sales-table th {
      padding: 15px;
      text-align: left;
      color: white;
      font-weight: 600;
      font-size: 14px;
    }
    
    .sales-table td {
      padding: 15px;
      border-bottom: 1px solid #f0f0f0;
      font-size: 14px;
    }
    
    .sales-table tbody tr:hover {
      background: #f9f9f9;
    }
    
    .rank {
      width: 50px;
      text-align: center;
      font-weight: 700;
      color: #ff9f1c;
    }
    
    .item-name {
      font-weight: 500;
    }
    
    .quantity {
      text-align: center;
      font-weight: 600;
      color: #e68a00;
    }
    
    .revenue {
      text-align: right;
      font-weight: 700;
      color: #28a745;
    }
    
    .badge {
      display: inline-block;
      padding: 3px 10px;
      background: #ffd700;
      color: #333;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 700;
      margin-left: 8px;
    }
    
    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 50px 20px;
      color: #888;
    }
    
    .empty-state i {
      font-size: 48px;
      color: #ddd;
      margin-bottom: 20px;
    }
    
    /* Footer */
    .footer {
      text-align: center;
      padding: 20px;
      color: #888;
      font-size: 14px;
      border-top: 1px solid #eee;
      margin-top: 30px;
    }
    
    /* Responsive */
    @media (max-width: 576px) {
      .header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
      }
      
      .card {
        padding: 20px;
      }
      
      .sales-table th,
      .sales-table td {
        padding: 12px 10px;
        font-size: 13px;
      }
      
      .chart-container {
        height: 280px;
      }
      
      .legend-item {
        flex: 1 0 100%;
      }
      
      .chart-legend {
        max-height: 150px;
      }
    }
  </style>
</head>
<body>
<div class="container">
  
  <!-- Header dengan Back Button -->
  <div class="header">
    <a href="dashboard.php" class="back-btn">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <h1 class="page-title">Detail Penjualan</h1>
  </div>
  
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
    <p>© <?php echo date('Y'); ?> Astaguna Catering • Data per <?php echo date('d/m/Y'); ?></p>
  </div>
  
</div>

<script>
// Data dari PHP
const labels = <?php echo json_encode($labels, JSON_UNESCAPED_UNICODE); ?>;
const dataQty = <?php echo json_encode($qtys); ?>;

// Warna untuk pie chart (palet warna yang bagus untuk pie chart)
const pieColors = [
  '#FF9F1C', // Orange utama
  '#FF6384', // Merah muda
  '#36A2EB', // Biru
  '#FFCE56', // Kuning
  '#4BC0C0', // Cyan
  '#9966FF', // Ungu
  '#FF9F40', // Orange tua
  '#C9CBCF', // Abu-abu
  '#FF6384', // Merah muda lagi
  '#4BC0C0'  // Cyan lagi
];

// Warna untuk hover (lebih terang)
const hoverColors = [
  '#FFB347',
  '#FF7B9C',
  '#5DB2FF',
  '#FFD978',
  '#6BD0D0',
  '#B38CFF',
  '#FFB86C',
  '#E0E2E5',
  '#FF7B9C',
  '#6BD0D0'
];

// Hitung total untuk persentase
const totalQty = <?php echo $total_qty; ?>;

// Buat legend
const chartLegend = document.getElementById('chartLegend');
let legendHTML = '';

labels.forEach((label, index) => {
  const qty = dataQty[index];
  const percentage = totalQty > 0 ? ((qty / totalQty) * 100).toFixed(1) : 0;
  
  legendHTML += `
    <div class="legend-item">
      <div class="legend-color" style="background-color: ${pieColors[index]}"></div>
      <div class="legend-text" title="${label}">${label}</div>
      <div class="legend-value">${qty.toLocaleString()} (${percentage}%)</div>
    </div>
  `;
});

chartLegend.innerHTML = legendHTML;

// Buat pie chart
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: labels,
    datasets: [{
      data: dataQty,
      backgroundColor: pieColors,
      borderColor: '#ffffff',
      borderWidth: 2,
      hoverBackgroundColor: hoverColors,
      hoverBorderWidth: 3
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false // Nonaktifkan legend default karena kita buat custom
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        titleColor: '#fff',
        bodyColor: '#fff',
        padding: 12,
        callbacks: {
          label: function(context) {
            const label = context.label || '';
            const value = context.raw || 0;
            const total = context.dataset.data.reduce((a, b) => a + b, 0);
            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
            return `${label}: ${value.toLocaleString()} (${percentage}%)`;
          }
        }
      }
    },
    animation: {
      animateScale: true,
      animateRotate: true,
      duration: 1000
    }
  }
});

// Tambahkan efek interaktif pada legend
document.querySelectorAll('.legend-item').forEach((item, index) => {
  item.addEventListener('mouseenter', function() {
    // Highlight pie slice ketika hover legend
    salesChart.setActiveElements([{datasetIndex: 0, index: index}]);
    salesChart.update();
  });
  
  item.addEventListener('mouseleave', function() {
    // Reset ketika mouse keluar
    salesChart.setActiveElements([]);
    salesChart.update();
  });
  
  item.addEventListener('click', function() {
    // Fokus ke slice tertentu ketika diklik
    const meta = salesChart.getDatasetMeta(0);
    meta.data[index].hidden = !meta.data[index].hidden;
    salesChart.update();
    
    // Update legend style
    if (meta.data[index].hidden) {
      this.style.opacity = '0.5';
    } else {
      this.style.opacity = '1';
    }
  });
});
</script>

</body>
</html>