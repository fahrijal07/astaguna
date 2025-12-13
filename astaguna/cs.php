<?php
// cs.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Service - Astaguna Jawa Catering</title>

<link href="https://fonts.googleapis.com/css2?family=Salsa&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/cs.css">
</head>

<body>

<!-- POPUP UNTUK CALL ICON -->
<div class="popup-overlay" id="callPopup">
  <div class="popup-content">
    <img src="asset/call.png" class="popup-icon" alt="Call Icon">
    <div class="popup-title">Fitur Telepon</div>
    <div class="popup-message">Fitur ini akan tersedia nanti. Kami sedang mengembangkan fitur telepon untuk memberikan pelayanan yang lebih baik.</div>
    <button class="popup-close-btn" onclick="closeCallPopup()">Mengerti</button>
  </div>
</div>

<header>
  <!-- HOME ICON untuk kembali ke dashboard -->
  <a href="dashboard.php">
    <img src="asset/home.png" class="home-icon" alt="Home">
  </a>

  <div class="header-title">
    <img src="asset/cs.png" class="icon">
    Customer Service
  </div>

  <img src="asset/call.png" class="icon icon-orange" onclick="openCallPopup()" style="cursor: pointer;">
</header>

<div class="container">
  <div class="chat-box" id="chatBox">
    <div class="chat-header">
      <h2>Hai Pelanggan,</h2>
      <span>
        <img src="asset/time.png" class="icon">
        pelayanan
      </span>
    </div>

    <p>Boleh tolong jelaskan kendala yang anda alami?</p>

    <div class="chat-content" id="chatContent">
      <!-- Pesan contoh (tampilan awal seperti sebelumnya) -->
      <div class="message">Kapan pesanan saya diantar?</div>
      <div class="message">Bagaimana cara melakukan pesanan?</div>
      <div class="message">Bagaimana cara cek apakah pesanan telah diantar?</div>
    </div>
  </div>
</div>

<!-- ===== FIX BOTTOM ===== -->
<div class="chat-footer">
  <div class="quick">
    <span onclick="handleQuickReply('Tanya Pesanan')">
      <img src="asset/notes.png">
      Tanya Pesanan
    </span>
    <span onclick="handleQuickReply('Menu Error')">Menu Error</span>
    <span onclick="handleQuickReply('Pembayaran Error')">Pembayaran Error</span>
    <span onclick="handleQuickReply('Kirim Form Gagal')">Kirim Form Gagal</span>
  </div>

  <div class="input-area">
    <input type="text" id="chatInput" placeholder="Ketik ..." onkeypress="handleKeyPress(event)">
    <button onclick="sendMessage()">
      <img src="asset/send.png" class="icon icon-orange">
    </button>
  </div>
</div>

<script src="js/cs.js"></script>
</body>
</html>