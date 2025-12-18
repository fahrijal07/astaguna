// js/admin.js

// Data dari PHP (dideklarasikan di admin.php)
// const labels, dataQty, totalQty

// Warna untuk pie chart
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

// Inisialisasi Chart
let salesChart = null;

// Modal Elements
const modal = document.getElementById('comingSoonModal');
const modalTitle = document.getElementById('modalTitle');
const modalSubtitle = document.getElementById('modalSubtitle');
const modalIcon = document.getElementById('modalIcon');
const modalMessage = document.getElementById('modalMessage');

// Fungsi untuk menginisialisasi dashboard
function initDashboard() {
  createChart();
  setupEventListeners();
  setupAutoRefresh();
}

// Fungsi untuk membuat chart
function createChart() {
  // Buat legend
  const chartLegend = document.getElementById('chartLegend');
  let legendHTML = '';

  if (labels && dataQty && totalQty) {
    labels.forEach((label, index) => {
      const qty = dataQty[index];
      const percentage = totalQty > 0 ? ((qty / totalQty) * 100).toFixed(1) : 0;
      
      legendHTML += `
        <div class="legend-item" data-index="${index}">
          <div class="legend-color" style="background-color: ${pieColors[index]}"></div>
          <div class="legend-text" title="${label}">${label}</div>
          <div class="legend-value">${qty.toLocaleString()} (${percentage}%)</div>
        </div>
      `;
    });
  }

  chartLegend.innerHTML = legendHTML;

  // Buat pie chart
  const ctx = document.getElementById('salesChart').getContext('2d');
  salesChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: labels || [],
      datasets: [{
        data: dataQty || [],
        backgroundColor: pieColors,
        borderColor: '#ffffff',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          titleColor: '#fff',
          bodyColor: '#fff',
          padding: 12,
          borderRadius: 8,
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
      }
    }
  });

  // Tambahkan efek interaktif pada legend
  setupChartLegendInteractions();
}

// Fungsi untuk setup interaksi legend chart
function setupChartLegendInteractions() {
  const legendItems = document.querySelectorAll('.legend-item');
  
  legendItems.forEach((item, index) => {
    item.addEventListener('click', function() {
      if (salesChart) {
        const meta = salesChart.getDatasetMeta(0);
        const isHidden = meta.data[index].hidden;
        
        // Toggle visibility
        meta.data[index].hidden = !isHidden;
        salesChart.update();
        
        // Update legend style
        if (meta.data[index].hidden) {
          this.style.opacity = '0.4';
        } else {
          this.style.opacity = '1';
        }
      }
    });
  });
}

// Modal Functions
function showModal(feature) {
  let title = '';
  let subtitle = '';
  let icon = 'fas fa-tools';
  let message = 'Fitur ini akan tersedia nanti';
  
  switch(feature) {
    case 'pesanan':
      title = 'Kelola Pesanan';
      subtitle = 'Fitur Manajemen Pesanan';
      icon = 'fas fa-box';
      message = 'Fitur manajemen pesanan akan segera hadir untuk memudahkan Anda mengelola semua pesanan pelanggan.';
      break;
    case 'menu':
      title = 'Kelola Menu';
      subtitle = 'Fitur Manajemen Menu';
      icon = 'fas fa-utensils';
      message = 'Fitur manajemen menu akan segera hadir untuk mengatur menu catering dengan lebih mudah.';
      break;
    case 'pelanggan':
      title = 'Data Pelanggan';
      subtitle = 'Fitur Manajemen Pelanggan';
      icon = 'fas fa-users';
      message = 'Fitur manajemen pelanggan akan segera hadir untuk mengelola data pelanggan dengan lebih baik.';
      break;
    default:
      title = 'Fitur Akan Segera Hadir';
      subtitle = 'Dalam Pengembangan';
  }
  
  modalTitle.textContent = title;
  modalSubtitle.textContent = subtitle;
  modalIcon.className = icon;
  modalMessage.textContent = message;
  
  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function hideModal() {
  modal.classList.remove('active');
  document.body.style.overflow = 'auto';
}

// Setup event listeners
function setupEventListeners() {
  // Tutup modal dengan klik di luar konten
  if (modal) {
    modal.addEventListener('click', function(event) {
      if (event.target === modal) {
        hideModal();
      }
    });
  }

  // Tutup modal dengan tombol ESC
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && modal && modal.classList.contains('active')) {
      hideModal();
    }
  });

  // Tambahkan event listener untuk menu items
  const menuItems = document.querySelectorAll('.menu-item[onclick*="showModal"]');
  menuItems.forEach(item => {
    // Hapus inline onclick dan ganti dengan event listener
    const originalOnClick = item.getAttribute('onclick');
    if (originalOnClick) {
      item.removeAttribute('onclick');
      const feature = originalOnClick.match(/showModal\('(.+)'\)/)[1];
      item.addEventListener('click', () => showModal(feature));
    }
  });

  // Tambahkan event listener untuk tombol modal
  const modalBtn = document.querySelector('.modal-btn');
  if (modalBtn) {
    modalBtn.addEventListener('click', hideModal);
  }
}

// Setup auto-refresh
function setupAutoRefresh() {
  // Auto-refresh data setiap 5 menit (300000 ms)
  setTimeout(() => {
    window.location.reload();
  }, 5 * 60 * 1000);
}

// Initialize dashboard ketika DOM siap
document.addEventListener('DOMContentLoaded', function() {
  // Cek apakah kita berada di dashboard (ada element salesChart)
  if (document.getElementById('salesChart')) {
    initDashboard();
  }
});