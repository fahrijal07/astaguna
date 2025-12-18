<?php
include 'config.php';

$nama = $_POST['nama'];
$rating = $_POST['rating'];
$text = $_POST['text'];

// simpan ke database
$sql = "INSERT INTO ulasan (nama, rating, text) VALUES ('$nama', '$rating', '$text')";
$query = mysqli_query($koneksi, $sql);

// balik ke halaman review
if ($query) {
    header("Location: ulasan.php");
    exit();
} else {
    echo "Gagal menambahkan ulasan!";
}
?>
