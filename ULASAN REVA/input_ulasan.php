<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tambah Ulasan</title>

<style>
body{
    font-family: Arial;
    background: #fff7e6;
    padding: 20px;
}

.form-box{
    background: #ffffff;
    padding: 20px;
    border-radius: 12px;
    width: 350px;
    margin: auto;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

label{
    font-weight: bold;
    margin-top: 10px;
    display: block;
}

input, textarea{
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

button{
    margin-top: 15px;
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 10px;
    background: #e28b00;
    color: white;
    font-weight: bold;
    cursor: pointer;
}
</style>
</head>

<body>

<div class="form-box">
    <h2 style="text-align:center;">Tambah Ulasan</h2>

    <form action="proses_ulasan.php" method="POST" enctype="multipart/form-data">

        <label>Nama</label>
        <input type="text" name="nama" required>

        <label>Rating (1–5)</label>
        <input type="number" name="rating" min="1" max="5" required>

        <label>Ulasan</label>
        <textarea name="text" rows="5" required></textarea>

        
        <button type="submit">Kirim Ulasan</button>
    </form>
</div>

</body>
</html>
