<?php
// Cek session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// === FIX 1: LOGIC JUMLAH PESANAN ===
// Nanti angka '1' ini lu ganti pake hasil query database: 
// Contoh: $conn->query("SELECT * FROM orders WHERE user_id = ...")->num_rows;
$order_count = 1; 

// Hitung jumlah barang di cart
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cart_count += $qty;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeryShop 🍞</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Outfit', sans-serif; }
        
        /* Dropdown Animation */
        .dropdown-menu {
            transform-origin: top right;
            transition: all 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
            transform: scale(0.95);
            opacity: 0;
            visibility: hidden;
        }
        .dropdown-menu.show { transform: scale(1); opacity: 1; visibility: visible; }
    </style>
</head>
<body class="bg-gray-50">

    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all">
        <div class="container mx-auto px-6 h-24 flex justify-between items-center">
            
            <a href="index.php" class="flex items-center gap-2 group">
                <span class="text-3xl font-black text-orange-600 tracking-tighter group-hover:scale-105 transition">BakeryShop</span>
                <span class="text-3xl animate-bounce">🍞</span>
            </a>

            <div class="hidden md:flex items-center gap-10 text-lg font-bold text-gray-500">
                <a href="index.php" class="hover:text-orange-600 transition <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'text-orange-600' : '' ?>">Home</a>
                <a href="products.php" class="hover:text-orange-600 transition <?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'text-orange-600' : '' ?>">Katalog Menu</a>
            </div>

            <div class="flex items-center gap-6">
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    
                    <a href="my-orders.php" class="relative group text-gray-400 hover:text-orange-600 transition" title="Pesanan Saya">
                        <i class="fas fa-box-open text-2xl group-hover:scale-110 transition duration-300"></i>
                        
                        <?php if($order_count > 0): ?>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm animate-pulse">
                                <?= $order_count ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <a href="cart.php" class="relative group text-gray-400 hover:text-orange-600 transition mr-2" title="Keranjang">
                        <i class="fas fa-shopping-bag text-2xl group-hover:scale-110 transition duration-300"></i>
                        <?php if($cart_count > 0): ?>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm animate-pulse">
                                <?= $cart_count ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <div class="w-px h-8 bg-gray-200 hidden md:block"></div>

                    <div class="relative">
                        <button onclick="document.getElementById('userDropdown').classList.toggle('show')" class="flex items-center gap-3 focus:outline-none group">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs text-gray-400">Halo,</p>
                                <p class="text-base font-bold text-gray-800 group-hover:text-orange-600 transition truncate max-w-[120px]">
                                    <?= explode(" ", $_SESSION['name'] ?? 'Guest')[0] ?>
                                </p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-600 font-bold border border-gray-100 shadow-sm group-hover:ring-2 group-hover:ring-orange-200 transition text-lg">
                                <?= substr($_SESSION['name'] ?? 'G', 0, 1) ?>
                            </div>
                        </button>

                        <div id="userDropdown" class="dropdown-menu absolute right-0 mt-4 w-60 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 overflow-hidden">
                            
                            <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50">
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Akun Kamu</p>
                                <p class="text-sm font-bold text-gray-900 truncate"><?= $_SESSION['email'] ?? '' ?></p>
                            </div>

                            <div class="py-2">
                                <a href="profile.php" class="px-6 py-3 text-sm font-medium text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition flex items-center gap-3">
                                    <i class="fas fa-user-cog w-5 text-center"></i> Edit Profil
                                </a>

                                <a href="https://wa.me/6281234567890" target="_blank" class="px-6 py-3 text-sm font-medium text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition flex items-center gap-3">
                                    <i class="fab fa-whatsapp w-5 text-center"></i> Bantuan CS
                                </a>

                                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                                    <a href="admin/index.php" class="px-6 py-3 text-sm font-medium text-gray-600 hover:bg-purple-50 hover:text-purple-600 transition flex items-center gap-3">
                                        <i class="fas fa-rocket w-5 text-center"></i> Admin Panel
                                    </a>
                                <?php endif; ?>
                            </div>

                            <div class="border-t border-gray-100 my-1"></div>
                            
                            <a href="logout.php" class="px-6 py-3 text-sm font-medium text-red-500 hover:bg-red-50 transition flex items-center gap-3">
                                <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar
                            </a>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="flex items-center gap-4">
                        <a href="login.php" class="text-base font-bold text-gray-500 hover:text-orange-600 transition">Masuk</a>
                        <a href="register.php" class="bg-gray-900 text-white px-6 py-3 rounded-full text-base font-bold hover:bg-orange-600 transition shadow-lg hover:shadow-orange-500/30">Daftar</a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </nav>
    
    <script>
        window.onclick = function(e) {
            if (!e.target.closest('button')) {
                var dropdowns = document.getElementsByClassName("dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    if (dropdowns[i].classList.contains('show')) dropdowns[i].classList.remove('show');
                }
            }
        }
        if(typeof AOS !== 'undefined') { AOS.init(); }
    </script>
</body>
</html>