<?php
include 'config/koneksi.php';
include 'layout/header.php';

// Cek Login
if(!isset($_SESSION['user_id'])){
    echo "<script>window.location='login.php';</script>";
    exit;
}

$id_order = $_GET['id'];
$uid = $_SESSION['user_id'];

// 1. Ambil Data Order (Security Check: Punya user sendiri)
$q_order = mysqli_query($conn, "SELECT * FROM orders WHERE id='$id_order' AND user_id='$uid'");
$order = mysqli_fetch_array($q_order);

if(!$order){
    echo "<script>alert('Pesanan tidak ditemukan!'); window.location='my-orders.php';</script>";
    exit;
}

// 2. Ambil Item Produk
$q_items = mysqli_query($conn, "SELECT i.*, p.name, p.image, p.price as current_price 
                                FROM order_items i 
                                JOIN products p ON i.product_id = p.id 
                                WHERE i.order_id='$id_order'");

// 3. Tentukan State untuk Progress Bar & Monster
$status = $order['status'];
$progress = 0;
$monster_type = 'waiting'; // Default

if($status == 'pending') { $progress = 25; $monster_type='waiting'; }
if($status == 'paid')    { $progress = 50; $monster_type='cooking'; }
if($status == 'shipped') { $progress = 75; $monster_type='delivery'; }
if($status == 'completed'){ $progress = 100; $monster_type='happy'; }
if($status == 'cancelled'){ $progress = 0; $monster_type='sad'; }
?>

<style>
    /* Animasi Monster */
    .bounce-monster { animation: bounce 3s infinite ease-in-out; }
    @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
</style>

<div class="py-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6 max-w-5xl">
        
        <div class="text-sm text-gray-500 mb-6">
            <a href="index.php" class="hover:text-orange-600">Home</a> / 
            <a href="my-orders.php" class="hover:text-orange-600">Pesanan Saya</a> / 
            <span class="font-bold text-gray-800">Order #<?= $order['id'] ?></span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h2 class="font-bold text-gray-800 mb-6 flex justify-between items-center">
                        Status Pesanan
                        <span class="text-xs font-normal text-gray-500"><?= date('d F Y, H:i', strtotime($order['order_date'])) ?></span>
                    </h2>

                    <?php if($status != 'cancelled'): ?>
                    <div class="relative pt-2 pb-6">
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-orange-500 transition-all duration-1000 ease-out" style="width: <?= $progress ?>%"></div>
                        </div>
                        
                        <div class="flex justify-between mt-4 text-xs font-bold text-gray-400 uppercase tracking-wider relative">
                            <div class="<?= ($progress >= 25) ? 'text-orange-600' : '' ?>">Menunggu</div>
                            <div class="<?= ($progress >= 50) ? 'text-orange-600' : '' ?>">Diproses</div>
                            <div class="<?= ($progress >= 75) ? 'text-orange-600' : '' ?>">Dikirim</div>
                            <div class="<?= ($progress >= 100) ? 'text-green-600' : '' ?>">Selesai</div>
                        </div>
                    </div>
                    <?php else: ?>
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl font-bold text-center border border-red-100">
                            ❌ Pesanan Dibatalkan
                        </div>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                        <h3 class="font-bold text-gray-800">Isi Paket</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <?php 
                        $subtotal_real = 0;
                        while($item = mysqli_fetch_array($q_items)): 
                            $total_item = $item['current_price'] * $item['quantity'];
                            $subtotal_real += $total_item;
                        ?>
                        <div class="p-6 flex items-center gap-4 hover:bg-gray-50 transition">
                            <div class="w-20 h-20 rounded-xl overflow-hidden border border-gray-200 flex-shrink-0">
                                <img src="assets/img/<?= $item['image'] ?>" class="w-full h-full object-cover">
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-lg"><?= $item['name'] ?></h4>
                                <p class="text-sm text-gray-500">
                                    Rp <?= number_format($item['current_price']) ?> x <?= $item['quantity'] ?> pcs
                                </p>
                            </div>
                            
                            <div class="text-right font-bold text-orange-600 text-lg">
                                Rp <?= number_format($total_item) ?>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="flex gap-4">
                    <a href="my-orders.php" class="px-6 py-3 border border-gray-300 rounded-xl font-bold text-gray-600 hover:bg-gray-50 transition">
                        ← Kembali
                    </a>
                    
                    <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20mau%20tanya%20pesanan%20ID%20%23<?= $order['id'] ?>" target="_blank" class="flex-1 bg-green-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-green-600 transition text-center shadow-lg shadow-green-200 flex justify-center items-center gap-2">
                        <span class="text-xl">💬</span> Hubungi Admin Bantuan
                    </a>
                </div>

            </div>

            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-gradient-to-b from-orange-50 to-white p-8 rounded-3xl border border-orange-100 text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
                    
                    <div class="h-40 flex items-center justify-center mb-4 bounce-monster">
                        <?php if($monster_type == 'waiting'): ?>
                            <svg width="120" height="120" viewBox="0 0 200 200">
                                <path d="M 50 150 Q 100 180 150 150 L 150 50 Q 100 20 50 50 Z" fill="#60A5FA"/>
                                <circle cx="80" cy="90" r="10" fill="white"/><circle cx="80" cy="90" r="3" fill="black"/>
                                <circle cx="120" cy="90" r="10" fill="white"/><circle cx="120" cy="90" r="3" fill="black"/>
                                <path d="M 90 120 Q 100 130 110 120" stroke="black" fill="none" stroke-width="3"/>
                                <circle cx="150" cy="100" r="15" fill="white" stroke="#2563EB" stroke-width="2"/>
                                <path d="M 150 100 L 150 90" stroke="black" stroke-width="2"/>
                                <path d="M 150 100 L 158 100" stroke="black" stroke-width="2"/>
                            </svg>
                            <h3 class="font-bold text-gray-800 mt-2">Menunggu Konfirmasi...</h3>
                            <p class="text-xs text-gray-500">Admin lagi ngecek pesananmu nih.</p>

                        <?php elseif($monster_type == 'cooking'): ?>
                            <svg width="120" height="120" viewBox="0 0 200 200">
                                <path d="M 50 150 Q 100 180 150 150 L 150 50 Q 100 20 50 50 Z" fill="#F97316"/>
                                <path d="M 70 50 L 70 30 Q 100 10 130 30 L 130 50 Z" fill="white" stroke="#E5E7EB"/>
                                <circle cx="80" cy="90" r="10" fill="white"/><circle cx="80" cy="90" r="3" fill="black"/>
                                <circle cx="120" cy="90" r="10" fill="white"/><circle cx="120" cy="90" r="3" fill="black"/>
                                <path d="M 90 120 Q 100 130 110 120" stroke="black" fill="none" stroke-width="3"/>
                            </svg>
                            <h3 class="font-bold text-gray-800 mt-2">Sedang Disiapkan!</h3>
                            <p class="text-xs text-gray-500">Roti lagi dioven biar anget 🔥</p>

                        <?php elseif($monster_type == 'delivery'): ?>
                            <svg width="120" height="120" viewBox="0 0 200 200">
                                <path d="M 50 150 Q 100 180 150 150 L 150 50 Q 100 20 50 50 Z" fill="#8B5CF6"/>
                                <path d="M 60 50 L 140 50 L 120 30 L 80 30 Z" fill="#4C1D95"/>
                                <circle cx="80" cy="90" r="10" fill="white"/><circle cx="82" cy="90" r="3" fill="black"/>
                                <circle cx="120" cy="90" r="10" fill="white"/><circle cx="122" cy="90" r="3" fill="black"/>
                                <path d="M 90 120 Q 100 130 110 120" stroke="black" fill="none" stroke-width="3"/>
                                <path d="M 20 100 L 40 100" stroke="#DDD" stroke-width="3" stroke-linecap="round"/>
                                <path d="M 10 120 L 30 120" stroke="#DDD" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                            <h3 class="font-bold text-gray-800 mt-2">Otw Lokasi!</h3>
                            <p class="text-xs text-gray-500">Siap-siap buka pintu ya.</p>

                        <?php elseif($monster_type == 'happy'): ?>
                            <svg width="120" height="120" viewBox="0 0 200 200">
                                <path d="M 50 150 Q 100 180 150 150 L 150 50 Q 100 20 50 50 Z" fill="#10B981"/>
                                <path d="M 70 90 L 80 80 L 90 90" stroke="white" fill="none" stroke-width="4" stroke-linecap="round"/>
                                <path d="M 110 90 L 120 80 L 130 90" stroke="white" fill="none" stroke-width="4" stroke-linecap="round"/>
                                <path d="M 80 110 Q 100 130 120 110" stroke="white" fill="none" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                            <h3 class="font-bold text-gray-800 mt-2">Yummy!</h3>
                            <p class="text-xs text-gray-500">Terima kasih udah jajan.</p>

                        <?php else: ?>
                            <div class="text-6xl">😿</div>
                            <h3 class="font-bold text-gray-800 mt-2">Yah.. Dibatalkan</h3>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Rincian Biaya</h3>
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Subtotal Produk</span>
                            <span>Rp <?= number_format($subtotal_real) ?></span>
                        </div>
                        <div class="flex justify-between text-green-600">
                            <span>Diskon</span>
                            <span>- Rp 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ongkos Kirim</span>
                            <span>Gratis</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between items-center">
                            <span class="font-bold text-gray-800">Total Bayar</span>
                            <span class="font-black text-xl text-orange-600">Rp <?= number_format($order['total_amount']) ?></span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Info Pengiriman</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase">Penerima</p>
                            <p class="font-bold text-gray-800"><?= $_SESSION['name'] ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase">Metode Bayar</p>
                            <p class="font-bold text-gray-800">Transfer Bank / COD</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<?php include 'layout/footer.php'; ?>