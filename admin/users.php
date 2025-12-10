<?php
session_start();
include '../config/koneksi.php';

// Cek Login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Logic Hapus User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Cegah admin menghapus dirinya sendiri
    if ($id == $_SESSION['user_id']) {
        echo "<script>alert('Gabisa hapus diri sendiri woi!'); window.location='users.php';</script>";
    } else {
        mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
        echo "<script>window.location='users.php';</script>";
    }
}

// Hitung Badge Sidebar
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user'"))['total'];
$total_pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status='pending'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pengguna - BakeryPro</title>
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
            <a href="orders.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium relative">
                <i class="fas fa-shopping-bag w-5"></i> Pesanan
                <?php if($total_pending > 0): ?>
                    <span class="absolute right-4 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm"><?= $total_pending ?></span>
                <?php endif; ?>
            </a>
            <a href="users.php" class="flex items-center gap-3 px-4 py-3 bg-orange-50 text-orange-700 rounded-xl font-bold transition relative">
                <i class="fas fa-users w-5"></i> Pelanggan
                <span class="absolute right-4 bg-blue-100 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-blue-200"><?= $total_users ?></span>
            </a>
        </nav>
        <div class="p-4 border-t border-gray-100">
            <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition font-bold"><i class="fas fa-sign-out-alt w-5"></i> Keluar</a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white/80 backdrop-blur-md border-b border-gray-200 h-20 flex items-center justify-between px-8 sticky top-0 z-30">
            <h2 class="text-xl font-bold text-gray-800">Daftar Semua Pengguna</h2>
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center font-bold text-orange-600">A</div>
        </header>

        <div class="p-8">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase border-b border-gray-200">
                            <th class="px-6 py-4 font-bold">Nama User</th>
                            <th class="px-6 py-4 font-bold">Email</th>
                            <th class="px-6 py-4 font-bold text-center">Role / Status</th>
                            <th class="px-6 py-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        // FIX: SELECT SEMUA USER (Hapus WHERE role='user')
                        $query = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
                        
                        if(mysqli_num_rows($query) > 0):
                            while($u = mysqli_fetch_array($query)):
                                // Logic Warna Badge Role
                                $role_bg = "bg-blue-100 text-blue-700 border-blue-200";
                                $role_text = "Customer";
                                
                                if($u['role'] == 'admin') {
                                    $role_bg = "bg-orange-100 text-orange-700 border-orange-200";
                                    $role_text = "Administrator";
                                }
                        ?>
                        <tr class="hover:bg-orange-50 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-500 border border-gray-200">
                                    <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800"><?= $u['name'] ?></p>
                                    <p class="text-xs text-gray-400">ID: #<?= $u['id'] ?></p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-medium"><?= $u['email'] ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="<?= $role_bg ?> border px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                                    <?= $role_text ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if($u['id'] != $_SESSION['user_id']): ?>
                                    <a href="users.php?delete=<?= $u['id'] ?>" onclick="return confirm('Yakin mau hapus user <?= $u['name'] ?>? Data tidak bisa kembali!')" 
                                       class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition shadow-sm border border-red-100" title="Hapus User">
                                        <i class="fas fa-trash text-xs"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 italic">You</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                                    Database User Kosong. (Aneh banget, minimal admin harus ada lho)
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>