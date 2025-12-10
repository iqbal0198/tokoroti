<?php
session_start();
include '../config/koneksi.php';

// 1. Cek Login Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// 2. Logic Hapus Produk
if (isset($_GET['delete'])) {
    $id_hapus = $_GET['delete'];
    
    // Ambil info gambar dulu
    $q_cek = mysqli_query($conn, "SELECT image FROM products WHERE id='$id_hapus'");
    $d_cek = mysqli_fetch_array($q_cek);
    
    // Hapus file gambar fisik (kecuali dummy)
    if ($d_cek['image'] != 'dummy.jpg' && file_exists("../assets/img/" . $d_cek['image'])) {
        unlink("../assets/img/" . $d_cek['image']);
    }

    // Hapus dari database
    mysqli_query($conn, "DELETE FROM products WHERE id='$id_hapus'");
    
    echo "<script>alert('Produk berhasil dihapus!'); window.location='products.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - BakeryPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }
        .table-row-hover:hover td { background-color: #FFF7ED; }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-gray-800">

    <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 hidden md:flex flex-col">
        <div class="p-6 flex items-center gap-3">
            <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center font-bold text-white shadow-lg">B</div>
            <span class="text-xl font-bold tracking-tight text-gray-900">BakeryPro.</span>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
            <p class="text-xs text-gray-400 font-bold uppercase px-2 mb-2 tracking-wider">Menu Utama</p>
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium">
                <i class="fas fa-home w-5"></i> Dashboard
            </a>
            <a href="products.php" class="flex items-center gap-3 px-4 py-3 bg-orange-50 text-orange-700 rounded-xl font-bold transition">
                <i class="fas fa-box-open w-5"></i> Kelola Produk
            </a>
            <a href="product_add.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium">
                <i class="fas fa-plus-circle w-5"></i> Tambah Baru
            </a>
            <a href="orders.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium">
                <i class="fas fa-shopping-bag w-5"></i> Pesanan
            </a>
            
        </nav>

        <div class="p-4 border-t border-gray-100">
            <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition font-bold">
                <i class="fas fa-sign-out-alt w-5"></i> Keluar
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        
        <header class="bg-white/80 backdrop-blur-md border-b border-gray-200 h-20 flex items-center justify-between px-8 sticky top-0 z-30">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Daftar Menu Roti</h2>
                <p class="text-xs text-gray-500">Total Produk: 
                    <?php 
                        $count = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"));
                        echo $count['total'];
                    ?> Item
                </p>
            </div>
            <a href="product_add.php" class="bg-gray-900 text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-orange-600 transition flex items-center gap-2 shadow-lg">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </header>

        <div class="p-8">
            
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase border-b border-gray-200">
                                <th class="px-6 py-4 font-bold">Foto & Nama</th>
                                <th class="px-6 py-4 font-bold text-center">Kategori</th>
                                <th class="px-6 py-4 font-bold text-center">Harga</th>
                                <th class="px-6 py-4 font-bold text-center">Stok</th>
                                <th class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php
                            $query = mysqli_query($conn, "SELECT products.*, categories.name as cat_name 
                                                          FROM products 
                                                          LEFT JOIN categories ON products.category_id = categories.id 
                                                          ORDER BY products.id DESC");
                            
                            if(mysqli_num_rows($query) > 0):
                                while($row = mysqli_fetch_array($query)):
                                    // Stok Warning
                                    $stok_class = "bg-green-100 text-green-700";
                                    $stok_text = "Aman";
                                    if($row['stock'] < 5) {
                                        $stok_class = "bg-red-100 text-red-700 animate-pulse";
                                        $stok_text = "Menipis!";
                                    } elseif($row['stock'] == 0) {
                                        $stok_class = "bg-gray-200 text-gray-500";
                                        $stok_text = "Habis";
                                    }
                            ?>
                            <tr class="table-row-hover transition duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200 flex-shrink-0">
                                            <img src="../assets/img/<?= $row['image'] ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800 text-sm"><?= $row['name'] ?></p>
                                            <p class="text-xs text-gray-400 truncate max-w-[150px]"><?= $row['description'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-600 border border-orange-100">
                                        <?= $row['cat_name'] ?? 'Umum' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-gray-700">
                                    Rp <?= number_format($row['price']) ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-sm font-bold"><?= $row['stock'] ?></span>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold <?= $stok_class ?>">
                                            <?= $stok_text ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        
                                        <a href="product_edit.php?id=<?= $row['id'] ?>" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition shadow-sm border border-blue-100" title="Edit Produk">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        
                                        <a href="products.php?delete=<?= $row['id'] ?>" onclick="return confirm('Yakin mau hapus <?= $row['name'] ?>?')" 
                                           class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition shadow-sm border border-red-100" title="Hapus Produk">
                                            <i class="fas fa-trash text-xs"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                    <i class="fas fa-box-open text-4xl mb-3"></i><br>
                                    Belum ada produk roti nih. <a href="product_add.php" class="text-orange-600 font-bold hover:underline">Tambah sekarang?</a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

</body>
</html>