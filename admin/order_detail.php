<?php
session_start();
include '../config/koneksi.php';

// 1. Cek Login Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id_order = $_GET['id'];

// 2. Logic Update Status
if (isset($_POST['update_status'])) {
    $status_baru = $_POST['status'];
    mysqli_query($conn, "UPDATE orders SET status='$status_baru' WHERE id='$id_order'");
    echo "<script>alert('Status pesanan diperbarui!'); window.location='order_detail.php?id=$id_order';</script>";
}

// 3. Ambil Data Order & User
$q_order = mysqli_query($conn, "SELECT o.*, u.name as user_name, u.email 
                                FROM orders o 
                                JOIN users u ON o.user_id = u.id 
                                WHERE o.id='$id_order'");
$order = mysqli_fetch_array($q_order);

// Kalo order ga nemu
if (!$order) {
    echo "<script>alert('Order tidak ditemukan!'); window.location='orders.php';</script>";
    exit;
}

// 4. Ambil Item Produk
$q_items = mysqli_query($conn, "SELECT i.*, p.name, p.image, p.price as current_price 
                                FROM order_items i 
                                JOIN products p ON i.product_id = p.id 
                                WHERE i.order_id='$id_order'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Order #<?= $order['id'] ?> - BakeryPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }
        @media print {
            aside, header, .no-print { display: none !important; }
            main { margin: 0; padding: 0; }
            .print-area { box-shadow: none; border: none; }
        }
    </style>
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
            <a href="orders.php" class="flex items-center gap-3 px-4 py-3 bg-orange-50 text-orange-700 rounded-xl font-bold transition"><i class="fas fa-shopping-bag w-5"></i> Pesanan</a>
            <a href="users.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium"><i class="fas fa-users w-5"></i> Pelanggan</a>
        </nav>
        <div class="p-4 border-t border-gray-100">
            <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition font-bold"><i class="fas fa-sign-out-alt w-5"></i> Keluar</a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        
        <header class="bg-white/80 backdrop-blur-md border-b border-gray-200 h-20 flex items-center justify-between px-8 sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <a href="orders.php" class="text-gray-400 hover:text-gray-800 transition"><i class="fas fa-arrow-left"></i></a>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Detail Pesanan #<?= $order['id'] ?></h2>
                    <p class="text-xs text-gray-500"><?= date('d F Y, H:i', strtotime($order['order_date'])) ?></p>
                </div>
            </div>
            
            <div class="flex gap-3 no-print">
                <button onclick="window.print()" class="bg-white border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-50 transition">
                    <i class="fas fa-print mr-2"></i> Cetak
                </button>
            </div>
        </header>

        <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden print-area">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="font-bold text-gray-800">Item yang Dibeli</h3>
                        </div>
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                                <tr>
                                    <th class="px-6 py-3">Produk</th>
                                    <th class="px-6 py-3 text-center">Harga</th>
                                    <th class="px-6 py-3 text-center">Qty</th>
                                    <th class="px-6 py-3 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php 
                                $subtotal_hitung = 0;
                                while($item = mysqli_fetch_array($q_items)): 
                                    $total_per_item = $item['current_price'] * $item['quantity'];
                                    $subtotal_hitung += $total_per_item;
                                ?>
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <img src="../assets/img/<?= $item['image'] ?>" class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                            <span class="font-bold text-gray-800 text-sm"><?= $item['name'] ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">Rp <?= number_format($item['current_price']) ?></td>
                                    <td class="px-6 py-4 text-center text-sm font-bold"><?= $item['quantity'] ?></td>
                                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-800">Rp <?= number_format($total_per_item) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-200">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-600">Total Akhir</td>
                                    <td class="px-6 py-4 text-right font-black text-xl text-orange-600">Rp <?= number_format($subtotal_hitung) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="space-y-6 no-print">
                    
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                        <h3 class="font-bold text-gray-800 mb-4">Update Status Pesanan</h3>
                        <form action="" method="POST">
                            <div class="mb-4">
                                <label class="text-xs font-bold text-gray-500 uppercase">Status Saat Ini</label>
                                <?php
                                    $color = 'bg-gray-100 text-gray-600';
                                    if($order['status']=='pending') $color='bg-yellow-100 text-yellow-700';
                                    if($order['status']=='paid') $color='bg-blue-100 text-blue-700';
                                    if($order['status']=='shipped') $color='bg-purple-100 text-purple-700';
                                    if($order['status']=='completed') $color='bg-green-100 text-green-700';
                                    if($order['status']=='cancelled') $color='bg-red-100 text-red-700';
                                ?>
                                <div class="mt-2 inline-block px-3 py-1 rounded-full text-sm font-bold uppercase <?= $color ?>">
                                    <?= $order['status'] ?>
                                </div>
                            </div>

                            <label class="text-xs font-bold text-gray-500 uppercase mb-2 block">Ganti Status ke:</label>
                            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-4 focus:ring-2 focus:ring-orange-500 outline-none text-sm">
                                <option value="pending" <?= ($order['status']=='pending')?'selected':'' ?>>Pending (Menunggu)</option>
                                <option value="paid" <?= ($order['status']=='paid')?'selected':'' ?>>Paid (Sudah Bayar)</option>
                                <option value="shipped" <?= ($order['status']=='shipped')?'selected':'' ?>>Shipped (Dikirim)</option>
                                <option value="completed" <?= ($order['status']=='completed')?'selected':'' ?>>Completed (Selesai)</option>
                                <option value="cancelled" <?= ($order['status']=='cancelled')?'selected':'' ?>>Cancelled (Batal)</option>
                            </select>

                            <button type="submit" name="update_status" class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition shadow-md">
                                Simpan Perubahan
                            </button>
                        </form>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                        <h3 class="font-bold text-gray-800 mb-4">Info Pembeli</h3>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-500 text-xl">
                                <?= substr($order['user_name'], 0, 1) ?>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900"><?= $order['user_name'] ?></p>
                                <p class="text-xs text-gray-500"><?= $order['email'] ?></p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 space-y-2 border-t pt-4 border-gray-100">
                            <p><span class="font-bold">ID User:</span> #<?= $order['user_id'] ?></p>
                            <p><span class="font-bold">Alamat:</span> <span class="italic text-gray-400">(Alamat default user)</span></p> 
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
</body>
</html>