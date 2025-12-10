<?php
session_start();
include 'config/koneksi.php';
include 'layout/header.php';

$id_produk = $_GET['id'];

// 1. LOGIC ADD TO CART
if(isset($_POST['add_to_cart'])){
    if(!isset($_SESSION['cart'])){ $_SESSION['cart'] = []; }
    $pid = $_POST['product_id'];
    $qty = $_POST['qty']; 
    if(isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid] += $qty; 
    } else {
        $_SESSION['cart'][$pid] = $qty; 
    }
    echo "<script>
        setTimeout(function() {
            Swal.fire({
                title: 'Berhasil!', text: 'Roti masuk keranjang 🛒', icon: 'success', timer: 1500, showConfirmButton: false
            });
        }, 100);
    </script>";
}

// 2. LOGIC REVIEW
if (isset($_POST['kirim_review'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>Swal.fire('Eits!', 'Login dulu dong baru review!', 'warning');</script>";
    } else {
        $uid = $_SESSION['user_id'];
        $rating = $_POST['rating'];
        $comment = $_POST['review'];
        $date = date('Y-m-d');
        mysqli_query($conn, "INSERT INTO reviews (user_id, product_id, rating, comment, created_at) VALUES ('$uid', '$id_produk', '$rating', '$comment', '$date')");
    }
}

// QUERY DATA
$q_prod = mysqli_query($conn, "SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id='$id_produk'");
$produk = mysqli_fetch_array($q_prod);

// RATING AVG
$d_rating = mysqli_fetch_array(mysqli_query($conn, "SELECT AVG(rating) as avg_rat, COUNT(*) as total_rat FROM reviews WHERE product_id='$id_produk'"));
$avg_rating = round($d_rating['avg_rat'] ?? 0, 1);
$total_review = $d_rating['total_rat'];
?>

<style>
    /* Animasi Fade In */
    .tab-content { display: none; }
    .tab-content.active { display: block; animation: fadeIn 0.4s ease-out forwards; }
    
    @keyframes fadeIn { 
        from { opacity: 0; transform: translateY(10px); } 
        to { opacity: 1; transform: translateY(0); } 
    }

    /* Styling Tombol Tab */
    .tab-btn {
        padding: 1rem 2rem;
        font-weight: 700;
        color: #9ca3af; /* gray-400 */
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
        white-space: nowrap;
    }
    .tab-btn:hover { color: #ea580c; background-color: #fff7ed; }
    
    /* State Active */
    .tab-btn.active {
        color: #ea580c; /* orange-600 */
        border-bottom: 3px solid #ea580c;
    }

    /* Rating Star Styling */
    .rate { float: left; height: 46px; padding: 0 10px; }
    .rate:not(:checked) > input { position:absolute; top:-9999px; }
    .rate:not(:checked) > label { float:right; width:1em; overflow:hidden; white-space:nowrap; cursor:pointer; font-size:30px; color:#ccc; }
    .rate:not(:checked) > label:before { content: '★ '; }
    .rate > input:checked ~ label { color: #ffc700; }
    .rate:not(:checked) > label:hover, .rate:not(:checked) > label:hover ~ label { color: #deb217; }
    .rate > input:checked + label:hover, .rate > input:checked + label:hover ~ label, .rate > input:checked ~ label:hover, .rate > input:checked ~ label:hover ~ label, .rate > label:hover ~ input:checked ~ label { color: #c59b08; }
</style>

<div class="bg-gray-50 min-h-screen py-10 font-outfit">
    <div class="container mx-auto px-6 max-w-7xl">

        <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
            <a href="index.php" class="hover:text-orange-600">Home</a> <i class="fas fa-chevron-right text-xs"></i>
            <a href="products.php" class="hover:text-orange-600">Katalog</a> <i class="fas fa-chevron-right text-xs"></i>
            <span class="font-bold text-gray-800"><?= $produk['name'] ?></span>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-gray-100 mb-10">
            <div class="flex flex-col md:flex-row">
                
                <div class="md:w-1/2 bg-gray-100 relative group h-[400px] md:h-auto">
                    <img src="assets/img/<?= $produk['image'] ?>" class="w-full h-full object-cover absolute inset-0 transition duration-700 hover:scale-105">
                    <div class="absolute top-6 left-6 bg-white/90 backdrop-blur px-4 py-2 rounded-full shadow-lg flex items-center gap-2 z-10">
                        <span class="text-xl">🔥</span><span class="font-bold text-gray-800 text-sm">Fresh from Oven</span>
                    </div>
                </div>

                <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="bg-orange-50 text-orange-600 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider"><?= $produk['category_name'] ?></span>
                            <div class="flex items-center gap-1 text-yellow-400 text-sm">
                                <i class="fas fa-star"></i><span class="font-bold text-gray-800 text-lg"><?= $avg_rating ?></span><span class="text-gray-400 text-xs">(<?= $total_review ?> Review)</span>
                            </div>
                        </div>

                        <h1 class="text-4xl font-black text-gray-900 mb-4 leading-tight"><?= $produk['name'] ?></h1>
                        <div class="flex items-center gap-4 mb-6">
                            <span class="text-3xl font-black text-orange-600">Rp <?= number_format($produk['price']) ?></span>
                            <span class="<?= ($produk['stock']>0)?'bg-green-100 text-green-700':'bg-red-100 text-red-700' ?> px-3 py-1 rounded-full text-xs font-bold">Stok: <?= $produk['stock'] ?></span>
                        </div>

                        <div class="grid grid-cols-3 gap-3 mb-8">
                            <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-orange-200 transition"><div class="text-2xl mb-1">🍬</div><p class="text-[10px] font-bold text-gray-500 uppercase">Less Sugar</p></div>
                            <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-orange-200 transition"><div class="text-2xl mb-1">🧈</div><p class="text-[10px] font-bold text-gray-500 uppercase">Premium Butter</p></div>
                            <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-orange-200 transition"><div class="text-2xl mb-1">🚫</div><p class="text-[10px] font-bold text-gray-500 uppercase">No Pengawet</p></div>
                        </div>
                    </div>

                    <form action="" method="POST" class="border-t border-gray-100 pt-6">
                        <input type="hidden" name="product_id" value="<?= $produk['id'] ?>">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center bg-gray-100 rounded-full px-2 py-1 w-32 justify-between">
                                <button type="button" onclick="ubahQty(-1)" class="w-8 h-8 bg-white rounded-full shadow font-bold text-gray-600 hover:text-orange-600 transition">-</button>
                                <input type="number" name="qty" id="qtyInput" value="1" min="1" max="<?= $produk['stock'] ?>" class="w-8 text-center bg-transparent border-none focus:ring-0 font-bold text-gray-800" readonly>
                                <button type="button" onclick="ubahQty(1)" class="w-8 h-8 bg-white rounded-full shadow font-bold text-gray-600 hover:text-orange-600 transition">+</button>
                            </div>
                            
                            <button type="submit" name="add_to_cart" <?= ($produk['stock'] <= 0) ? 'disabled' : '' ?> class="flex-1 bg-gray-900 text-white py-4 rounded-full font-bold hover:bg-orange-600 transition shadow-xl flex justify-center items-center gap-2 disabled:bg-gray-300 disabled:cursor-not-allowed transform hover:-translate-y-1">
                                <i class="fas fa-shopping-cart"></i> 
                                <?= ($produk['stock'] > 0) ? 'Masukin Keranjang' : 'Stok Habis' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-12">
            
            <div class="flex border-b border-gray-100 overflow-x-auto no-scrollbar">
                <button onclick="openTab('desc')" class="tab-btn active" id="btn-desc">Deskripsi</button>
                <button onclick="openTab('info')" class="tab-btn" id="btn-info">Info Nutrisi</button>
                <button onclick="openTab('review')" class="tab-btn" id="btn-review">Review (<?= $total_review ?>)</button>
            </div>

            <div class="p-8 md:p-10">
                
                <div id="tab-desc" class="tab-content active">
                    <h3 class="font-bold text-xl text-gray-800 mb-4">Tentang Produk Ini</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        <?= nl2br($produk['description'] ?? 'Tidak ada deskripsi.') ?>
                    </p>
                </div>

                <div id="tab-info" class="tab-content">
                    <h3 class="font-bold text-xl text-gray-800 mb-6">Informasi Gizi & Bahan</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-orange-50 p-4 rounded-2xl text-center">
                            <p class="text-xs font-bold text-orange-400 uppercase">Kalori</p>
                            <p class="text-2xl font-black text-gray-800">250 <span class="text-xs font-normal">kkal</span></p>
                        </div>
                         <div class="bg-blue-50 p-4 rounded-2xl text-center">
                            <p class="text-xs font-bold text-blue-400 uppercase">Protein</p>
                            <p class="text-2xl font-black text-gray-800">5 <span class="text-xs font-normal">g</span></p>
                        </div>
                    </div>
                </div>

                <div id="tab-review" class="tab-content">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                        
                        <div class="lg:col-span-1">
                            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm sticky top-24">
                                <h4 class="font-bold text-gray-800 mb-2 text-lg">Tulis Pengalamanmu</h4>
                                <p class="text-sm text-gray-500 mb-6">Gimana rasanya? Enak gak?</p>
                                
                                <form action="" method="POST">
                                    <div class="flex justify-center mb-4 bg-gray-50 rounded-xl py-2">
                                        <div class="rate">
                                            <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 stars">5</label>
                                            <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars">4</label>
                                            <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars">3</label>
                                            <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars">2</label>
                                            <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star">1</label>
                                        </div>
                                    </div>
                                    
                                    <textarea name="review" rows="4" class="w-full rounded-xl border-gray-200 bg-gray-50 p-4 text-sm focus:bg-white focus:ring-2 focus:ring-orange-200 transition mb-4 outline-none" placeholder="Ceritain detailnya disini..."></textarea>
                                    
                                    <button type="submit" name="kirim_review" class="w-full bg-gray-900 text-white font-bold py-3 rounded-xl hover:bg-orange-600 transition shadow-lg">Kirim Ulasan</button>
                                </form>
                            </div>
                        </div>

                        <div class="lg:col-span-2 space-y-6">
                            <?php
                            $q_rv = mysqli_query($conn, "SELECT r.*, u.name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id='$id_produk' ORDER BY r.id DESC");
                            if(mysqli_num_rows($q_rv) > 0):
                                while($rv = mysqli_fetch_array($q_rv)):
                            ?>
                            <div class="flex gap-5 border-b border-gray-100 pb-6 last:border-0 animate-fade-in">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center font-bold text-orange-600 text-lg flex-shrink-0 border-2 border-white shadow-sm">
                                    <?= substr($rv['name'],0,1) ?>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-1">
                                        <h5 class="font-bold text-gray-900"><?= $rv['name'] ?></h5>
                                        <span class="text-xs text-gray-400"><?= date('d M Y', strtotime($rv['created_at'])) ?></span>
                                    </div>
                                    <div class="text-yellow-400 text-sm mb-2">
                                        <?php for($i=0; $i<$rv['rating']; $i++) echo '<i class="fas fa-star"></i>'; ?>
                                        <?php for($i=$rv['rating']; $i<5; $i++) echo '<i class="far fa-star text-gray-200"></i>'; ?>
                                    </div>
                                    <p class="text-gray-600 text-sm leading-relaxed">"<?= $rv['comment'] ?>"</p>
                                </div>
                            </div>
                            <?php endwhile; else: ?>
                                <div class="text-center py-10">
                                    <div class="text-4xl mb-2">😴</div>
                                    <p class="text-gray-400 font-bold">Belum ada review nih.</p>
                                    <p class="text-gray-400 text-sm">Jadilah yang pertama!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="mt-12">
            <h3 class="font-bold text-2xl text-gray-800 mb-6 flex items-center gap-2"><i class="fas fa-fire text-orange-500"></i> Mungkin Kamu Suka</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php
                $q_rel = mysqli_query($conn, "SELECT * FROM products WHERE id != '$id_produk' ORDER BY RAND() LIMIT 4");
                while($rel = mysqli_fetch_array($q_rel)):
                ?>
                <a href="product_detail.php?id=<?= $rel['id'] ?>" class="bg-white p-4 rounded-2xl border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 group">
                    <div class="h-40 rounded-xl overflow-hidden mb-3 bg-gray-100 relative">
                        <img src="assets/img/<?= $rel['image'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <h4 class="font-bold text-gray-800 truncate mb-1"><?= $rel['name'] ?></h4>
                    <p class="text-orange-600 font-black text-sm">Rp <?= number_format($rel['price']) ?></p>
                </a>
                <?php endwhile; ?>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Logic Tab Switcher
    function openTab(tabName) {
        // 1. Sembunyikan semua konten
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        // 2. Reset tombol jadi abu-abu
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        
        // 3. Aktifin yang diklik
        document.getElementById('tab-' + tabName).classList.add('active');
        document.getElementById('btn-' + tabName).classList.add('active');
    }

    // Logic Quantity
    function ubahQty(val) {
        let input = document.getElementById('qtyInput');
        let current = parseInt(input.value);
        let max = parseInt(input.getAttribute('max'));
        let newVal = current + val;
        if(newVal >= 1 && newVal <= max) input.value = newVal;
    }
</script>

<?php include 'layout/footer.php'; ?>