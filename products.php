<?php
include 'config/koneksi.php';
include 'layout/header.php';

$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
?>

<style>
    /* 1. Definisi Gerakan (Fade In + Naik Dikit) */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* 2. Class buat manggil animasi */
    .product-anim {
        animation: fadeInUp 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    /* 3. Empty State Animation */
    .fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

<div class="bg-gray-50 min-h-screen pb-12">
    
    <div class="bg-white shadow-sm pt-8 pb-6 sticky top-0 z-30 transition-all" id="mainHeader">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-800 flex items-center gap-2">
                        🥐 Katalog Menu <span id="catTitle" class="text-orange-500"><?= $kategori ? '/ '.ucfirst($kategori) : '' ?></span>
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Cari roti favoritmu secepat kilat ⚡</p>
                </div>

                <div class="relative w-full md:w-96">
                    <input type="text" id="realTimeSearch" 
                           class="w-full pl-12 pr-4 py-3 rounded-full border border-gray-200 bg-gray-100 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-100 outline-none transition text-gray-700 font-medium" 
                           placeholder="Coba ketik 'Coklat'..." autocomplete="off">
                    <span class="absolute left-4 top-3.5 text-gray-400 text-lg">🔍</span>
                </div>
            </div>

            <div class="mt-6 flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                <a href="products.php" class="px-5 py-2 rounded-full text-sm font-bold transition <?= ($kategori=='')?'bg-gray-900 text-white shadow-lg':'bg-white border hover:border-orange-500' ?>">Semua</a>
                <a href="products.php?kategori=roti" class="px-5 py-2 rounded-full text-sm font-bold transition <?= ($kategori=='roti')?'bg-orange-500 text-white shadow-lg':'bg-white border hover:border-orange-500' ?>">🍞 Roti</a>
                <a href="products.php?kategori=cake" class="px-5 py-2 rounded-full text-sm font-bold transition <?= ($kategori=='cake')?'bg-pink-500 text-white shadow-lg':'bg-white border hover:border-pink-500' ?>">🍰 Cake</a>
                <a href="products.php?kategori=pastry" class="px-5 py-2 rounded-full text-sm font-bold transition <?= ($kategori=='pastry')?'bg-yellow-500 text-white shadow-lg':'bg-white border hover:border-yellow-500' ?>">🥐 Pastry</a>
                <a href="products.php?kategori=donat" class="px-5 py-2 rounded-full text-sm font-bold transition <?= ($kategori=='donat')?'bg-purple-500 text-white shadow-lg':'bg-white border hover:border-purple-500' ?>">🍩 Donat</a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8 relative min-h-[400px]">
        
        <div id="productGrid" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php
            $where = $kategori ? "WHERE categories.name LIKE '%$kategori%'" : "";
            $query_text = "SELECT products.*, categories.name as cat_name 
                           FROM products 
                           JOIN categories ON products.category_id = categories.id 
                           $where ORDER BY products.id DESC";
            $query = mysqli_query($conn, $query_text);
            $jumlah_produk = mysqli_num_rows($query);

            if($jumlah_produk > 0):
                while($row = mysqli_fetch_array($query)):
            ?>
                <div class="product-card bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-xl transition duration-300 border border-gray-100 flex flex-col h-full group" 
                     data-name="<?= strtolower($row['name']) ?>" 
                     data-desc="<?= strtolower($row['description']) ?>">
                    
                    <a href="product_detail.php?id=<?= $row['id'] ?>" class="block h-56 overflow-hidden relative">
                        <img src="assets/img/<?= $row['image'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </a>

                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="product-title text-lg font-bold text-gray-800 truncate hover:text-orange-600 transition cursor-pointer">
                            <?= $row['name'] ?>
                        </h3>
                        <p class="text-xs text-orange-500 font-bold uppercase mb-2"><?= $row['cat_name'] ?></p>
                        <p class="text-gray-500 text-sm mb-4 line-clamp-2"><?= $row['description'] ?></p>
                        
                        <div class="mt-auto flex justify-between items-center">
                            <span class="text-xl font-black text-gray-900">Rp <?= number_format($row['price']) ?></span>
                            <a href="product_detail.php?id=<?= $row['id'] ?>" class="bg-gray-100 text-gray-900 w-10 h-10 flex items-center justify-center rounded-full hover:bg-orange-600 hover:text-white transition">➝</a>
                        </div>
                    </div>
                </div>
            <?php 
                endwhile;
            endif;
            ?>
        </div>

        <?php if($jumlah_produk == 0): ?>
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center mt-10 fade-in">
            <svg width="150" height="150" viewBox="0 0 200 200" class="mb-6 opacity-80">
                <g transform="translate(60, 50)">
                    <path d="M 0 40 Q 0 0 40 0 Q 80 0 80 40 L 80 80 Q 80 90 70 90 L 10 90 Q 0 90 0 80 Z" fill="#E5E7EB"/> 
                    <g transform="translate(40, 35)">
                        <path d="M -10 25 Q 0 15 10 25" fill="none" stroke="#9CA3AF" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="-15" cy="0" r="3" fill="#9CA3AF"/>
                        <circle cx="15" cy="0" r="3" fill="#9CA3AF"/>
                    </g>
                    <text x="90" y="30" font-size="30">💨</text>
                </g>
            </svg>
            <h3 class="text-2xl font-bold text-gray-400">Yah, Stok <?= ucfirst($kategori) ?> Lagi Kosong</h3>
            <a href="products.php" class="mt-4 text-orange-600 font-bold hover:underline">Lihat Menu Lain →</a>
        </div>
        <?php endif; ?>

        <div id="noResult" class="hidden absolute inset-0 flex flex-col items-center justify-center text-center mt-20 fade-in">
             <svg width="150" height="150" viewBox="0 0 200 200" class="mb-6 opacity-80">
                <g transform="translate(60, 50)">
                    <path d="M 0 40 Q 0 0 40 0 Q 80 0 80 40 L 80 80 Q 80 90 70 90 L 10 90 Q 0 90 0 80 Z" fill="#F3F4F6"/> 
                    <path d="M 0 40 Q 0 0 40 0 Q 80 0 80 40 L 80 80 Q 80 90 70 90 L 10 90 Q 0 90 0 80 Z" stroke="#D1D5DB" stroke-width="2" fill="none"/>
                    <g transform="translate(40, 35)">
                        <g transform="translate(-15, 0)"><circle cx="0" cy="0" r="8" fill="white"/><circle cx="0" cy="2" r="3" fill="#374151"/></g>
                        <g transform="translate(15, 0)"><circle cx="0" cy="0" r="8" fill="white"/><circle cx="0" cy="2" r="3" fill="#374151"/></g>
                        <path d="M -10 25 Q 0 15 10 25" fill="none" stroke="#374151" stroke-width="3" stroke-linecap="round"/>
                    </g>
                    <text x="90" y="30" font-size="30">❓</text>
                </g>
            </svg>
            <h3 class="text-2xl font-bold text-gray-400">Yah, Roti "<span id="searchKeyword" class="text-orange-500"></span>" Gak Ketemu</h3>
            <p class="text-gray-400 mt-2">Coba kata kunci lain ya bre.</p>
        </div>

    </div>

    <div class="container mx-auto px-6 mt-12">
        <div class="bg-gray-900 rounded-3xl p-10 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="absolute top-0 right-0 w-64 h-64 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
            <div class="relative z-10">
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">Butuh Snack buat Acara? 🎉</h2>
                <p class="text-gray-400">Kami terima pesanan partai besar. Harga spesial + Gratis Ongkir!</p>
            </div>
            <button onclick="contactAdmin()" class="bg-orange-500 text-white px-8 py-3 rounded-full font-bold hover:bg-orange-600 transition shadow-lg transform hover:scale-105">
                Hubungi Admin
            </button>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('realTimeSearch');
    const cards = document.querySelectorAll('.product-card');
    const noResult = document.getElementById('noResult');
    const searchKeywordSpan = document.getElementById('searchKeyword');

    searchInput.addEventListener('input', function(e) {
        const keyword = e.target.value.toLowerCase();
        let visibleCount = 0;

        cards.forEach((card, index) => {
            const name = card.getAttribute('data-name');
            const desc = card.getAttribute('data-desc');
            const titleElement = card.querySelector('.product-title');
            
            // Logic Filter
            if (name.includes(keyword) || desc.includes(keyword)) {
                // MATCH: Tampilkan & Kasih Animasi
                card.classList.remove('hidden');
                
                // --- INI RAHASIA ANIMASINYA ---
                // Kita hapus class animasi dulu, baru tambah lagi (Trigger Reflow)
                card.classList.remove('product-anim');
                void card.offsetWidth; // Trigger Reflow (Reset Animasi)
                card.classList.add('product-anim'); 
                
                // Kasi delay dikit per item (Staggering Effect) biar keren
                // card.style.animationDelay = `${visibleCount * 0.05}s`; 

                visibleCount++;

                // Highlight Text
                const originalText = titleElement.innerText;
                if (keyword !== "") {
                    const regex = new RegExp(`(${keyword})`, 'gi');
                    titleElement.innerHTML = originalText.replace(regex, '<span class="bg-yellow-200 text-orange-800 rounded px-0.5">$1</span>');
                } else {
                    titleElement.innerHTML = originalText; 
                }

            } else {
                // NO MATCH: Sembunyiin Langsung (Biar layout rapi nutup)
                card.classList.add('hidden');
                card.classList.remove('product-anim');
            }
        });

        // Toggle Empty State Monster
        if (visibleCount === 0 && cards.length > 0) {
            noResult.classList.remove('hidden');
            searchKeywordSpan.innerText = e.target.value;
        } else {
            noResult.classList.add('hidden');
        }
    });

    function contactAdmin() {
        Swal.fire({
            title: 'Hubungi Kami',
            text: 'Mau pesan katering? Chat Admin sekarang!',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Chat WA',
            confirmButtonColor: '#25D366',
            cancelButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) window.open('https://wa.me/6281234567890', '_blank');
        })
    }
</script>

<?php include 'layout/footer.php'; ?>