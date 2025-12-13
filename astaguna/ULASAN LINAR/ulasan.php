<?php
// ulasan.php
session_start();

// Dummy data untuk review pelanggan
$reviews = [
    [
        'name' => 'Costumer 1',
        'rating' => 5,
        'comment' => 'Sangat puas! Makanan lezat, sesuai tema "Rasa Tradisi", dan pelayanannya profesional. Tidak mengecewakan!',
        'date' => '2 hari yang lalu'
    ],
    [
        'name' => 'Costumer 2',
        'rating' => 5,
        'comment' => 'Catering terbaik yang pernah saya coba. Makanan selalu datang tepat waktu dan rasanya lezat. Astaguna memang top!',
        'date' => '1 minggu yang lalu'
    ],
    [
        'name' => 'Costumer 3',
        'rating' => 5,
        'comment' => 'Kualitas makanan bintang lima dengan harga terjangkau. Semua hidangan bersih dan segar. Terima kasih Astaguna!',
        'date' => '2 minggu yang lalu'
    ],
    [
        'name' => 'Budi Santoso',
        'rating' => 5,
        'comment' => 'Acara pernikahan saya sukses berkat Astaguna. Tamu-tamu semua memuji makanannya yang autentik dan lezat!',
        'date' => '1 bulan yang lalu'
    ],
    [
        'name' => 'Sari Dewi',
        'rating' => 5,
        'comment' => 'Pesan untuk acara kantor, semua kolega suka. Nasi kuning dan ayam lodho favorit semua orang!',
        'date' => '3 minggu yang lalu'
    ],
    [
        'name' => 'Rudi Hartono',
        'rating' => 4,
        'comment' => 'Pelayanan cepat dan makanan enak. Hanya saja packaging bisa ditingkatkan lagi. Overall sangat memuaskan!',
        'date' => '5 hari yang lalu'
    ]
];

// Handle form submission untuk review baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $newReview = [
        'name' => htmlspecialchars($_POST['name'] ?? 'Anonymous'),
        'rating' => (int)($_POST['rating'] ?? 5),
        'comment' => htmlspecialchars($_POST['comment'] ?? ''),
        'date' => 'Baru saja'
    ];
    
    // Tambahkan ke awal array
    array_unshift($reviews, $newReview);
    
    // Simpan ke session untuk persistensi (dalam aplikasi nyata, simpan ke database)
    $_SESSION['reviews'][] = $newReview;
}

