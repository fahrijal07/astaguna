<?php
require_once __DIR__ . '/config/session_helper.php';
require_once __DIR__ . '/models/Menu.php';
require_once __DIR__ . '/models/Cart.php';

$menu = new Menu();
$cart = new Cart();

// database
$paketMenus = $menu->getPaketMenus();
$laukItems = $menu->getMenuItems('lauk');
$sayurItems = $menu->getMenuItems('sayur');
$nasiItems = $menu->getMenuItems('nasi');
$pendampingItems = $menu->getMenuItems('pendamping');

$cartCount = getCartCount();

// ========== GAMBAR UNTUK SEMUA MENU ==========
$paketImages = [
    1 => 'paket-1.jpg',
    2 => 'paket-2.jpg',
    3 => 'paket-3.jpg',
    4 => 'paket-4.jpg',
    5 => 'paket-5.jpg',
];

$laukImages = [
    1 => 'ayamjawa.jpg',
    2 => 'katsu.jpg',
    3 => 'bakar.jpg', 
    4 => 'ayam-goreng.jpg',
    5 => 'lele.jpg',
    6 => 'perkedel.jpg',
    7 => 'Ayam Lodho.jpg',
    8 => 'balado.jpg'
    
];

$sayurImages = [
    9 => 'sop.jpg',
    10 => 'sayur asem.jpg',
    11 => 'urap.jpg',
    12 => 'sambal pecel.jpg',
    13 => 'lalapan.jpg',
    
];

$pendampingImages = [
    
    14 => 'krupuk.jpg',
    15 => 'bacem.jpg',
    16 => 'tempegoreng.jpg',
];
$nasiImages = [
  17 => 'nasi.jpeg',
  18 => 'kuning.jpeg',
  19 => 'uduk.jpeg',
];

