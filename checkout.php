<?php
session_start();
include 'config/koneksi.php';
include 'layout/header.php';

// Cek Login & Keranjang
if (!isset($_SESSION['user_id'])) { echo "<script>window.location='login.php';</script>"; exit; }
if (empty($_SESSION['cart']) && !isset($_POST['checkout'])) { echo "<script>window.location='index.php';</script>"; exit; }

// --- LOGIC PHP ---
$success_order = false;
$new_order_id = "";

// HITUNG TOTAL (Buat jaga-jaga kalo user inspect element)
$total_belanja = 0;
if(isset($_SESSION['cart'])){
    foreach ($_SESSION['cart'] as $id => $qty) {
        $q = mysqli_query($conn, "SELECT price FROM products WHERE id='$id'");
        $p = mysqli_fetch_array($q);
        $total_belanja += ($p['price'] * $qty);
    }
}
$threshold = 150000;
$ongkir = ($total_belanja >= $threshold) ? 0 : 15000;
$grand_total = $total_belanja + $ongkir;

// PROSES CHECKOUT
if (isset($_POST['checkout'])) {
    $user_id = $_SESSION['user_id'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment = $_POST['payment_method'];
    $date = date('Y-m-d H:i:s');

    // 1. Simpan Order
    $query = "INSERT INTO orders (user_id, total_amount, status, order_date, shipping_address, shipping_phone, payment_method) 
              VALUES ('$user_id', '$grand_total', 'pending', '$date', '$address', '$phone', '$payment')";
    
    if (mysqli_query($conn, $query)) {
        $new_order_id = mysqli_insert_id($conn);

        // 2. Simpan Item & Kurangi Stok
        foreach ($_SESSION['cart'] as $id_produk => $qty) {
            $q_prod = mysqli_query($conn, "SELECT price, stock FROM products WHERE id='$id_produk'");
            $d_prod = mysqli_fetch_array($q_prod);
            
            $stok_baru = $d_prod['stock'] - $qty;
            mysqli_query($conn, "UPDATE products SET stock='$stok_baru' WHERE id='$id_produk'");
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$new_order_id', '$id_produk', '$qty', '{$d_prod['price']}')");
        }

        // 3. Set Flag Sukses & Kosongin Cart
        $success_order = true;
        unset($_SESSION['cart']);
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<style>
    .pay-card { cursor: pointer; transition: all 0.3s; border: 2px solid #F3F4F6; }
    .pay-card:hover { border-color: #FDBA74; transform: translateY(-2px); }
    .pay-card.selected { border-color: #EA580C; background-color: #FFF7ED; }
    .pay-card.selected .check-icon { display: block; }
    
    /* Animasi Sukses */
    .scale-in-center { animation: scale-in-center 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both; }
    @keyframes scale-in-center { 0% { transform: scale(0); opacity: 1; } 100% { transform: scale(1); opacity: 1; } }
    
    .bounce-happy { animation: bounce 1s infinite; }
    @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
</style>

<div class="bg-gray-50 min-h-screen py-10 font-poppins">
    <div class="container mx-auto px-6 max-w-6xl">

        <?php if ($success_order): ?>
            <div class="fixed inset-0 bg-white z-50 flex flex-col items-center justify-center p-6 scale-in-center">
                
                <div class="bounce-happy mb-6">
                    <svg width="180" height="180" viewBox="0 0 200 200">
                        <path d="M 50 150 Q 100 180 150 150 L 150 50 Q 100 20 50 50 Z" fill="#F97316"/>
                        <g transform="translate(80, 80)">
                            <path d="M 0 -10 L 2 -2 L 10 0 L 2 2 L 0 10 L -2 2 L -10 0 L -2 -2 Z" fill="white"/>
                        </g>
                        <g transform="translate(120, 80)">
                            <path d="M 0 -10 L 2 -2 L 10 0 L 2 2 L 0 10 L -2 2 L -10 0 L -2 -2 Z" fill="white"/>
                        </g>
                        <path d="M 80 110 Q 100 140 120 110" stroke="white" fill="none" stroke-width="5" stroke-linecap="round"/>
                        <path d="M 40 100 Q 20 50 50 40" stroke="#C2410C" fill="none" stroke-width="5" stroke-linecap="round"/>
                        <path d="M 160 100 Q 180 50 150 40" stroke="#C2410C" fill="none" stroke-width="5" stroke-linecap="round"/>
                    </svg>
                </div>

                <h1 class="text-4xl font-black text-gray-800 mb-2 text-center">Yey! Pesanan Berhasil 🎉</h1>
                <p class="text-gray-500 mb-8 text-center max-w-md">Terima kasih udah belanja! Monster kami lagi lari-lari nyiapin pesanan kamu nih.</p>

                <div class="bg-gray-50 border border-dashed border-gray-300 p-6 rounded-2xl w-full max-w-sm mb-8 relative">
                    <div class="absolute -left-3 top-1/2 w-6 h-6 bg-white rounded-full"></div>
                    <div class="absolute -right-3 top-1/2 w-6 h-6 bg-white rounded-full"></div>
                    
                    <div class="flex justify-between mb-2">
                        <span class="text-xs text-gray-400 font-bold uppercase">Order ID</span>
                        <span class="text-sm font-black text-gray-800">#<?= $new_order_id ?></span>
                    </div>
                    <div class="flex justify-between mb-4 border-b border-gray-200 pb-4">
                        <span class="text-xs text-gray-400 font-bold uppercase">Total Bayar</span>
                        <span class="text-sm font-black text-orange-600">Rp <?= number_format($grand_total) ?></span>
                    </div>
                    <p class="text-xs text-center text-gray-400">Silahkan cek status pesanan berkala ya!</p>
                </div>

                <div class="flex gap-4">
                    <a href="index.php" class="text-gray-500 font-bold px-6 py-3 hover:text-orange-600 transition">Ke Home</a>
                    <a href="my-order-detail.php?id=<?= $new_order_id ?>" class="bg-orange-600 text-white px-8 py-3 rounded-full font-bold hover:bg-orange-700 transition shadow-xl hover:shadow-orange-500/30 transform hover:-translate-y-1">
                        Lihat Pesanan Saya 📦
                    </a>
                </div>

                <script>
                    window.onload = function() {
                        var duration = 3 * 1000;
                        var animationEnd = Date.now() + duration;
                        var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 999 };

                        function randomInRange(min, max) { return Math.random() * (max - min) + min; }

                        var interval = setInterval(function() {
                            var timeLeft = animationEnd - Date.now();
                            if (timeLeft <= 0) return clearInterval(interval);
                            var particleCount = 50 * (timeLeft / duration);
                            confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
                            confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
                        }, 250);
                    };
                </script>
            </div>

        <?php else: ?>
            <h1 class="text-3xl font-black text-gray-800 mb-8 text-center">Checkout 🛍️</h1>

            <form action="" method="POST" id="checkoutForm">
                <div class="flex flex-col lg:flex-row gap-8">
                    
                    <div class="lg:w-2/3 space-y-8">
                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                                <span class="bg-orange-100 text-orange-600 w-8 h-8 flex items-center justify-center rounded-full text-sm">1</span> Info Pengiriman
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Penerima</label>
                                    <input type="text" value="<?= $_SESSION['name'] ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">WhatsApp</label>
                                    <input type="number" name="phone" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500" placeholder="0812xxxx">
                                </div>
                            </div>
                            <textarea name="address" rows="3" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500" placeholder="Alamat lengkap..."></textarea>
                        </div>

                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                                <span class="bg-orange-100 text-orange-600 w-8 h-8 flex items-center justify-center rounded-full text-sm">2</span> Pembayaran
                            </h2>
                            <input type="hidden" name="payment_method" id="selectedPayment" required>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="pay-card bg-white p-4 rounded-xl flex items-center gap-3 relative" onclick="selectPay(this, 'COD')">
                                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                                    <span class="font-bold text-sm">COD</span>
                                    <i class="fas fa-check-circle text-orange-600 absolute top-2 right-2 hidden check-icon"></i>
                                </div>
                                <div class="pay-card bg-white p-4 rounded-xl flex items-center gap-3 relative" onclick="selectPay(this, 'BCA')">
                                    <i class="fas fa-university text-blue-600 text-xl"></i>
                                    <span class="font-bold text-sm">BCA</span>
                                    <i class="fas fa-check-circle text-orange-600 absolute top-2 right-2 hidden check-icon"></i>
                                </div>
                                <div class="pay-card bg-white p-4 rounded-xl flex items-center gap-3 relative" onclick="selectPay(this, 'QRIS')">
                                    <i class="fas fa-qrcode text-gray-800 text-xl"></i>
                                    <span class="font-bold text-sm">QRIS</span>
                                    <i class="fas fa-check-circle text-orange-600 absolute top-2 right-2 hidden check-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:w-1/3">
                        <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 sticky top-24">
                            <h3 class="font-bold text-lg mb-6">Total Tagihan</h3>
                            <div class="space-y-3 text-sm text-gray-600 mb-6 border-b border-gray-100 pb-6">
                                <div class="flex justify-between"><span>Subtotal</span><span>Rp <?= number_format($total_belanja) ?></span></div>
                                <div class="flex justify-between"><span>Ongkir</span><span><?= ($ongkir==0)?'Gratis': 'Rp '.number_format($ongkir) ?></span></div>
                            </div>
                            <div class="flex justify-between items-end mb-6">
                                <span class="font-bold text-gray-800">Total</span>
                                <span class="font-black text-2xl text-orange-600">Rp <?= number_format($grand_total) ?></span>
                            </div>
                            <button type="submit" name="checkout" class="block w-full bg-gray-900 text-white py-4 rounded-xl font-bold hover:bg-orange-600 transition shadow-xl">
                                Bayar Sekarang 💳
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function selectPay(el, val) {
        document.querySelectorAll('.pay-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('selectedPayment').value = val;
    }
    
    document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
        if(!document.getElementById('selectedPayment').value) {
            e.preventDefault();
            Swal.fire('Eits!', 'Pilih metode pembayaran dulu bos!', 'warning');
        }
    });
</script>

<?php include 'layout/footer.php'; ?>