// Gabungkan dengan review dari session jika ada
if (isset($_SESSION['reviews'])) {
    $reviews = array_merge($_SESSION['reviews'], $reviews);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ulasan - Astaguna Jawa Catering</title>

<link href="https://fonts.googleapis.com/css2?family=Salsa&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
*{margin:0;padding:0;box-sizing:border-box}
body{
  font-family:'Roboto',sans-serif;
  background:#f5f5f5;
  color:#333;
}

/* ===== HEADER ===== */
header{
  position:fixed;
  top:0;left:0;
  width:100%;
  height:70px;
  background:#fff;
  box-shadow:0 2px 5px rgba(0,0,0,.1);
  z-index:3000;
}
.header{
  height:100%;
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:0 20px;
}

/* MENU BUTTON */
.menu-btn{
  font-size:26px;
  cursor:pointer;
  color:#ff9f1c;
}

/* ICON */
.icon{
  width:26px;
  height:26px;
  filter: invert(62%) sepia(87%) saturate(423%)
          hue-rotate(358deg) brightness(101%) contrast(101%);
}

/* LOGO HEADER */
.logo{
  font-family:'Salsa',cursive;
  font-size:22px;
  font-weight:700;
  display:flex;
  align-items:center;
  gap:8px;
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

/* ===== PAGE TITLE ===== */
.page-title {
  margin-top: 70px;
  background: linear-gradient(135deg, #ff9f1c 0%, #ff8a00 100%);
  color: white;
  padding: 40px 20px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.page-title::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path fill="rgba(255,255,255,0.1)" d="M0,0 L100,0 L100,100 Z"/></svg>');
  background-size: cover;
}

.page-title h1 {
  font-family: 'Salsa', cursive;
  font-size: 42px;
  margin-bottom: 10px;
  position: relative;
  z-index: 1;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

.page-title p {
  font-size: 20px;
  opacity: 0.95;
  position: relative;
  z-index: 1;
  max-width: 600px;
  margin: 0 auto;
  font-style: italic;
}

/* ===== STATS SECTION ===== */
.stats-section {
  max-width: 1200px;
  margin: 40px auto;
  padding: 0 20px;
}

.stats-title {
  text-align: center;
  font-family: 'Salsa', cursive;
  font-size: 28px;
  color: #ff9f1c;
  margin-bottom: 30px;
  position: relative;
}

.stats-title::after {
  content: '';
  display: block;
  width: 100px;
  height: 3px;
  background: #ff9f1c;
  margin: 10px auto 0;
  border-radius: 2px;
}

.stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
  margin-bottom: 50px;
}

.stat-card {
  background: white;
  border-radius: 15px;
  padding: 30px;
  text-align: center;
  box-shadow: 0 5px 15px rgba(0,0,0,0.08);
  transition: transform 0.3s, box-shadow 0.3s;
  border-top: 5px solid #ff9f1c;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.stat-icon {
  font-size: 40px;
  color: #ff9f1c;
  margin-bottom: 15px;
}

.stat-number {
  font-size: 36px;
  font-weight: bold;
  color: #333;
  margin-bottom: 5px;
}

.stat-label {
  font-size: 18px;
  color: #666;
  font-weight: 500;
}

.experience-badge {
  display: inline-flex;
  align-items: center;
  background: #fff9f0;
  padding: 8px 20px;
  border-radius: 25px;
  margin-top: 10px;
  border: 2px solid #ff9f1c;
}

.experience-badge .fas {
  color: #ff9f1c;
  margin-right: 8px;
}

/* ===== REVIEWS SECTION ===== */
.reviews-section {
  max-width: 1200px;
  margin: 0 auto 60px;
  padding: 0 20px;
}

.section-title {
  font-family: 'Salsa', cursive;
  font-size: 32px;
  color: #ff9f1c;
  text-align: center;
  margin-bottom: 40px;
  position: relative;
}

.section-title::after {
  content: '';
  display: block;
  width: 100px;
  height: 3px;
  background: #ff9f1c;
  margin: 10px auto 0;
  border-radius: 2px;
}

/* ===== ADD REVIEW FORM ===== */
.add-review-form {
  background: white;
  border-radius: 15px;
  padding: 30px;
  margin-bottom: 40px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.form-title {
  font-family: 'Salsa', cursive;
  font-size: 24px;
  color: #ff9f1c;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.form-title i {
  font-size: 28px;
}

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
.form-group textarea {
  width: 100%;
  padding: 12px 15px;
  border: 2px solid #ddd;
  border-radius: 8px;
  font-size: 16px;
  font-family: inherit;
  transition: border-color 0.3s;
}

.form-group input[type="text"]:focus,
.form-group textarea:focus {
  border-color: #ff9f1c;
  outline: none;
}

.form-group textarea {
  min-height: 120px;
  resize: vertical;
}

.rating-container {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 15px;
}

.rating-label {
  font-weight: 500;
  color: #333;
}

.rating-stars {
  display: flex;
  gap: 5px;
  direction: rtl;
}

.rating-stars input {
  display: none;
}

.rating-stars label {
  font-size: 28px;
  color: #ddd;
  cursor: pointer;
  transition: color 0.2s;
}

.rating-stars input:checked ~ label,
.rating-stars label:hover,
.rating-stars label:hover ~ label {
  color: #ff9f1c;
}

.submit-btn {
  background: #ff9f1c;
  color: white;
  border: none;
  padding: 14px 30px;
  border-radius: 8px;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s;
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 10px;
}

.submit-btn:hover {
  background: #e68a00;
}

/* ===== REVIEWS GRID ===== */
.reviews-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 30px;
}

.review-card {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.08);
  transition: transform 0.3s;
  position: relative;
  overflow: hidden;
}

.review-card:hover {
  transform: translateY(-5px);
}

.review-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 5px;
  height: 100%;
  background: #ff9f1c;
}

.review-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.reviewer-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.reviewer-avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: #ff9f1c;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: bold;
  font-size: 20px;
}

.reviewer-name {
  font-size: 18px;
  font-weight: bold;
  color: #333;
}

.review-date {
  font-size: 14px;
  color: #888;
}

.review-rating {
  color: #ff9f1c;
  font-size: 18px;
}

.review-content {
  color: #555;
  line-height: 1.6;
  font-size: 16px;
  font-style: italic;
  position: relative;
  padding-left: 15px;
}

.review-content::before {
  content: '"';
  font-size: 40px;
  color: #ff9f1c;
  position: absolute;
  left: -5px;
  top: -10px;
  opacity: 0.3;
  font-family: Georgia, serif;
}

