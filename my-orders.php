<?php
include 'config/koneksi.php';
include 'layout/header.php';

// Cek Login
if(!isset($_SESSION['user_id'])){
    echo "<script>window.location='login.php';</script>";
    exit;
}

$uid = $_SESSION['user_id'];

// --- LOGIC BATALKAN PESANAN ---
if(isset($_POST['cancel_order'])){
    $oid = $_POST['order_id'];
    $reason = $_POST['alasan']; // Ini alesan yang dipilih user
    
    // Security Check: Pastikan order punya user ini & statusnya pending
    $cek = mysqli_query($conn, "SELECT status FROM orders WHERE id='$oid' AND user_id='$uid'");
    
    if(mysqli_num_rows($cek) > 0){
        $data = mysqli_fetch_array($cek);
        if($data['status'] == 'pending'){
            // 1. RESTOCK BARANG
            $items = mysqli_query($conn, "SELECT product_id, quantity FROM order_items WHERE order_id='$oid'");
            while($item = mysqli_fetch_array($items)){
                $pid = $item['product_id'];
                $qty = $item['quantity'];
                mysqli_query($conn, "UPDATE products SET stock = stock + $qty WHERE id='$pid'");
            }
            
            // 2. UPDATE STATUS
            // (Opsional: Simpen alasan batal ke kolom baru kalo ada, disini gw update status aja)
            $update = mysqli_query($conn, "UPDATE orders SET status='cancelled' WHERE id='$oid'");
            
            if($update) {
                echo "<script>
                    setTimeout(function() {
                        Swal.fire({
                            title: 'Berhasil Dibatalkan',
                            text: 'Stok udah dikembaliin. Nanti pesen lagi ya! 😿',
                            icon: 'success',
                            confirmButtonColor: '#ea580c'
                        });
                    }, 100);
                </script>";
            }
        }
    }
}

// QUERY DATA
$q_active = mysqli_query($conn, "SELECT * FROM orders WHERE user_id='$uid' AND status IN ('pending', 'paid', 'shipped') ORDER BY id DESC");
$q_history = mysqli_query($conn, "SELECT * FROM orders WHERE user_id='$uid' AND status IN ('completed', 'cancelled') ORDER BY id DESC");
?>

