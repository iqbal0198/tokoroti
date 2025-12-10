<?php
session_start();
include 'config/koneksi.php';
include 'layout/header.php';

// --- LOGIC PHP ---

// 1. HAPUS ITEM (Diproses setelah konfirmasi JS)
if (isset($_GET['del'])) {
    $id_hapus = $_GET['del'];
    unset($_SESSION['cart'][$id_hapus]);
    echo "<script>window.location='cart.php';</script>";
}

// 2. UPDATE QTY
if (isset($_POST['update_qty'])) {
    $id_produk = $_POST['product_id'];
    $qty_baru = $_POST['qty'];
    
    $cek = mysqli_query($conn, "SELECT stock FROM products WHERE id='$id_produk'");
    $stok_db = mysqli_fetch_array($cek)['stock'];

    if ($qty_baru > $stok_db) {
        echo "<script>Swal.fire('Ups!', 'Stok cuma sisa $stok_db pcs', 'warning');</script>";
    } elseif ($qty_baru < 1) {
        // Kalo 0, arahkan ke logic hapus via JS nanti, tapi disini unset aja buat jaga2
        unset($_SESSION['cart'][$id_produk]);
    } else {
        $_SESSION['cart'][$id_produk] = $qty_baru;
    }
}

// 3. KOSONGKAN
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    echo "<script>window.location='cart.php';</script>";
}

// Hitung Total
$total_belanja = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $qty) {
        $q = mysqli_query($conn, "SELECT price FROM products WHERE id='$id'");
        $p = mysqli_fetch_array($q);
        $total_belanja += ($p['price'] * $qty);
    }
}

$threshold = 150000;
$persen = ($total_belanja / $threshold) * 100;
if($persen > 100) $persen = 100;
?>

<style>
    .float-sad { animation: float 4s ease-in-out infinite; }
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
    
    /* Animasi Air Mata */
    .cry-anim { animation: cry 1.5s infinite; }
    @keyframes cry { 0% { transform: translateY(0); opacity: 0; } 50% { opacity: 1; } 100% { transform: translateY(20px); opacity: 0; } }
    
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>

