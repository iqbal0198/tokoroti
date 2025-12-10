<?php
$hostname = "localhost";
$username = "root";
$password = "";       // Default XAMPP biasanya kosong
$database = "bakery_db"; // Sesuai nama DB yang tadi lo buat

// Bikin koneksi
$conn = mysqli_connect($hostname, $username, $password, $database);

// Cek koneksi, kalo gagal matiin aja
if (!$conn) {
    die("Koneksi Database Gagal Bre: " . mysqli_connect_error());
}

// Opsional: Biar aman kalo ada error
// echo "Koneksi Aman Jaya!"; 
?>