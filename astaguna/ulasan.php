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
    color: #ff9f1c;
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

/* ===== SIDEBAR ===== */
.sidebar{
  font-family:'Salsa',cursive;
  position:fixed;
  top:0;
  left:-270px;
  width:270px;
  height:100vh;
  background:#ff9f1c;
  transition:.3s;
  z-index:3500;
  text-shadow:none;
}
.sidebar *{text-shadow:none;}
.sidebar.active{left:0}

.sidebar-header{
  height:70px;
  display:flex;
  align-items:center;
  padding:0 20px;
}
.sidebar .menu-btn{color:#000;}
.sidebar a{
  display:block;
  color:#fff;
  text-decoration:none;
  padding:18px 25px;
  font-size:20px;
  transition: background 0.3s;
}
.sidebar a:hover {
  background: rgba(255, 255, 255, 0.1);
}

/* ===== OVERLAY ===== */
.overlay{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.4);
  display:none;
  z-index:3200;
}
.overlay.active{display:block}

/* ICON */
.icon{
  width:26px;
  height:26px;
  filter: invert(62%) sepia(87%) saturate(423%)
          hue-rotate(358deg) brightness(101%) contrast(101%);
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
</head>
<body>
  <!-- ===== NAVBAR ===== -->
<div class="navbar">
    <div class="nav-left">
        <span class="menu-btn" onclick="toggleMenu()">☰</span>
    </div>

    <div class="nav-title">
        <img src="img/plate.png" class="logo-icon">
        <span>Astaguna Jawa Catering</span>
    </div>

    <div class="nav-right">
        <a href="cart.php" style="position: relative; display: inline-block;">
            <img src="img/cart.png" class="icon">
        </a>
    </div>
</div>

<!-- ===== SIDEBAR ===== -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <span class="menu-btn" onclick="toggleMenu()">☰</span>
    </div>
    <a href="dashboard.php">Home</a>
    <a href="menu.php">Menu</a>
    <a href="cs.php">Customer Service</a>
</div>
<div class="overlay" id="overlay" onclick="toggleMenu()"></div>


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

function toggleMenu(){
  sidebar.classList.toggle('active');
  overlay.classList.toggle('active');
}

// Close sidebar when clicking on overlay
overlay.addEventListener('click', function() {
  toggleMenu();
});

// Close sidebar when clicking on a link
document.querySelectorAll('.sidebar a').forEach(link => {
  link.addEventListener('click', function() {
    toggleMenu();
  });
});
</script>
</body>
</html>

<?php mysqli_close($koneksi); ?>