<div class="bg-gray-50 py-10 font-poppins">
    <div class="container mx-auto px-6">

        <h1 class="text-3xl font-black text-gray-800 mb-8">Keranjang Belanja 🛒</h1>
        
        <?php if (empty($_SESSION['cart'])): ?>
            
            <div class="bg-white rounded-3xl p-10 text-center shadow-sm border border-gray-100 py-16 mb-12">
                <div class="float-sad mb-6">
                    <svg width="150" height="150" viewBox="0 0 200 200" class="mx-auto opacity-70">
                        <path d="M 50 160 Q 100 190 150 160 L 150 60 Q 100 20 50 60 Z" fill="#CBD5E1"/>
                        <g transform="translate(80, 90)"><circle cx="0" cy="0" r="8" fill="white"/><circle cx="0" cy="3" r="3" fill="#475569"/></g>
                        <g transform="translate(120, 90)"><circle cx="0" cy="0" r="8" fill="white"/><circle cx="0" cy="3" r="3" fill="#475569"/></g>
                        <path d="M 90 130 Q 100 120 110 130" stroke="white" fill="none" stroke-width="4" stroke-linecap="round"/>
                        <path d="M 75 105 Q 75 115 75 115" stroke="#BFDBFE" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Yah.. Keranjang Kosong</h2>
                <p class="text-gray-500 mb-8">Si Monster sedih nih belum dikasih makan.</p>
                <a href="products.php" class="bg-orange-600 text-white px-8 py-3 rounded-full font-bold hover:bg-orange-700 transition shadow-lg shadow-orange-200">
                    Belanja Sekarang
                </a>
            </div>

        <?php else: ?>
            
            <div class="flex flex-col lg:flex-row gap-8 mb-16">
                
                <div class="lg:w-2/3">
                    
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-orange-100 mb-6 flex items-center gap-4">
                        <div class="text-3xl">🚚</div>
                        <div class="flex-1">
                            <?php if($persen >= 100): ?>
                                <p class="text-sm font-bold text-green-600 mb-2">HORE! Kamu dapet <span class="underline">Gratis Ongkir</span>! 🎉</p>
                            <?php else: ?>
                                <p class="text-sm font-bold text-gray-700 mb-2">Tambah <b>Rp <?= number_format($threshold - $total_belanja) ?></b> lagi buat Gratis Ongkir!</p>
                            <?php endif; ?>
                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-orange-500 h-full rounded-full transition-all duration-1000" style="width: <?= $persen ?>%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center px-2">
                            <span class="text-sm font-bold text-gray-500">Item Dipilih</span>
                            <button onclick="confirmClear()" class="text-xs text-red-500 font-bold hover:underline bg-transparent border-none cursor-pointer">Hapus Semua</button>
                        </div>

                        <?php
                        $total_display = 0;
                        foreach ($_SESSION['cart'] as $id_produk => $jumlah):
                            $ambil = mysqli_query($conn, "SELECT * FROM products WHERE id='$id_produk'");
                            $produk = mysqli_fetch_array($ambil);
                            $subharga = $produk['price'] * $jumlah;
                            $total_display += $subharga;
                        ?>
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:border-orange-200 transition group relative">
                            <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-50 border border-gray-100 flex-shrink-0">
                                <img src="assets/img/<?= $produk['image'] ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-800 text-lg leading-tight"><?= $produk['name'] ?></h3>
                                <p class="text-xs text-gray-400 mb-2">Sisa Stok: <?= $produk['stock'] ?></p>
                                <p class="text-orange-600 font-bold">Rp <?= number_format($produk['price']) ?></p>
                            </div>
                            
                            <form method="POST" class="flex items-center bg-gray-50 rounded-lg p-1 border border-gray-200">
                                <input type="hidden" name="product_id" value="<?= $id_produk ?>">
                                <button type="submit" name="update_qty" onclick="this.form.qty.value--" class="w-8 h-8 rounded bg-white text-gray-600 shadow-sm hover:text-orange-600 font-bold transition">-</button>
                                <input type="number" name="qty" value="<?= $jumlah ?>" class="w-10 text-center bg-transparent text-sm font-bold border-none p-0 focus:ring-0" readonly>
                                <button type="submit" name="update_qty" onclick="this.form.qty.value++" class="w-8 h-8 rounded bg-white text-gray-600 shadow-sm hover:text-orange-600 font-bold transition">+</button>
                            </form>

                            <button onclick="confirmDelete(<?= $id_produk ?>)" class="text-gray-300 hover:text-red-500 p-2 transition ml-2 rounded-full hover:bg-red-50">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="lg:w-1/3">
                    <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 sticky top-24">
                        <h3 class="font-bold text-lg mb-6">Ringkasan Pesanan</h3>
                        <div class="bg-orange-50 p-3 rounded-xl mb-6 flex items-center gap-3">
                            <input type="checkbox" class="w-4 h-4 text-orange-600 rounded focus:ring-orange-500">
                            <label class="text-sm font-bold text-gray-700">Kirim sebagai hadiah? 🎁</label>
                        </div>
                        <div class="space-y-3 text-sm text-gray-600 mb-6 border-b border-gray-100 pb-6">
                            <div class="flex justify-between">
                                <span>Total Harga</span>
                                <span class="font-bold">Rp <?= number_format($total_display) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Ongkos Kirim</span>
                                <?php if($persen >= 100): ?>
                                    <span class="text-green-600 font-bold bg-green-100 px-2 py-0.5 rounded text-xs">GRATIS</span>
                                <?php else: ?>
                                    <span>Rp 15.000</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex justify-between items-end mb-6">
                            <span class="font-bold text-gray-800">Total Bayar</span>
                            <span class="font-black text-2xl text-orange-600">
                                Rp <?= number_format($total_display + ($persen >= 100 ? 0 : 15000)) ?>
                            </span>
                        </div>
                        <a href="checkout.php" class="block w-full bg-gray-900 text-white text-center py-4 rounded-xl font-bold text-lg hover:bg-orange-600 transition shadow-xl transform hover:-translate-y-1">
                            Checkout 🚀
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="border-t border-gray-200 pt-12 mt-8">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-2xl font-black text-gray-800">✨ Mungkin Kamu Suka</h2>
                    <p class="text-gray-500 text-sm mt-1">Jangan lupa cobain menu best seller ini.</p>
                </div>
                <a href="products.php" class="text-orange-600 font-bold text-sm hover:underline">Lihat Semua →</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php
                $q_sugg = mysqli_query($conn, "SELECT * FROM products ORDER BY RAND() LIMIT 4");
                if(mysqli_num_rows($q_sugg) > 0):
                    while($sugg = mysqli_fetch_array($q_sugg)):
                ?>
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 hover:shadow-lg transition group cursor-pointer h-full flex flex-col" onclick="window.location='product_detail.php?id=<?= $sugg['id'] ?>'">
                        <div class="h-40 rounded-xl overflow-hidden mb-4 relative bg-gray-50">
                            <img src="assets/img/<?= $sugg['image'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" onerror="this.src='https://placehold.co/400x300?text=No+Image'">
                            <button class="absolute bottom-2 right-2 bg-white w-8 h-8 rounded-full flex items-center justify-center shadow-md hover:bg-orange-600 hover:text-white transition"><i class="fas fa-plus"></i></button>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1 truncate"><?= $sugg['name'] ?></h4>
                        <div class="mt-auto">
                            <p class="text-orange-600 font-black">Rp <?= number_format($sugg['price']) ?></p>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else: 
                    for($i=1; $i<=4; $i++):
                ?>
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 opacity-50">
                        <div class="h-40 bg-gray-200 rounded-xl mb-4 animate-pulse"></div>
                        <div class="h-4 bg-gray-200 rounded w-3/4 mb-2 animate-pulse"></div>
                    </div>
                <?php endfor; endif; ?>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. Konfirmasi Hapus Item (Monster Nangis)
    function confirmDelete(id) {
        const sadMonster = `
            <div class="mb-4">
                <svg width="120" height="120" viewBox="0 0 200 200" class="mx-auto">
                    <path d="M 50 160 Q 100 190 150 160 L 150 60 Q 100 20 50 60 Z" fill="#94A3B8"/>
                    <g transform="translate(80, 90)"><circle cx="0" cy="0" r="8" fill="white"/><circle cx="0" cy="3" r="3" fill="black"/></g>
                    <g transform="translate(120, 90)"><circle cx="0" cy="0" r="8" fill="white"/><circle cx="0" cy="3" r="3" fill="black"/></g>
                    <path d="M 90 130 Q 100 120 110 130" stroke="white" fill="none" stroke-width="4" stroke-linecap="round"/>
                    <circle cx="75" cy="100" r="3" fill="#60A5FA" class="cry-anim"/>
                    <circle cx="125" cy="100" r="3" fill="#60A5FA" class="cry-anim" style="animation-delay: 0.5s"/>
                </svg>
            </div>
        `;

        Swal.fire({
            title: 'Yakin dihapus?',
            html: sadMonster + '<p class="text-gray-500 text-sm">Yah.. Monster sedih makanannya diambil 😿</p>',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Buang aja',
            cancelButtonText: 'Gajadi deh'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'cart.php?del=' + id;
            }
        })
    }

    // 2. Konfirmasi Hapus Semua
    function confirmClear() {
        Swal.fire({
            title: 'Kosongin Keranjang?',
            text: "Semua item bakal ilang loh!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Kosongin'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'cart.php?clear=true';
            }
        })
    }
</script>

<?php include 'layout/footer.php'; ?>