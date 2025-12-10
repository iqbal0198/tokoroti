<?php
session_start();
include '../config/koneksi.php';

// 1. CEK LOGIN (Wajib Admin)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// 2. DATA REALTIME DASHBOARD
// Hitung Total Pendapatan (Status 'completed')
$q_income = mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders WHERE status='completed'");
$d_income = mysqli_fetch_assoc($q_income);
$total_income = $d_income['total'] ?? 0;

// Hitung Total Pesanan Masuk
$q_orders = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders");
$total_orders = mysqli_fetch_assoc($q_orders)['total'];

// Hitung Pesanan Pending (Butuh Aksi)
$q_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status='pending'");
$total_pending = mysqli_fetch_assoc($q_pending)['total'];

// Hitung Total Produk Roti
$q_prod = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
$total_products = mysqli_fetch_assoc($q_prod)['total'];

// Hitung Total User/Pelanggan
$q_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user'");
$total_users = mysqli_fetch_assoc($q_users)['total'];

// Ambil Nama Admin buat Header
$admin_name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - BakeryPro</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }
        
        /* Glass Effect Card */
        .glass-card { 
            background: white; 
            border: 1px solid #E2E8F0; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); 
            transition: transform 0.2s;
        }
        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-gray-800">

    <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 hidden md:flex flex-col z-20">
        <div class="p-6 flex items-center gap-3 border-b border-gray-50">
            <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center font-black text-white shadow-lg shadow-orange-500/30 text-xl">
                B
            </div>
            <div>
                <h1 class="text-lg font-bold tracking-tight text-gray-900 leading-none">BakeryPro</h1>
                <p class="text-[10px] text-gray-400 font-medium tracking-wider uppercase mt-1">Admin Panel</p>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
            <p class="text-xs text-gray-400 font-bold uppercase px-2 mb-2 tracking-wider">Overview</p>
            
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 bg-orange-50 text-orange-700 rounded-xl font-bold transition border border-orange-100">
                <i class="fas fa-chart-pie w-5 text-center"></i> Dashboard
            </a>

            <p class="text-xs text-gray-400 font-bold uppercase px-2 mb-2 mt-6 tracking-wider">Management</p>
            
            <a href="products.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium group">
                <i class="fas fa-box-open w-5 text-center group-hover:text-orange-500 transition"></i> Kelola Produk
            </a>
            
            <a href="product_add.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium group">
                <i class="fas fa-plus-circle w-5 text-center group-hover:text-orange-500 transition"></i> Tambah Baru
            </a>

            <a href="orders.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium group relative">
                <i class="fas fa-shopping-bag w-5 text-center group-hover:text-orange-500 transition"></i> Pesanan
                <?php if($total_pending > 0): ?>
                    <span class="absolute right-3 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                        <?= $total_pending ?>
                    </span>
                <?php endif; ?>
            </a>

            <a href="users.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium group">
                <i class="fas fa-users w-5 text-center group-hover:text-orange-500 transition"></i> Pelanggan
                <span class="ml-auto bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded-full">
                    <?= $total_users ?>
                </span>
            </a>
        </nav>

        <div class="p-4 border-t border-gray-100">
            <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition font-bold w-full">
                <i class="fas fa-sign-out-alt w-5"></i> Keluar
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        
        <header class="bg-white/80 backdrop-blur-md border-b border-gray-200 h-20 flex items-center justify-between px-8 sticky top-0 z-30">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Selamat Datang, Admin! 👋</h2>
                <p class="text-xs text-gray-500 mt-0.5">Pantau terus perkembangan tokomu hari ini.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="hidden md:block text-right mr-2">
                    <p class="text-xs font-bold text-gray-700"><?= date('l, d F Y') ?></p>
                    <p class="text-[10px] text-gray-400">Jakarta, Indonesia</p>
                </div>
                
                <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-900 leading-tight"><?= $admin_name ?></p>
                        <p class="text-[10px] text-orange-600 font-bold bg-orange-100 px-2 py-0.5 rounded-full inline-block mt-1">Super Admin</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-orange-400 to-red-500 p-0.5 shadow-md">
                        <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-orange-600 font-bold">
                            <?= substr($admin_name, 0, 1) ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <div class="glass-card p-6 rounded-2xl relative overflow-hidden">
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Total Pendapatan</p>
                            <h3 class="text-2xl font-black text-gray-800">Rp <?= number_format($total_income) ?></h3>
                        </div>
                        <div class="p-3 bg-green-100 text-green-600 rounded-xl">
                            <i class="fas fa-wallet text-xl"></i>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-green-50 rounded-full opacity-50"></div>
                </div>

                <div class="glass-card p-6 rounded-2xl relative overflow-hidden">
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Total Transaksi</p>
                            <h3 class="text-2xl font-black text-gray-800"><?= $total_orders ?> <span class="text-sm font-normal text-gray-400">Order</span></h3>
                        </div>
                        <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                            <i class="fas fa-shopping-bag text-xl"></i>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-blue-50 rounded-full opacity-50"></div>
                </div>

                <div class="glass-card p-6 rounded-2xl relative overflow-hidden border-orange-200 bg-orange-50/30">
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div>
                            <p class="text-orange-600 text-sm font-bold mb-1">Perlu Diproses</p>
                            <h3 class="text-2xl font-black text-orange-700"><?= $total_pending ?> <span class="text-sm font-normal text-orange-400">Pending</span></h3>
                        </div>
                        <div class="p-3 bg-orange-500 text-white rounded-xl shadow-lg shadow-orange-500/30 animate-pulse">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                    </div>
                    <a href="orders.php" class="text-xs font-bold text-orange-600 hover:text-orange-800 relative z-10 flex items-center gap-1">
                        Proses Sekarang <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="glass-card p-6 rounded-2xl relative overflow-hidden">
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Menu Aktif</p>
                            <h3 class="text-2xl font-black text-gray-800"><?= $total_products ?> <span class="text-sm font-normal text-gray-400">Varian</span></h3>
                        </div>
                        <div class="p-3 bg-purple-100 text-purple-600 rounded-xl">
                            <i class="fas fa-bread-slice text-xl"></i>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-purple-50 rounded-full opacity-50"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 glass-card p-6 rounded-2xl">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Analitik Penjualan</h3>
                            <p class="text-xs text-gray-400">Performa penjualan minggu ini</p>
                        </div>
                        <button class="text-xs bg-gray-100 px-3 py-1.5 rounded-full text-gray-600 hover:bg-gray-200 transition font-bold">
                            <i class="fas fa-download mr-1"></i> Report
                        </button>
                    </div>
                    <div class="h-72 w-full">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <div class="glass-card p-6 rounded-2xl">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-gray-800 text-lg">Order Terbaru</h3>
                        <a href="orders.php" class="text-xs text-blue-600 font-bold hover:underline">Lihat Semua</a>
                    </div>
                    
                    <div class="space-y-4">
                        <?php
                        // Ambil 5 pesanan terakhir (Join User)
                        $q_recent = mysqli_query($conn, "SELECT o.*, u.name as user_name 
                                                         FROM orders o 
                                                         JOIN users u ON o.user_id = u.id 
                                                         ORDER BY o.id DESC LIMIT 5");
                        
                        if(mysqli_num_rows($q_recent) > 0):
                            while($ro = mysqli_fetch_array($q_recent)):
                                // Styling Status Badge
                                $st_bg = "bg-gray-100 text-gray-600";
                                if($ro['status']=='pending') $st_bg = "bg-yellow-100 text-yellow-800 border border-yellow-200";
                                if($ro['status']=='completed') $st_bg = "bg-green-100 text-green-800 border border-green-200";
                                if($ro['status']=='cancelled') $st_bg = "bg-red-100 text-red-800 border border-red-200";
                        ?>
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition border border-gray-100 group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-500 group-hover:bg-white group-hover:shadow-md transition">
                                    <?= substr($ro['user_name'], 0, 1) ?>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 line-clamp-1"><?= $ro['user_name'] ?></p>
                                    <p class="text-[10px] text-gray-400 font-mono">ID: #<?= $ro['id'] ?> • Rp <?= number_format($ro['total_amount']) ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] px-2 py-1 rounded-full font-bold uppercase <?= $st_bg ?>">
                                    <?= $ro['status'] ?>
                                </span>
                                <p class="text-[10px] text-gray-300 mt-1"><?= date('H:i', strtotime($ro['order_date'])) ?></p>
                            </div>
                        </div>
                        <?php 
                            endwhile;
                        else:
                            echo "<div class='text-center py-10 text-gray-400 text-sm'>Belum ada transaksi hari ini.</div>";
                        endif; 
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Gradient Warna Chart
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(249, 115, 22, 0.5)'); // Orange
        gradient.addColorStop(1, 'rgba(249, 115, 22, 0.0)');

        new Chart(ctx, {
            type: 'line', // Grafik Garis
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Pendapatan (Ribu Rp)',
                    data: [150, 230, 180, 320, 290, 450, 500], // Data Dummy (Ganti pake PHP kalo mau advanced)
                    borderColor: '#ea580c', // Warna Garis Orange
                    backgroundColor: gradient, // Warna Isi Gradient
                    borderWidth: 3,
                    tension: 0.4, // Lengkungan garis
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#ea580c',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }, // Umpetin legend default
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f3f4f6' },
                        ticks: { font: { size: 10, family: 'Outfit' }, color: '#9ca3af' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, family: 'Outfit' }, color: '#9ca3af' }
                    }
                }
            }
        });
    </script>
</body>
</html>