function getImagePath($itemId, $itemType) {
    global $paketImages, $laukImages, $sayurImages, $nasiImages, $pendampingImages;
    
    $defaultImage = 'img/default-food.jpg';
    $imageFile = '';
    $folder = '';
    
    switch($itemType) {
        case 'paket':
            $imageFile = isset($paketImages[$itemId]) ? $paketImages[$itemId] : '';
            $folder = 'img/paket/';
            break;
        case 'lauk':
            $imageFile = isset($laukImages[$itemId]) ? $laukImages[$itemId] : '';
            $folder = 'img/lauk/';
            break;
            case 'sayur':
              $imageFile = isset($sayurImages[$itemId]) ? $sayurImages[$itemId] : '';
              $folder = 'img/sayur/';
              break;
        case 'nasi':
            $imageFile = isset($nasiImages[$itemId]) ? $nasiImages[$itemId] : '';
            $folder = 'img/nasi/';
            break;
        case 'pendamping':
            $imageFile = isset($pendampingImages[$itemId]) ? $pendampingImages[$itemId] : '';
            $folder = 'img/pendamping/';
            break;
        default:
            $folder = 'img/items/';
    }
    
    if (!empty($imageFile)) {
        $fullPath = $folder . $imageFile;
        // Cek apakah file gambar ada
        if (file_exists(__DIR__ . '/' . $fullPath)) {
            return $fullPath;
        }
    }
    
    return $defaultImage;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menu - Astaguna Jawa Catering</title>

<link href="https://fonts.googleapis.com/css2?family=Salsa&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/menu.css">
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
    <a href="cart.php" style="position: relative;">
      <img src="asset/cart.png" class="icon">
      <?php if ($cartCount > 0): ?>
      <span class="cart-count"><?php echo $cartCount; ?></span>
      <?php endif; ?>
    </a>
  </div>
</header>

<?php require_once __DIR__ . '/includes/sidebar.php'; ?>

<div class="overlay" id="overlay" onclick="toggleMenu()"></div>

<section class="page-title">
  <h1>Menu Kami</h1>
  <p>Pilih menu favorit Anda</p>
  <div class="bulk-order-bar">
    <form id="bulkForm" method="POST" action="pesan.php">
      <input type="hidden" name="bulk_add" value="1">
      <input type="hidden" name="items_json" id="items_json">
      <button type="button" class="pesan-btn bulk-btn" onclick="bulkToPesan()">Lanjut ke Pemesanan (Pilih)</button>
      <div class="bulk-info">Fitur ini hanya berlaku untuk Menu Custom.</div>
    </form>
  </div>
</section>

<section class="menu-selection">
  <div class="menu-option active" onclick="showMenu('paket')">
    <h2>Menu Paket</h2>
    <p>Menu paket khas Jawa rumahan & acara hajatan</p>
  </div>
  
  <div class="menu-option" onclick="showMenu('custom')">
    <h2>Menu Custom</h2>
    <p>Bisa dicampur sesuai selera & kebutuhan acara</p>
  </div>
</section>

<!-- Menu Paket -->
<section class="menu-content active" id="paket-content">
  <div class="menu-grid">
    <?php foreach ($paketMenus as $paket): ?>
    <?php 
    $paketItems = $menu->getPaketItems($paket['id']);
    $imagePath = getImagePath($paket['id'], 'paket');
    ?>
    <div class="menu-item" id="paket-<?php echo $paket['id']; ?>">
      
      <!-- GAMBAR PAKET -->
      <div class="menu-image-container">
        <img src="<?php echo $imagePath; ?>" 
             alt="<?php echo htmlspecialchars($paket['name']); ?>"
             class="menu-image"
             onerror="this.src='img/default-food.jpg'">
      </div>
      
      <div class="menu-item-header">
        <div class="menu-item-name"><?php echo htmlspecialchars($paket['name']); ?></div>
        <div class="menu-item-price">
          <?php if ($paket['price'] && $paket['price'] > 0): ?>
            Rp <?php echo number_format($paket['price'], 0, ',', '.'); ?>
          <?php else: ?>
            Hubungi Kami
          <?php endif; ?>
        </div>
      </div>
      
      <div class="paket-rating">
        ⭐ <?php echo number_format($paket['rating'], 1); ?>/5 (<?php echo $paket['review_count']; ?> Ulasan)
      </div>
      
      <div class="paket-description">
        <?php if (!empty($paketItems)): ?>
          <?php foreach ($paketItems as $item): ?>
          - <?php echo htmlspecialchars($item['name']); ?><br>
          <?php endforeach; ?>
        <?php else: ?>
          <?php echo htmlspecialchars($paket['description']); ?>
        <?php endif; ?>
      </div>
      
      <div class="button-group">
        <a class="pesan-btn" 
           href="pesan.php?add=1&item_type=paket&item_id=<?php echo $paket['id']; ?>">
          Pesan Sekarang
        </a>
        <button class="pesan-btn cart-btn" 
                onclick="addToCart(<?php echo $paket['id']; ?>, 'paket', '<?php echo htmlspecialchars($paket['name'], ENT_QUOTES); ?>', <?php echo isset($paket['price']) ? $paket['price'] : 0; ?>)">
          Masukkan ke Keranjang
        </button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Menu Custom -->
<section class="menu-content" id="custom-content">
  <div class="menu-section">
    <h2>- Lauk Utama -</h2>
    <div class="menu-grid">
      <?php foreach ($laukItems as $item): ?>
      <?php $imagePath = getImagePath($item['id'], 'lauk'); ?>
      <div class="menu-item">
        
        <!-- GAMBAR LAUK -->
        <div class="menu-image-container">
          <img src="<?php echo $imagePath; ?>" 
               alt="<?php echo htmlspecialchars($item['name']); ?>"
               class="menu-image"
               onerror="this.src='img/default-food.jpg'">
        </div>
        
        <div class="menu-item-header">
          <div class="menu-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
          <div class="menu-item-price">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></div>
        </div>
        
        <?php if ($item['description']): ?>
        <div class="menu-item-note"><?php echo htmlspecialchars($item['description']); ?></div>
        <?php endif; ?>
        
        <div class="bulk-selection">
          <label class="bulk-label">
            <input type="checkbox" class="bulk-check" data-type="item" data-id="<?php echo $item['id']; ?>">
            Pilih
          </label>
          <input type="number" class="bulk-qty" data-id="<?php echo $item['id']; ?>" min="1" value="1">
        </div>
        
        <div class="button-group">
          <a class="pesan-btn" 
             href="pesan.php?add=1&item_type=item&item_id=<?php echo $item['id']; ?>">
            Pesan Sekarang
          </a>
          <button class="pesan-btn cart-btn" 
                  onclick="addToCart(<?php echo $item['id']; ?>, 'item', '<?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>', <?php echo $item['price']; ?>)">
            Masukkan ke Keranjang
          </button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  
  <div class="menu-section">
    <h2>- Sayur & Pendamping -</h2>
    <div class="menu-grid">
      <?php foreach ($sayurItems as $item): ?>
      <?php $imagePath = getImagePath($item['id'], 'sayur'); ?>
      <div class="menu-item">
        
        <!-- GAMBAR SAYUR -->
        <div class="menu-image-container">
          <img src="<?php echo $imagePath; ?>" 
               alt="<?php echo htmlspecialchars($item['name']); ?>"
               class="menu-image"
               onerror="this.src='img/default-food.jpg'">
        </div>
        
        <div class="menu-item-header">
          <div class="menu-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
          <div class="menu-item-price">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></div>
        </div>
        
        <?php if ($item['description']): ?>
        <div class="menu-item-note"><?php echo htmlspecialchars($item['description']); ?></div>
        <?php endif; ?>
        
        <div class="bulk-selection">
          <label class="bulk-label">
            <input type="checkbox" class="bulk-check" data-type="item" data-id="<?php echo $item['id']; ?>">
            Pilih
          </label>
          <input type="number" class="bulk-qty" data-id="<?php echo $item['id']; ?>" min="1" value="1">
        </div>
        
        <div class="button-group">
          <a class="pesan-btn" 
             href="pesan.php?add=1&item_type=item&item_id=<?php echo $item['id']; ?>">
            Pesan Sekarang
          </a>
          <button class="pesan-btn cart-btn" 
                  onclick="addToCart(<?php echo $item['id']; ?>, 'item', '<?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>', <?php echo $item['price']; ?>)">
            Masukkan ke Keranjang
          </button>
        </div>
      </div>
      <?php endforeach; ?>
      
      <?php foreach ($pendampingItems as $item): ?>
      <?php $imagePath = getImagePath($item['id'], 'pendamping'); ?>
      <div class="menu-item">
        
        <!-- GAMBAR PENDAMPING -->
        <div class="menu-image-container">
          <img src="<?php echo $imagePath; ?>" 
               alt="<?php echo htmlspecialchars($item['name']); ?>"
               class="menu-image"
               onerror="this.src='img/default-food.jpg'">
        </div>
        
        <div class="menu-item-header">
          <div class="menu-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
          <div class="menu-item-price">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></div>
        </div>
        
        <?php if ($item['description']): ?>
        <div class="menu-item-note"><?php echo htmlspecialchars($item['description']); ?></div>
        <?php endif; ?>
        
        <div class="bulk-selection">
          <label class="bulk-label">
            <input type="checkbox" class="bulk-check" data-type="item" data-id="<?php echo $item['id']; ?>">
            Pilih
          </label>
          <input type="number" class="bulk-qty" data-id="<?php echo $item['id']; ?>" min="1" value="1">
        </div>
        
        <div class="button-group">
          <a class="pesan-btn" 
             href="pesan.php?add=1&item_type=item&item_id=<?php echo $item['id']; ?>">
            Pesan Sekarang
          </a>
          <button class="pesan-btn cart-btn" 
                  onclick="addToCart(<?php echo $item['id']; ?>, 'item', '<?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>', <?php echo $item['price']; ?>)">
            Masukkan ke Keranjang
          </button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  
  <div class="menu-section">
    <h2>- Nasi -</h2>
    <div class="menu-grid">
      <?php foreach ($nasiItems as $item): ?>
      <?php $imagePath = getImagePath($item['id'], 'nasi'); ?>
      <div class="menu-item">
        
        <!-- GAMBAR NASI -->
        <div class="menu-image-container">
          <img src="<?php echo $imagePath; ?>" 
               alt="<?php echo htmlspecialchars($item['name']); ?>"
               class="menu-image"
               onerror="this.src='img/default-food.jpg'">
        </div>
        
        <div class="menu-item-header">
          <div class="menu-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
          <div class="menu-item-price">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></div>
        </div>
        
        <?php if ($item['description']): ?>
        <div class="menu-item-note"><?php echo htmlspecialchars($item['description']); ?></div>
        <?php endif; ?>
        
        <div class="bulk-selection">
          <label class="bulk-label">
            <input type="checkbox" class="bulk-check" data-type="item" data-id="<?php echo $item['id']; ?>">
            Pilih
          </label>
          <input type="number" class="bulk-qty" data-id="<?php echo $item['id']; ?>" min="1" value="1">
        </div>
        
        <div class="button-group">
          <a class="pesan-btn" 
             href="pesan.php?add=1&item_type=item&item_id=<?php echo $item['id']; ?>">
            Pesan Sekarang
          </a>
          <button class="pesan-btn cart-btn" 
                  onclick="addToCart(<?php echo $item['id']; ?>, 'item', '<?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>', <?php echo $item['price']; ?>)">
            Masukkan ke Keranjang
          </button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<script src="js/menu.js"></script>
</body>
</html>