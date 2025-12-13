const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

function toggleMenu(){
  if (sidebar && overlay) {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
  }
}

function showMenu(menuType) {
  // Update active button
  const options = document.querySelectorAll('.menu-option');
  options.forEach(opt => opt.classList.remove('active'));
  
  if (menuType === 'paket') {
    document.querySelector('.menu-option:nth-child(1)').classList.add('active');
    document.getElementById('paket-content').classList.add('active');
    document.getElementById('custom-content').classList.remove('active');
  } else {
    document.querySelector('.menu-option:nth-child(2)').classList.add('active');
    document.getElementById('paket-content').classList.remove('active');
    document.getElementById('custom-content').classList.add('active');
  }
}

function bulkToPesan(){
  const checks = document.querySelectorAll('.bulk-check:checked');
  const form = document.getElementById('bulkForm');
  if (!form) return;
  
  // bersihkan input items sebelumnya
  form.querySelectorAll('input[name^="items["]').forEach(e=>e.remove());
  let i=0;
  checks.forEach(ch=>{
    const id = ch.getAttribute('data-id');
    const type = ch.getAttribute('data-type');
    const qtyEl = document.querySelector('.bulk-qty[data-id="'+id+'"]');
    const qty = qtyEl ? parseInt(qtyEl.value||'1',10) : 1;
    if(qty>0){
      const in1=document.createElement('input'); in1.type='hidden'; in1.name=`items[${i}][id]`; in1.value=id;
      const in2=document.createElement('input'); in2.type='hidden'; in2.name=`items[${i}][type]`; in2.value=type;
      const in3=document.createElement('input'); in3.type='hidden'; in3.name=`items[${i}][qty]`; in3.value=qty;
      form.appendChild(in1); form.appendChild(in2); form.appendChild(in3);
      i++;
    }
  });
  if(i===0){
    alert('Pilih minimal 1 item dulu.');
    return;
  }
  form.submit();
}

function addToCart(itemId, itemType, itemName, price) {
  console.log('addToCart dipanggil:', {itemId, itemType, itemName, price});
  
  // Jika price adalah string "0" atau 0
  if (price === 0 || price === "0") {
    alert('Untuk harga paket ini, silahkan hubungi kami melalui WhatsApp atau telepon.');
    return;
  }
  
  const formData = new FormData();
  formData.append('item_id', itemId);
  formData.append('item_type', itemType);
  
  console.log('Mengirim data ke add_to_cart.php:', {itemId, itemType});
  
  fetch('add_to_cart.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    console.log('Response status:', response.status);
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(data => {
    console.log('Data response:', data);
    if (data.success) {
      alert(`"${itemName}" telah ditambahkan ke keranjang!\nHarga: Rp ${parseInt(price).toLocaleString()}`);
      // Update cart count
      updateCartCount(data.cart_count);
    } else {
      alert('Gagal menambahkan ke keranjang: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat menambahkan ke keranjang. Periksa console untuk detail.');
  });
}

function updateCartCount(count) {
  const cartCountElement = document.querySelector('.cart-count');
  if (cartCountElement) {
    cartCountElement.textContent = count;
  } else if (count > 0) {
    // Create cart count if it doesn't exist
    const cartLink = document.querySelector('a[href="cart.php"]');
    if (cartLink) {
      const newCartCount = document.createElement('span');
      newCartCount.className = 'cart-count';
      newCartCount.textContent = count;
      cartLink.appendChild(newCartCount);
    }
  }
}

// Initialize with paket menu active
document.addEventListener('DOMContentLoaded', function() {
  console.log('DOM loaded');
  showMenu('paket');
  
  // Check if there's a hash in URL to scroll to specific paket
  const hash = window.location.hash;
  if (hash) {
    const element = document.querySelector(hash);
    if (element) {
      setTimeout(() => {
        element.scrollIntoView({ behavior: 'smooth' });
      }, 300);
    }
  }
  
  // Test: Tambahkan event listener untuk debug
  const cartButtons = document.querySelectorAll('.cart-btn');
  console.log('Jumlah tombol cart:', cartButtons.length);
  
  cartButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      console.log('Tombol cart diklik:', this);
    });
  });
});

// Close sidebar when clicking outside (on overlay)
if (overlay) {
  overlay.addEventListener('click', toggleMenu);
}

// Close sidebar with Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape' && sidebar && overlay) {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
  }
});