/* ===== TESTIMONIALS SLIDER ===== */
.testimonials-slider {
  background: linear-gradient(135deg, #fff9f0 0%, #fff3e0 100%);
  border-radius: 15px;
  padding: 40px;
  margin-top: 50px;
  text-align: center;
  border: 2px solid #ff9f1c;
}

.testimonials-title {
  font-family: 'Salsa', cursive;
  font-size: 28px;
  color: #ff9f1c;
  margin-bottom: 30px;
}

.testimonial-slide {
  display: none;
  animation: fadeIn 1s;
}

.testimonial-slide.active {
  display: block;
}

.testimonial-text {
  font-size: 20px;
  color: #333;
  font-style: italic;
  max-width: 800px;
  margin: 0 auto 20px;
  line-height: 1.6;
}

.testimonial-author {
  font-weight: bold;
  color: #ff9f1c;
  font-size: 18px;
}

.slider-controls {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 20px;
}

.slider-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #ddd;
  cursor: pointer;
  transition: background 0.3s;
}

.slider-dot.active {
  background: #ff9f1c;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .stats-container {
    grid-template-columns: 1fr;
  }
  
  .reviews-grid {
    grid-template-columns: 1fr;
  }
  
  .page-title h1 {
    font-size: 32px;
  }
  
  .page-title p {
    font-size: 18px;
  }
  
  .section-title {
    font-size: 26px;
  }
  
  .add-review-form {
    padding: 20px;
  }
  
  .testimonials-slider {
    padding: 25px;
  }
}

@media (max-width: 480px) {
  .review-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
  
  .review-date {
    align-self: flex-end;
  }
  
  .stat-number {
    font-size: 28px;
  }
  
  .stat-label {
    font-size: 16px;
  }
}

/* ===== ANIMATIONS ===== */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.fade-in {
  animation: fadeIn 0.5s ease-in;
}

/* ===== NO REVIEWS MESSAGE ===== */
.no-reviews {
  text-align: center;
  padding: 40px;
  background: white;
  border-radius: 15px;
  margin: 20px 0;
}

.no-reviews i {
  font-size: 60px;
  color: #ddd;
  margin-bottom: 20px;
}

.no-reviews p {
  font-size: 18px;
  color: #888;
}
</style>
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

<?php require_once __DIR__ . '/includes/sidebar.php'; ?>


<div class="overlay" id="overlay" onclick="toggleMenu()"></div>

<section class="page-title">
  <h1>Ulasan Pelanggan</h1>
  <p>Jejak Rasa dari Para Pelanggan~</p>
</section>

<div class="stats-section">
  <h2 class="stats-title">Beberapa review pelanggan setia kami</h2>
  
  <div class="stats-container">
    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-star"></i>
      </div>
      <div class="stat-number">650+</div>
      <div class="stat-label">Review Positif</div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-user-check"></i>
      </div>
      <div class="stat-number">2000+</div>
      <div class="stat-label">Happy Customers</div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-award"></i>
      </div>
      <div class="stat-number">5+</div>
      <div class="stat-label">Years Experience</div>
      <div class="experience-badge">
        <i class="fas fa-medal"></i>
        <span>Terpercaya</span>
      </div>
    </div>
  </div>
</div>