<style>
    /* CSS BARU BUAT PILIHAN ALASAN (PENGGANTI DROPDOWN) */
    .reason-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 15px;
        text-align: left;
    }
    .reason-card {
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        padding: 12px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 13px;
        color: #4B5563;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .reason-card:hover { border-color: #FDBA74; background: #FFF7ED; }
    
    /* Kalo Dipilih (Radio Checked) */
    .reason-input:checked + .reason-card {
        border-color: #EA580C;
        background-color: #FFF7ED;
        color: #C2410C;
        font-weight: bold;
    }
    .reason-input { display: none; } /* Umpetin radio button asli */

    /* Animasi Monster & Progress */
    .step-line { position: absolute; top: 15px; left: 0; width: 100%; height: 3px; background: #E5E7EB; z-index: 0; }
    .step-active .step-icon { background-color: #EA580C; color: white; border-color: #EA580C; }
    .step-active p { color: #EA580C; font-weight: bold; }
    
    .cry-anim { animation: cry 2s infinite; }
    @keyframes cry { 0% { transform: translateY(0); opacity: 0; } 50% { opacity: 1; } 100% { transform: translateY(20px); opacity: 0; } }
    .shake-head { animation: shake 3s infinite; transform-origin: bottom center; }
    @keyframes shake { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-5deg); } 75% { transform: rotate(5deg); } }
</style>

<div class="py-12 bg-gray-50 min-h-screen font-poppins">
    <div class="container mx-auto px-6 max-w-5xl">
        
        <h1 class="text-3xl font-black text-gray-800 mb-2">Pesanan Saya 📦</h1>
        <p class="text-gray-500 mb-10">Pantau paketmu dan cek riwayat jajanmu di sini.</p>

        <div class="mb-16">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                🚀 Sedang Dalam Proses
                <span class="bg-orange-100 text-orange-600 text-xs px-2 py-1 rounded-full"><?= mysqli_num_rows($q_active) ?></span>
            </h2>

            <?php if(mysqli_num_rows($q_active) > 0): ?>
                <div class="space-y-6">
                    <?php while($active = mysqli_fetch_array($q_active)): 
                        $step = 1;
                        if($active['status'] == 'paid') $step = 2;
                        if($active['status'] == 'shipped') $step = 3;
                    ?>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-orange-100 relative overflow-hidden">
                        
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-100 pb-4 mb-4 gap-4">
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Order ID: #<?= $active['id'] ?></p>
                                <p class="text-sm text-gray-500">Dipesan: <?= date('d M, H:i', strtotime($active['order_date'])) ?></p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="font-black text-orange-600">Rp <?= number_format($active['total_amount']) ?></span>
                                
                                <?php if($active['status'] == 'pending'): ?>
                                <button type="button" onclick="askCancel('<?= $active['id'] ?>')" class="text-xs text-red-500 bg-white border border-red-200 px-3 py-2 rounded-lg font-bold hover:bg-red-50 hover:border-red-300 transition flex items-center gap-1 shadow-sm">
                                    <i class="fas fa-times-circle"></i> Batalkan
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="relative flex justify-between items-center mb-6 px-4">
                            <div class="step-line"></div> 
                            <div class="relative z-10 text-center <?= ($step >= 1) ? 'step-active' : '' ?>">
                                <div class="step-icon w-8 h-8 mx-auto rounded-full bg-white border-2 border-gray-300 flex items-center justify-center text-xs font-bold transition duration-500">1</div>
                                <p class="text-xs mt-2 text-gray-400">Pending</p>
                            </div>
                            <div class="relative z-10 text-center <?= ($step >= 2) ? 'step-active' : '' ?>">
                                <div class="step-icon w-8 h-8 mx-auto rounded-full bg-white border-2 border-gray-300 flex items-center justify-center text-xs font-bold transition duration-500">2</div>
                                <p class="text-xs mt-2 text-gray-400">Diproses</p>
                            </div>
                            <div class="relative z-10 text-center <?= ($step >= 3) ? 'step-active' : '' ?>">
                                <div class="step-icon w-8 h-8 mx-auto rounded-full bg-white border-2 border-gray-300 flex items-center justify-center text-xs font-bold transition duration-500">3</div>
                                <p class="text-xs mt-2 text-gray-400">Dikirim</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="my-order-detail.php?id=<?= $active['id'] ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-50 transition">Rincian</a>
                            <button onclick="lacakPaket('<?= $active['id'] ?>', '<?= $active['status'] ?>')" class="px-4 py-2 bg-orange-600 rounded-lg text-sm font-bold text-white hover:bg-orange-700 transition shadow-lg shadow-orange-200">Lacak Paket</button>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-2xl p-8 text-center border border-dashed border-gray-300">
                    <div class="text-6xl mb-4">💤</div>
                    <h3 class="font-bold text-gray-800 text-lg">Semua Aman!</h3>
                    <p class="text-gray-500 text-sm">Gak ada pesanan yang lagi nunggu.</p>
                    <a href="products.php" class="inline-block mt-4 text-orange-600 font-bold hover:underline">Belanja Sekarang →</a>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-6">📜 Riwayat Pesanan</h2>
            <?php if(mysqli_num_rows($q_history) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php while($hist = mysqli_fetch_array($q_history)): 
                        $is_cancel = ($hist['status'] == 'cancelled');
                        $bg = $is_cancel ? 'bg-red-50 border-red-100' : 'bg-white border-gray-100';
                        $icon = $is_cancel ? '❌ Dibatalkan' : '✅ Selesai';
                        $text = $is_cancel ? 'text-red-600' : 'text-green-600';
                    ?>
                    <div class="<?= $bg ?> p-5 rounded-xl border hover:shadow-md transition">
                        <div class="flex justify-between mb-3">
                            <span class="text-xs font-bold uppercase <?= $text ?>"><?= $icon ?></span>
                            <span class="text-xs text-gray-400"><?= date('d M Y', strtotime($hist['order_date'])) ?></span>
                        </div>
                        <h4 class="font-bold text-gray-800">Order #<?= $hist['id'] ?></h4>
                        <p class="text-sm text-gray-500 mb-4">Total: Rp <?= number_format($hist['total_amount']) ?></p>
                        <div class="flex gap-2">
                            <a href="my-order-detail.php?id=<?= $hist['id'] ?>" class="flex-1 text-center py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-white transition">Lihat Detail</a>
                            <?php if(!$is_cancel): ?>
                                <a href="cart.php" class="flex-1 text-center py-2 bg-yellow-50 border border-yellow-200 rounded-lg text-xs font-bold text-yellow-700 hover:bg-yellow-100 transition">⭐ Ulas</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-400 text-center py-4">Belum ada riwayat.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<form id="cancelForm" method="POST" action="" style="display:none;">
    <input type="hidden" name="cancel_order" value="true">
    <input type="hidden" name="order_id" id="cancelOrderId">
    <input type="hidden" name="alasan" id="cancelReason">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. FITUR BATAL (MONSTER NANGIS + PILIHAN MODERN)
    function askCancel(id) {
        
        // HTML KONTEN MODAL
        const modalContent = `
            <div class="mb-4">
                <svg width="120" height="120" viewBox="0 0 200 200" class="mx-auto shake-head">
                    <path d="M 50 160 Q 100 190 150 160 L 150 60 Q 100 20 50 60 Z" fill="#94A3B8"/>
                    <g transform="translate(80, 90)"><circle cx="0" cy="0" r="8" fill="white"/><circle cx="0" cy="3" r="3" fill="black"/></g>
                    <g transform="translate(120, 90)"><circle cx="0" cy="0" r="8" fill="white"/><circle cx="0" cy="3" r="3" fill="black"/></g>
                    <path d="M 90 130 Q 100 115 110 130" stroke="white" fill="none" stroke-width="4" stroke-linecap="round"/>
                    <circle cx="75" cy="100" r="3" fill="#60A5FA" class="cry-anim"/>
                    <circle cx="125" cy="100" r="3" fill="#60A5FA" class="cry-anim" style="animation-delay: 0.5s"/>
                </svg>
            </div>
            
            <p class="text-gray-500 text-sm mb-4">Yah.. kok dibatalin? Pilih alasannya dong:</p>
            
            <div class="reason-grid">
                <label>
                    <input type="radio" name="swal_reason" value="Ganti Menu" class="reason-input">
                    <div class="reason-card">🍔 Mau Ganti Menu</div>
                </label>
                <label>
                    <input type="radio" name="swal_reason" value="Salah Alamat" class="reason-input">
                    <div class="reason-card">🏠 Salah Alamat</div>
                </label>
                <label>
                    <input type="radio" name="swal_reason" value="Lupa Voucher" class="reason-input">
                    <div class="reason-card">🎟️ Lupa Voucher</div>
                </label>
                <label>
                    <input type="radio" name="swal_reason" value="Berubah Pikiran" class="reason-input">
                    <div class="reason-card">🤔 Berubah Pikiran</div>
                </label>
            </div>
        `;

        Swal.fire({
            title: 'Yakin mau batalin?',
            html: modalContent,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Gajadi deh',
            focusConfirm: false,
            preConfirm: () => {
                // Cari radio yang dipilih
                const selected = document.querySelector('input[name="swal_reason"]:checked');
                if (!selected) {
                    Swal.showValidationMessage('Pilih dulu alasannya bre!')
                }
                return selected ? selected.value : null
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Masukin data ke form hidden & submit
                document.getElementById('cancelOrderId').value = id;
                document.getElementById('cancelReason').value = result.value;
                document.getElementById('cancelForm').submit();
            }
        })
    }

    // 2. FITUR LACAK
    function lacakPaket(id, status) {
        let msg = '';
        if(status == 'pending') msg = 'Sabar ya, Admin lagi nyiapin adonannya nih! ⏳';
        else if(status == 'paid') msg = 'Pembayaran oke! Roti lagi dipanggang 🔥';
        else if(status == 'shipped') msg = 'Kurir monster sedang meluncur ke lokasi kamu! 🚚💨';

        Swal.fire({ title: 'Status Order #' + id, text: msg, icon: 'info', confirmButtonColor: '#ea580c' });
    }
</script>

<?php include 'layout/footer.php'; ?>