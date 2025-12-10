<?php
session_start();
// Cek: Kalo belum login ATAU bukan admin, tendang keluar!
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bakery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-100">

<nav class="bg-gray-900 text-white shadow-lg">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="index.php" class="text-xl font-bold text-orange-400">Admin Panel 🍞</a>
        <div class="space-x-4">
            <a href="index.php" class="hover:text-orange-400">Dashboard Produk</a>
            <a href="orders.php" class="hover:text-orange-400">Pesanan Masuk</a>
            <a href="../logout.php" class="bg-red-600 px-3 py-1 rounded hover:bg-red-700 text-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mx-auto px-6 py-8">