// js/cs.js

const chatContent = document.getElementById('chatContent');
const chatInput = document.getElementById('chatInput');
const chatBox = document.getElementById('chatBox');
const callPopup = document.getElementById('callPopup');

// Flag untuk mencegah multiple clicks
let isProcessing = false;

// Fungsi untuk membuka popup call
function openCallPopup() {
  callPopup.classList.add('active');
}

// Fungsi untuk menutup popup call
function closeCallPopup() {
  callPopup.classList.remove('active');
}

// Tutup popup saat klik di luar konten popup
callPopup.addEventListener('click', function(event) {
  if (event.target === callPopup) {
    closeCallPopup();
  }
});

// Fungsi untuk mendapatkan waktu sekarang dalam format HH:MM
function getCurrentTime() {
  const now = new Date();
  return `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;
}

// Database respon otomatis
const autoReplies = {
  'Kapan pesanan saya diantar?': 'Pesanan Anda biasanya diantar dalam 1-2 jam kerja setelah konfirmasi pembayaran. Untuk informasi lebih detail, silakan cek di halaman "Lacak Pesanan" dengan nomor order Anda.',
  'Bagaimana cara melakukan pesanan?': 'Cara melakukan pesanan:<P>1) Pilih menu yang diinginkan.<p>2) Tambahkan ke keranjang.<p>3) Pilih metode pembayaran.<p>4) Konfirmasi pesanan.<p>Jika ada kendala, hubungi kami.',
  'Bagaimana cara cek apakah pesanan telah diantar?': 'Anda bisa cek status pengantaran di menu "Riwayat Pesanan" atau melalui link tracking yang dikirim ke email/WhatsApp Anda. Nomor resi akan dikirim setelah pesanan dikirim.',
  'Tanya Pesanan': 'Untuk pertanyaan tentang pesanan, silakan sertakan:<p>1) Nomor order.2) Tanggal pemesanan.<p>3) Nama pemesan.<p>Kami akan cek status pesanan Anda.',
  'Menu Error': 'Mohon maaf atas kendala menu. Coba:<p>1) Refresh halaman.<p>2) Clear cache browser.<p>3) Gunakan browser lain.<p>Jika masih error, screenshot dan kirim ke kami.',
  'Pembayaran Error': 'Jika pembayaran error:<p>1) Pastikan saldo/cukup.<p>2) Cek metode pembayaran yang dipilih.<p>3) Tunggu 5-10 menit.<p>Jika masih gagal, hubungi bank/OVO/GoPay/Dana Anda.',
  'Kirim Form Gagal': 'Untuk form yang gagal dikirim:<p>1) Cek koneksi internet.<p>2) Pastikan semua field terisi.<p>3) File upload tidak melebihi 5MB.<p>4) Coba submit ulang.<p>Jika masih gagal, screenshot error dan hubungi kami.',
  'default': 'Terima kasih telah menghubungi Customer Service. Tim kami akan segera merespon pertanyaan Anda. Untuk pertanyaan lebih lanjut, silakan kirim pesan.'
};

// Fungsi untuk menambahkan pesan ke chat
function addMessage(text, isUser = false) {
  const messageDiv = document.createElement('div');
  messageDiv.className = `message ${isUser ? 'user' : 'admin'}`;
  messageDiv.innerHTML = `
    <div>${text}</div>
    <small style="color:#888; font-size:12px; margin-top:5px; display:block;">${getCurrentTime()}</small>
  `;
  
  chatContent.appendChild(messageDiv);
  
  // Scroll ke bawah
  chatBox.scrollTop = chatBox.scrollHeight;
}

// Fungsi untuk mendapatkan balasan otomatis
function getAutoReply(message) {
  // Cari balasan yang tepat
  for (const key in autoReplies) {
    if (key !== 'default' && message.toLowerCase().includes(key.toLowerCase())) {
      return autoReplies[key];
    }
  }
  return autoReplies['default'];
}

// Fungsi untuk menangani klik pada pesan contoh
function handleExampleMessage(messageText) {
  if (isProcessing) return;
  isProcessing = true;
  
  // Kirim pesan user (dari pelanggan)
  addMessage(messageText, true);
  
  // Dapatkan balasan otomatis
  const reply = getAutoReply(messageText);
  
  // Beri respon otomatis setelah delay
  setTimeout(() => {
    addMessage(reply, false);
    isProcessing = false;
  }, 800);
}

// Fungsi untuk menangani quick reply
function handleQuickReply(buttonText) {
  if (isProcessing) return;
  isProcessing = true;
  
  // Kirim pesan user
  addMessage(buttonText, true);
  
  // Dapatkan balasan otomatis
  const reply = getAutoReply(buttonText);
  
  // Beri respon otomatis setelah delay
  setTimeout(() => {
    addMessage(reply, false);
    isProcessing = false;
  }, 800);
}

// Fungsi untuk mengirim pesan manual dari input
function sendMessage() {
  if (isProcessing) return;
  
  const message = chatInput.value.trim();
  if (message) {
    isProcessing = true;
    
    // Kirim pesan user
    addMessage(message, true);
    
    // Dapatkan balasan otomatis
    const reply = getAutoReply(message);
    
    // Beri respon otomatis setelah delay
    setTimeout(() => {
      addMessage(reply, false);
      isProcessing = false;
    }, 1000);
    
    // Kosongkan input
    chatInput.value = '';
  }
}

// Fungsi untuk handle tekan Enter
function handleKeyPress(event) {
  if (event.key === 'Enter' && !isProcessing) {
    sendMessage();
  }
}

// Event listener untuk pesan contoh (ditambahkan setelah DOM siap)
document.addEventListener('DOMContentLoaded', function() {
  // Tambahkan event listener ke pesan contoh
  const exampleMessages = document.querySelectorAll('#chatContent .message');
  exampleMessages.forEach(message => {
    message.addEventListener('click', function() {
      const text = this.textContent.trim();
      handleExampleMessage(text);
    });
  });
});