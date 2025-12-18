// js/cart.js

// Fungsi untuk update quantity item di keranjang
function updateQuantity(cartId, change) {
    const itemElement = document.getElementById(`cart-item-${cartId}`);
    const quantityDisplay = itemElement.querySelector('.quantity-display');
    const currentQty = parseInt(quantityDisplay.textContent);
    
    // Validasi jika mengurangi dan quantity sudah 1
    if (change === -1 && currentQty <= 1) {
        removeFromCart(cartId);
        return;
    }
    
    const formData = new FormData();
    formData.append('cart_id', cartId);
    formData.append('change', change);
    
    fetch('update_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update tampilan sementara
            quantityDisplay.textContent = currentQty + change;
            // Reload untuk update total harga
            setTimeout(() => {
                window.location.reload();
            }, 300);
        } else {
            alert('Gagal mengupdate kuantitas: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate kuantitas');
    });
}

// Fungsi untuk menghapus item dari keranjang
function removeFromCart(cartId) {
    if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
        const formData = new FormData();
        formData.append('cart_id', cartId);
        
        fetch('remove_from_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Hapus item dari tampilan
                const itemElement = document.getElementById(`cart-item-${cartId}`);
                if (itemElement) {
                    itemElement.style.display = 'none';
                }
                // Reload halaman setelah 300ms
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            } else {
                alert('Gagal menghapus item: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus item');
        });
    }
}

// Fungsi untuk checkout
function checkout() {
    window.location.href = 'pesan.php';
}

// Fungsi untuk kembali ke menu
function backToMenu() {
    window.location.href = 'menu.php';
}

// Keyboard shortcuts
function setupKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Ctrl + R untuk refresh
        if (e.ctrlKey && e.key === 'r') {
            e.preventDefault();
            location.reload();
        }
        // Escape untuk kembali ke menu
        if (e.key === 'Escape') {
            window.location.href = 'menu.php';
        }
        // F1 untuk bantuan
        if (e.key === 'F1') {
            e.preventDefault();
            alert('Shortcut Keyboard:\n- Ctrl+R: Refresh halaman\n- Escape: Kembali ke menu\n- F1: Tampilkan bantuan ini');
        }
    });
}

// Initialize functions when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Setup keyboard shortcuts
    setupKeyboardShortcuts();
    
    // Add click event to back button
    const backBtn = document.querySelector('.back-btn');
    if (backBtn) {
        backBtn.addEventListener('click', backToMenu);
    }
    
    // Add click event to back link
    const backLink = document.querySelector('.back-link');
    if (backLink) {
        backLink.addEventListener('click', function(e) {
            e.preventDefault();
            backToMenu();
        });
    }
    
    // Add confirmation to checkout button
    const checkoutBtn = document.querySelector('.checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function(e) {
            if (!confirm('Lanjutkan ke pembayaran?')) {
                e.preventDefault();
            }
        });
    }
    
    // Validate quantities on page load
    validateQuantities();
});

// Fungsi untuk validasi quantity
function validateQuantities() {
    const quantityDisplays = document.querySelectorAll('.quantity-display');
    quantityDisplays.forEach(display => {
        const qty = parseInt(display.textContent);
        if (isNaN(qty) || qty < 1) {
            display.textContent = '1';
        }
    });
}

// Fungsi untuk menghitung total secara real-time (jika diperlukan)
function calculateItemTotal(price, quantity) {
    const priceNum = parseFloat(price.replace(/[^\d]/g, ''));
    return priceNum * quantity;
}

// Export functions for testing (jika menggunakan module)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        updateQuantity,
        removeFromCart,
        checkout,
        backToMenu,
        validateQuantities,
        calculateItemTotal
    };
}