<div class="reviews-section">
  <!-- Form untuk menambah review -->
  <div class="add-review-form">
    <h3 class="form-title">
      <i class="fas fa-pen"></i>
      Bagikan Pengalaman Anda
    </h3>
    
    <form method="POST" action="ulasan.php">
      <div class="form-group">
        <label for="name">Nama Anda</label>
        <input type="text" id="name" name="name" placeholder="Masukkan nama Anda" 
               value="<?php echo isset($_SESSION['order']['nama']) ? htmlspecialchars($_SESSION['order']['nama']) : ''; ?>">
      </div>
      
      <div class="rating-container">
        <div class="rating-label">Rating:</div>
        <div class="rating-stars">
          <input type="radio" id="star5" name="rating" value="5" checked>
          <label for="star5" title="5 stars">★</label>
          <input type="radio" id="star4" name="rating" value="4">
          <label for="star4" title="4 stars">★</label>
          <input type="radio" id="star3" name="rating" value="3">
          <label for="star3" title="3 stars">★</label>
          <input type="radio" id="star2" name="rating" value="2">
          <label for="star2" title="2 stars">★</label>
          <input type="radio" id="star1" name="rating" value="1">
          <label for="star1" title="1 star">★</label>
        </div>
      </div>
      
      <div class="form-group">
        <label for="comment">Ulasan Anda</label>
        <textarea id="comment" name="comment" placeholder="Bagikan pengalaman Anda dengan Astaguna Jawa Catering..." required></textarea>
      </div>
      
      <button type="submit" name="submit_review" class="submit-btn">
        <i class="fas fa-paper-plane"></i>
        Kirim Ulasan
      </button>
    </form>
  </div>
  
  <!-- Daftar Review -->
  <h2 class="section-title">Ulasan Terbaru</h2>
  
  <div class="reviews-grid">
    <?php if (count($reviews) > 0): ?>
      <?php foreach ($reviews as $index => $review): ?>
        <div class="review-card fade-in">
          <div class="review-header">
            <div class="reviewer-info">
              <div class="reviewer-avatar">
                <?php echo strtoupper(substr($review['name'], 0, 1)); ?>
              </div>
              <div>
                <div class="reviewer-name"><?php echo $review['name']; ?></div>
                <div class="review-date"><?php echo $review['date']; ?></div>
              </div>
            </div>
            <div class="review-rating">
              <?php for ($i = 0; $i < 5; $i++): ?>
                <?php if ($i < $review['rating']): ?>
                  <i class="fas fa-star"></i>
                <?php else: ?>
                  <i class="far fa-star"></i>
                <?php endif; ?>
              <?php endfor; ?>
            </div>
          </div>
          <div class="review-content">
            <?php echo $review['comment']; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="no-reviews">
        <i class="far fa-comment-dots"></i>
        <p>Belum ada ulasan. Jadilah yang pertama memberikan ulasan!</p>
      </div>
    <?php endif; ?>
  </div>
  
  <!-- Testimonials Slider -->
  <div class="testimonials-slider">
    <h3 class="testimonials-title">Testimoni Pilihan</h3>
    
    <div class="testimonial-slide active">
      <p class="testimonial-text">"Sangat puas! Makanan lezat, sesuai tema 'Rasa Tradisi', dan pelayanannya profesional. Tidak mengecewakan!"</p>
      <p class="testimonial-author">- Costumer 1</p>
    </div>
    
    <div class="testimonial-slide">
      <p class="testimonial-text">"Catering terbaik yang pernah saya coba. Makanan selalu datang tepat waktu dan rasanya lezat. Astaguna memang top!"</p>
      <p class="testimonial-author">- Costumer 2</p>
    </div>
    
    <div class="testimonial-slide">
      <p class="testimonial-text">"Kualitas makanan bintang lima dengan harga terjangkau. Semua hidangan bersih dan segar. Terima kasih Astaguna!"</p>
      <p class="testimonial-author">- Costumer 3</p>
    </div>
    
    <div class="slider-controls">
      <div class="slider-dot active" onclick="showSlide(0)"></div>
      <div class="slider-dot" onclick="showSlide(1)"></div>
      <div class="slider-dot" onclick="showSlide(2)"></div>
    </div>
  </div>
</div>

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

function toggleMenu() {
  sidebar.classList.toggle('active');
  overlay.classList.toggle('active');
}

// Testimonials Slider
let currentSlide = 0;
const slides = document.querySelectorAll('.testimonial-slide');
const dots = document.querySelectorAll('.slider-dot');

function showSlide(n) {
  // Hide all slides
  slides.forEach(slide => slide.classList.remove('active'));
  dots.forEach(dot => dot.classList.remove('active'));
  
  // Show selected slide
  currentSlide = n;
  slides[currentSlide].classList.add('active');
  dots[currentSlide].classList.add('active');
}

// Auto-advance slides
function autoAdvanceSlide() {
  currentSlide = (currentSlide + 1) % slides.length;
  showSlide(currentSlide);
}

// Start auto-slide
let slideInterval = setInterval(autoAdvanceSlide, 5000);

// Pause auto-slide on hover
const slider = document.querySelector('.testimonials-slider');
slider.addEventListener('mouseenter', () => clearInterval(slideInterval));
slider.addEventListener('mouseleave', () => {
  slideInterval = setInterval(autoAdvanceSlide, 5000);
});

// Star rating interaction
const stars = document.querySelectorAll('.rating-stars label');
stars.forEach(star => {
  star.addEventListener('click', function() {
    const rating = this.previousElementSibling.value;
    console.log('Rating selected:', rating);
  });
});

// Form submission feedback
const reviewForm = document.querySelector('form');
if (reviewForm) {
  reviewForm.addEventListener('submit', function(e) {
    const comment = document.getElementById('comment').value.trim();
    if (!comment) {
      e.preventDefault();
      alert('Silahkan tulis ulasan Anda sebelum mengirim.');
      return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('.submit-btn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
    submitBtn.disabled = true;
  });
}

// Smooth scroll to form after submission
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])): ?>
window.onload = function() {
  document.querySelector('.add-review-form').scrollIntoView({ 
    behavior: 'smooth' 
  });
  
  // Show success message
  setTimeout(() => {
    alert('Terima kasih! Ulasan Anda telah berhasil dikirim.');
  }, 500);
};
<?php endif; ?>

// Initialize tooltips for stars
stars.forEach(star => {
  star.addEventListener('mouseover', function() {
    const title = this.getAttribute('title');
    // You could add a custom tooltip here if needed
  });
});
</script>

</body>
</html>