<?php
// dashboard.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Astaguna Jawa Catering</title>

<link href="https://fonts.googleapis.com/css2?family=Salsa&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css/dashboard.css">
</head>

<body>

<header>
  <div class="header">
    <div class="menu-btn" onclick="toggleMenu()">☰</div>

    <div class="logo">
      <img src="asset/plate.png" class="icon">
      Astaguna Jawa Catering
    </div>

    <!-- CART -->
    <a href="cart.php">
      <img src="asset/cart.png" class="icon">
    </a>
  </div>
</header>

<div class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <div class="menu-btn" onclick="toggleMenu()">☰</div>
  </div>
  <a href="menu.php">Menu</a>
  <a href="status.php">Status Pesanan</a>
  <a href="ulasan.php">Ulasan</a>
  <a href="cs.php">Customer Service</a>
</div>

<div class="overlay" id="overlay" onclick="toggleMenu()"></div>

<section class="hero">
  <div class="hero-content">
    <div class="hero-sub">
      <img src="asset/plate.png" class="icon">
      ASTAGUNA JAWA CATERING
    </div>
    <h1>Rasa Tradisi, Suguhan<br>Penuh Welas Asih</h1>

    <!-- PESAN -->
    <a href="menu.php">
      <button>pesan sekarang →</button>
    </a>
  </div>
</section>

<section class="best">
  <div class="best-title">- Our Best Seller Menu -</div>
  <div class="cards">

    <a href="menu.php#paket-content" class="card">
      <div class="img-box"><img src="asset/1.jpg"></div>
      <div class="bar"></div>
    </a>

    <a href="menu.php#paket-content" class="card">
      <div class="img-box"><img src="asset/2.jpg"></div>
      <div class="bar"></div>
    </a>

    <a href="menu.php#paket-content" class="card">
      <div class="img-box"><img src="asset/3.jpg"></div>
      <div class="bar"></div>
    </a>

    <a href="menu.php#paket-content" class="card">
      <div class="img-box"><img src="asset/4.jpg"></div>
      <div class="bar"></div>
    </a>

  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer>
  <div class="footer-container">
    <!-- Tentang Kami -->
    <div class="footer-section">
      <div class="footer-logo">
        <img src="asset/plate.png" alt="Logo">
        Astaguna Jawa Catering
      </div>
      <p>Melayani dengan hati, menyajikan cita rasa tradisional Jawa yang autentik untuk setiap momen spesial Anda.</p>
      <div class="social-icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-whatsapp"></i></a>
        <a href="#"><i class="fab fa-tiktok"></i></a>
      </div>
    </div>

    <!-- Kontak -->
    <div class="footer-section">
      <h3>Hubungi Kami</h3>
      <ul class="contact-info">
        <li>
          <i class="fas fa-map-marker-alt"></i>
          <span>Jl. Tradisi Jawa No. 123, Salatiga</span>
        </li>
        <li>
          <i class="fas fa-phone"></i>
          <span>(0274) 567-890</span>
        </li>
        <li>
          <i class="fas fa-envelope"></i>
          <span>info@astagunajawa.com</span>
        </li>
        <li>
          <i class="fas fa-clock"></i>
          <span>Buka Setiap Hari: 08.00 - 20.00 WIB</span>
        </li>
      </ul>
    </div>

    <!-- Layanan -->
    <div class="footer-section">
      <h3>Layanan Kami</h3>
      <ul>
        <li><a href="#"><i class="fas fa-utensil-spoon"></i> Catering Harian</a></li>
        <li><a href="#"><i class="fas fa-birthday-cake"></i> Catering Acara</a></li>
        <li><a href="#"><i class="fas fa-box"></i> Catering Kotak</a></li>
        <li><a href="#"><i class="fas fa-truck"></i> Pengiriman Gratis</a></li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom">
    <p>&copy; 2025 Astaguna Jawa Catering. Semua Hak Dilindungi.</p>
    <p>Website dibuat dengan ❤ untuk pecinta kuliner Jawa.</p>
  </div>
</footer>

<script src="js/dashboard.js"></script>
</body>
</html>