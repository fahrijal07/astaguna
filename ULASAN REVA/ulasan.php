<?php
include 'config.php';

// ambil data ulasan
$sql = "SELECT * FROM ulasan ORDER BY id ASC";
$result = mysqli_query($koneksi, $sql);
$reviews = [];
while ($r = mysqli_fetch_assoc($result)) $reviews[] = $r;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Astaguna Jawa Catering — Ulasan</title>
<link href="https://fonts.googleapis.com/css2?family=Salsa&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
/* Reset minimal */
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: "Salsa", sans-serif;
   background-color: #fff; 
   }
   /* ======== NAVBAR ======== */
.navbar {
    display: flex;
    align-items: center ;
    justify-content: space-between;
    padding: 12px 10px;
    background:rgb(255, 255, 255);
    border-bottom: 2px solid #e6e6e6;
    border-radius: 10px;
    margin-bottom: 0px;
}

.menu-btn {
    font-size: 28px;
    cursor: pointer;
}

.logo-icon {
    width: 30px;
    margin-right: 6px;
    color: #ff9f1c;
}

.nav-title {
    display: flex;
    font-family: 'salsa', cursive;
    align-items: center;
    font-size: 20px;
    font-weight: bold;
}

.nav-right img {
    width: 28px;
    cursor: pointer;
}

/* ======== SIDEBAR ======== */
.sidebar {
    position: fixed;
    top: 0;
    left: -260px;
    width: 240px;
    height: 100%;
    background:#ff9f1c;
    box-shadow: 3px 0px 8px rgba(0,0,0,0.2);
    padding-top: 70px;
    transition: 0.3s;
    border-right: 2px solid #e0e0e0;
    z-index: 9999;
}

.sidebar a {
    display: block;
    padding: 14px 22px;
    font-size: 17px;
    color: black;
    text-decoration: none;
    border-bottom: 1px solid #f0f0f0;
}

.sidebar a:hover {
    background: #ffe2b3;
}
.sidebar.active {
    left: 0;
}

/* ======= OVERLAY ======= */
#overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.4);
    display: none;
    z-index: 500;
}

#overlay.active {
    display: block;
}

/* Layout dua kolom */
.page {
  max-width: 1200px;
  margin: 24px auto;
  padding: 20px;
  display: grid;
  grid-template-columns: 1fr 460px;
  gap: 40px;
  align-items: start;
}

