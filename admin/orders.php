<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Logic Hitung Badge Sidebar
$total_pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status='pending'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pesanan - BakeryPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }</style>
</head>
<body class="flex h-screen overflow-hidden text-gray-800">

    <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 hidden md:flex flex-col">
        <div class="p-6 flex items-center gap-3">
            <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center font-bold text-white shadow-lg">B</div>
            <span class="text-xl font-bold tracking-tight text-gray-900">BakeryPro.</span>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <p class="text-xs text-gray-400 font-bold uppercase px-2 mb-2">Menu Utama</p>
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium"><i class="fas fa-home w-5"></i> Dashboard</a>
            <a href="products.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium"><i class="fas fa-box-open w-5"></i> Kelola Produk</a>
            <a href="product_add.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium"><i class="fas fa-plus-circle w-5"></i> Tambah Baru</a>
            
            <a href="orders.php" class="flex items-center gap-3 px-4 py-3 bg-orange-50 text-orange-700 rounded-xl font-bold transition relative">
                <i class="fas fa-shopping-bag w-5"></i> Pesanan
                <?php if($total_pending > 0): ?>
                    <span class="absolute right-4 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full"><?= $total_pending ?></span>
                <?php endif; ?>
            </a>
            <a href="users.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium"><i class="fas fa-users w-5"></i> Pelanggan</a>
        </nav>
        <div class="p-4 border-t border-gray-100">
            <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition font-bold"><i class="fas fa-sign-out-alt w-5"></i> Keluar</a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white/80 backdrop-blur-md border-b border-gray-200 h-20 flex items-center justify-between px-8 sticky top-0 z-30">
            <h2 class="text-xl font-bold text-gray-800">Daftar Pesanan Masuk</h2>
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center font-bold text-orange-600">A</div>
        </header>

        <div class="p-8">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase border-b border-gray-200">
                            <th class="px-6 py-4 font-bold">ID Order</th>
                            <th class="px-6 py-4 font-bold">Tanggal</th>
                            <th class="px-6 py-4 font-bold">Pelanggan</th>
                            <th class="px-6 py-4 font-bold text-right">Total</th>
                            <th class="px-6 py-4 font-bold text-center">Status</th>
                            <th class="px-6 py-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $query = mysqli_query($conn, "SELECT o.*, u.name as user_name 
                                                      FROM orders o 
                                                      JOIN users u ON o.user_id = u.id 
                                                      ORDER BY o.id DESC");
                        
                        if(mysqli_num_rows($query) > 0):
                            while($row = mysqli_fetch_array($query)):
                                // Warna Warni Status
                                $status_class = "bg-gray-100 text-gray-600";
                                if($row['status'] == 'pending') $status_class = "bg-yellow-100 text-yellow-700";
                                if($row['status'] == 'paid') $status_class = "bg-blue-100 text-blue-700";
                                if($row['status'] == 'shipped') $status_class = "bg-purple-100 text-purple-700";
                                if($row['status'] == 'completed') $status_class = "bg-green-100 text-green-700";
                                if($row['status'] == 'cancelled') $status_class = "bg-red-100 text-red-700";
                        ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-700">#<?= $row['id'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?= date('d M Y, H:i', strtotime($row['order_date'])) ?></td>
                            <td class="px-6 py-4 font-medium text-gray-800"><?= $row['user_name'] ?></td>
                            <td class="px-6 py-4 text-right font-bold text-gray-700">Rp <?= number_format($row['total_amount']) ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase <?= $status_class ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="order_detail.php?id=<?= $row['id'] ?>" class="bg-white border border-gray-200 text-gray-600 hover:text-orange-600 hover:border-orange-500 px-3 py-1.5 rounded-lg text-sm font-bold transition shadow-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada pesanan masuk.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>