/* Kartu besar kiri (rounded + orange border + inner shadow) */
.left-panel {
    justify-content: center;
  background: #fff;
  border-radius: 48px;
  margin-top: 70px;
  padding: 36px;
  position: relative;
  box-shadow: 0 7px 0 #e28b00, 0 10px 18px rgba(0,0,0,0.12);
  border: 6px solid #ff9a1a;
  overflow: hidden;
}
.left-panel .title {
  font-family: "salsa", cursive;
  color: #e28b00;
  font-size: 44px;
  text-shadow: 2px 2px rgba(0,0,0,0.15);
  line-height: 1;
  margin-bottom: 10px;
}
.left-panel .subtitle {
  color: #222;
  font-size: 18px;
  margin-bottom: 28px;
  filter: drop-shadow(1px 1px 0 #fff);
}

/* Statistik kotak kecil */
.stats {
  display:flex;
  justify-content: space-evenly;
  gap:18px;
  margin-top: 20px;
}
.stat {
  width:120px;
  height:110px;
  border-radius:18px;
  border:2px solid #222;
  display:flex;
  flex-direction:column;
  justify-content:center;
  align-items:center;
  background:#fff;
  box-shadow: 0 6px 0 rgba(0,0,0,0.08);
}
.stat .num { font-size:28px; font-weight:700; color:#222; text-shadow: 1px 1px #fff; }
.stat .label { font-size:12px; color:#666; margin-top:6px; }

/* Panel kanan: daftar ulasan */
.right-panel {
  display:flex;
  flex-direction:column;
  gap:28px;
}

/* kartu ulasan */
.review-card {
  background:#fff;
  border-radius:18px;
  padding:18px 20px;
  display:flex;
  gap:14px;
  align-items:flex-start;
  border:3px solid #777;
  box-shadow: 12px 10px 0 rgba(0,0,0,0.18);
}

/* avatar bulat */
.avatar {
  width:56px; height:56px; border-radius:50%;
  flex-shrink:0;
  border: 4px solid #fff;
  box-shadow: 0 3px 0 rgba(0,0,0,0.12);
  background: #f2f2f2;
  object-fit:cover;
}
.btn-ulasan{
  display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;

    background-color: #e28b00;
    color: #fff;
    padding: 15px;
    border-radius: 12px;
    width: fit-content;
    text-decoration: none;
    font-weight: bold;
    margin-top: 20px;
    cursor: pointer;
    font-family: "poppins";
    margin-left: auto;
}
.btn-ulasan .icon{
  width: 22px;
    height: 22px;
    
}



/* nama + rating row */
.meta {
  display:flex;
  align-items:center;
  gap:12px;
  width:100%;
}
.nama { font-size:20px; font-weight:700; color:#111; }
.stars { margin-left:auto; color:#ffcf2f; font-size:18px; }



/* responsive */
@media (max-width: 980px) {
  .page { grid-template-columns: 1fr; padding: 12px; }
  .right-panel { order: 2; }
  .left-panel { order: 1; }
  .review-card { box-shadow: 8px 6px 0 rgba(0,0,0,0.12); }
}
</style>
<script>
function toggleSidebar() {
    let sb = document.getElementById("sidebar");
    sb.style.left = (sb.style.left === "0px") ? "-260px" : "0px";
}
</script>
</head>
<body>
  <!-- ===== NAVBAR ===== -->
<div class="navbar">
    <div class="nav-left">
        <span class="menu-btn" onclick="toggleSidebar()">☰</span>
    </div>

    <div class="nav-title">
        <img src="img/plate.png" class="logo-icon">
        <span>Astaguna Jawa Catering</span>
    </div>

    <div class="nav-right">
        <img src="img/cart.png">
    </div>
</div>

<!-- ===== SIDEBAR ===== -->
<div class="sidebar" id="sidebar">
    <a href="menu.php">Menu Paket</a>
    <a href="menu_costum.php">Menu Costum</a>
    <a href="transaksi.php">Transaksi</a>
    <a href="status.php">Status</a>
    <a href="ulasan.php">Ulasan</a>
</div>
<div id="overlay" onclick="closeSidebar()"></div>


<div class="page">

  <!-- LEFT: besar -->
  <div class="left-panel">
    <div class="title">Jejak Rasa dari Para Pelanggan~</div>
    <div class="subtitle">Beberapa review pelanggan setia kami</div>

    <div class="stats">
      <div class="stat">
        <div class="num">650</div>
        <div class="label">Review</div>
      </div>
      <div class="stat">
        <div class="num">2000 +</div>
        <div class="label">Happy customers</div>
      </div>
      <div class="stat">
        <div class="num">5+</div>
        <div class="label">experience</div>
      </div>
    </div>

  </div>

  <!-- RIGHT: ulasan -->
  <div class="right-panel">
    <?php
    foreach ($reviews as $r) {
        // gunakan gambar fixed di folder img/
        $img = 'img/icon.png';
        $name = htmlspecialchars($r['nama']);
        $text = htmlspecialchars($r['text']);
        $rating = intval($r['rating']);
        echo '<div class="review-card">';
        echo '<img class="avatar" src="'.$img.'" alt="avatar">';
        echo '<div style="flex:1">';
        echo '<div class="meta"><div class="nama">'.$name.'</div>';
        // tampilkan bintang (max 5)
        echo '<div class="stars">';
        for ($i=0;$i<5;$i++){
            if ($i < $rating) echo '★'; else echo '☆';
        }
        echo '</div></div>';
        echo '<div class="quote">'.$text.'</div>';
        echo '</div></div>';
    }
    ?>
  </div>

</div>
<a class="btn-ulasan" 
               href="input_ulasan.php" href="input_ulasan.php">
               beri ulasan sekarang
               <img src="img/chat.png" class="icon">
            </a>
        
<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

function toggleSidebar() {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}

function closeSidebar() {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
}
</script>
</body>
</html>

<?php mysqli_close($koneksi); ?>
