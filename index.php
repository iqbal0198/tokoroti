<?php
session_start();
include 'config/koneksi.php';
include 'layout/header.php';

// 1. LOGIC GREETING & WAKTU
date_default_timezone_set('Asia/Jakarta');
$jam = date('H');
$sapaan = "Halo";
if ($jam >= 5 && $jam < 11) $sapaan = "Selamat Pagi, Semangat! ☀️";
else if ($jam >= 11 && $jam < 15) $sapaan = "Selamat Siang, Jangan Lupa Makan! 🍛";
else if ($jam >= 15 && $jam < 18) $sapaan = "Sore! Waktunya Ngemil ☕";
else $sapaan = "Malam! Laper Tengah Malem? 🌙";

$username = isset($_SESSION['name']) ? explode(" ", $_SESSION['name'])[0] : 'Kakak';

// 2. QUERY BEST SELLER (Ambil 4 produk acak)
$q_best = mysqli_query($conn, "SELECT * FROM products ORDER BY RAND() LIMIT 4");

// 3. QUERY FLASH SALE (Ambil 2 produk)
$q_flash = mysqli_query($conn, "SELECT * FROM products LIMIT 2");
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
    /* Custom Animations */

    /* 1. WIGGLE (Goyang Happy) */
    @keyframes wiggle {
        0%, 100% { transform: rotate(-6deg); }
        50% { transform: rotate(6deg); }
    }
    .animate-wiggle { animation: wiggle 0.8s ease-in-out infinite; }

    /* 2. HEARTBEAT (Denyut Laper) */
    @keyframes heartbeat {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }
    .animate-heartbeat { animation: heartbeat 1s ease-in-out infinite; }

    /* 3. FLOAT (Ngambang Santai) */
    @keyframes float-icon {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    .animate-float { animation: float-icon 3s ease-in-out infinite; }
    
    /* 4. TILT (Miring Dikit - Opsional) */
    .hover-tilt:hover { transform: rotate(5deg) scale(1.05); }
    .blob { position: absolute; filter: blur(80px); z-index: 0; opacity: 0.5; }
    .float-monster { animation: float 6s ease-in-out infinite; }
    @keyframes float { 0%, 100% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-15px) rotate(5deg); } }
    
    .wiggle:hover { animation: wiggle 0.5s ease-in-out; }
    @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-5deg); } 75% { transform: rotate(5deg); } }

    /* Swiper Custom */
    .swiper-pagination-bullet-active { background-color: #EA580C !important; }
</style>

<div class="bg-gray-50 min-h-screen overflow-x-hidden font-poppins">

    <section class="relative bg-white pt-8 pb-16 rounded-b-[40px] shadow-sm overflow-hidden">
        <div class="blob bg-orange-200 w-96 h-96 top-0 -left-20 rounded-full mix-blend-multiply animate-pulse"></div>
        <div class="blob bg-yellow-200 w-96 h-96 bottom-0 -right-20 rounded-full mix-blend-multiply animate-pulse" style="animation-delay: 2s"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex justify-between items-center mb-8" data-aos="fade-down">
                <div>
                    <p class="text-orange-600 font-bold text-sm tracking-widest uppercase">BakeryPro Official Store</p>
                    <h2 class="text-2xl md:text-4xl font-black text-gray-800 mt-1"><?= $sapaan ?>, <span class="text-orange-500 underline decoration-wavy"><?= $username ?></span></h2>
                </div>
                <div class="hidden md:block w-1/3">
                    <form action="products.php" method="GET" class="relative">
                        <input type="text" name="keyword" class="w-full bg-gray-100 border-none rounded-full py-3 px-6 pl-12 text-sm focus:ring-2 focus:ring-orange-500 transition" placeholder="Lagi nyari roti apa?">
                        <span class="absolute left-4 top-3 text-gray-400">🔍</span>
                    </form>
                </div>
            </div>

            <div class="swiper mySwiper rounded-3xl shadow-2xl overflow-hidden aspect-[16/6] md:aspect-[16/7]" data-aos="zoom-in">
                <div class="swiper-wrapper">
                    <div class="swiper-slide relative bg-gray-900">
                        <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=2072&auto=format&fit=crop" class="w-full h-full object-cover opacity-60">
                        <div class="absolute inset-0 flex flex-col justify-center items-start p-12 text-white">
                            <span class="bg-orange-600 px-3 py-1 rounded text-xs font-bold mb-4">PROMO MINGGU INI</span>
                            <h2 class="text-3xl md:text-6xl font-black mb-4 leading-tight">Sourdough <br>Premium Artisan.</h2>
                            <p class="mb-8 max-w-lg text-gray-200">Dibuat dengan ragi alami berumur 5 tahun. Rasa otentik Eropa di lidah lokal.</p>
                            <a href="products.php" class="bg-white text-gray-900 px-8 py-3 rounded-full font-bold hover:bg-orange-500 hover:text-white transition">Cek Katalog →</a>
                        </div>
                    </div>
                    <div class="swiper-slide relative bg-gray-900">
                        <img src="https://images.unsplash.com/photo-1608198093002-ad4e005484ec?q=80&w=2072&auto=format&fit=crop" class="w-full h-full object-cover opacity-60">
                        <div class="absolute inset-0 flex flex-col justify-center items-start p-12 text-white">
                            <span class="bg-pink-600 px-3 py-1 rounded text-xs font-bold mb-4">NEW ARRIVAL</span>
                            <h2 class="text-3xl md:text-6xl font-black mb-4 leading-tight">Donat Bomboloni <br>Luber Isinya!</h2>
                            <p class="mb-8 max-w-lg text-gray-200">Hati-hati gigitan pertama bisa muncrat. Coklat Belgia asli.</p>
                            <a href="products.php?kategori=donat" class="bg-white text-gray-900 px-8 py-3 rounded-full font-bold hover:bg-pink-500 hover:text-white transition">Lihat Varian →</a>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section class="container mx-auto px-6 -mt-10 relative z-20 mb-20">
        <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 flex flex-wrap justify-around items-center gap-4">
            <a href="products.php?kategori=roti" class="flex flex-col items-center gap-2 group cursor-pointer w-20">
                <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center text-2xl group-hover:bg-orange-500 group-hover:text-white transition wiggle">🍞</div>
                <span class="text-xs font-bold text-gray-600">Roti</span>
            </a>
            <a href="products.php?kategori=cake" class="flex flex-col items-center gap-2 group cursor-pointer w-20">
                <div class="w-14 h-14 bg-pink-100 rounded-full flex items-center justify-center text-2xl group-hover:bg-pink-500 group-hover:text-white transition wiggle">🍰</div>
                <span class="text-xs font-bold text-gray-600">Cake</span>
            </a>
            <a href="products.php?kategori=donat" class="flex flex-col items-center gap-2 group cursor-pointer w-20">
                <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center text-2xl group-hover:bg-yellow-500 group-hover:text-white transition wiggle">🍩</div>
                <span class="text-xs font-bold text-gray-600">Donat</span>
            </a>
            <a href="products.php?kategori=pastry" class="flex flex-col items-center gap-2 group cursor-pointer w-20">
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center text-2xl group-hover:bg-purple-500 group-hover:text-white transition wiggle">🥐</div>
                <span class="text-xs font-bold text-gray-600">Pastry</span>
            </a>
            <div class="w-px h-10 bg-gray-200 hidden md:block"></div>
            <a href="products.php" class="flex flex-col items-center gap-2 group cursor-pointer w-20">
                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center text-2xl group-hover:bg-gray-800 group-hover:text-white transition wiggle">🔍</div>
                <span class="text-xs font-bold text-gray-600">Semua</span>
            </a>
        </div>
    </section>

    <section class="mb-20 bg-gray-900 py-16 relative overflow-hidden">
        <div class="absolute -top-10 right-10 float-monster hidden md:block">
            <svg width="180" height="180" viewBox="0 0 200 200">
                <g transform="rotate(180 100 100)">
                    <path d="M 50 150 Q 100 180 150 150 L 150 50 Q 100 20 50 50 Z" fill="#F97316"/>
                    <circle cx="80" cy="90" r="10" fill="white"/><circle cx="80" cy="90" r="3" fill="black"/>
                    <circle cx="120" cy="90" r="10" fill="white"/><circle cx="120" cy="90" r="3" fill="black"/>
                    <path d="M 90 130 Q 100 120 110 130" stroke="black" fill="none" stroke-width="3"/>
                </g>
            </svg>
        </div>

        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/3 text-white">
                    <span class="bg-red-600 text-white px-3 py-1 rounded text-xs font-bold uppercase tracking-widest animate-pulse">Flash Sale ⚡</span>
                    <h2 class="text-4xl font-black mt-4 mb-4">Diskon Kilat!</h2>
                    <p class="text-gray-400 mb-8">Berakhir dalam waktu:</p>
                    <div class="flex gap-3">
                        <div class="bg-gray-800 p-4 rounded-xl text-center min-w-[80px]">
                            <span class="block text-3xl font-bold text-orange-500" id="hours">02</span>
                            <span class="text-xs text-gray-500 uppercase">Jam</span>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl text-center min-w-[80px]">
                            <span class="block text-3xl font-bold text-orange-500" id="minutes">45</span>
                            <span class="text-xs text-gray-500 uppercase">Menit</span>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl text-center min-w-[80px]">
                            <span class="block text-3xl font-bold text-orange-500" id="seconds">12</span>
                            <span class="text-xs text-gray-500 uppercase">Detik</span>
                        </div>
                    </div>
                </div>

                <div class="md:w-2/3 w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php while($fs = mysqli_fetch_array($q_flash)): 
                            $harga_diskon = $fs['price']; 
                            $harga_asli = $fs['price'] * 1.5; // Pura-pura diskon 50%
                        ?>
                        <div class="bg-white p-4 rounded-2xl flex items-center gap-4 hover:scale-105 transition duration-300 group cursor-pointer" onclick="window.location='product_detail.php?id=<?= $fs['id'] ?>'">
                            <div class="relative w-28 h-28 flex-shrink-0">
                                <img src="assets/img/<?= $fs['image'] ?>" class="w-full h-full object-cover rounded-xl">
                                <span class="absolute top-0 left-0 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded-br-lg rounded-tl-lg">50% OFF</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 line-clamp-1 group-hover:text-orange-600 transition"><?= $fs['name'] ?></h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-red-500 font-black text-xl">Rp <?= number_format($harga_diskon) ?></span>
                                    <span class="text-gray-400 text-xs line-through">Rp <?= number_format($harga_asli) ?></span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 mt-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-red-500 to-orange-500 h-full" style="width: <?= rand(60, 95) ?>%"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <p class="text-[10px] text-red-500 font-bold">Segera Habis!</p>
                                    <p class="text-[10px] text-gray-400">Terjual <?= rand(10, 100) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container mx-auto px-6 mb-20">
        <div class="text-center mb-10" data-aos="fade-up">
            <h2 class="text-3xl font-black text-gray-800">Lagi Mood Apa? 🤔</h2>
            <p class="text-gray-500 mt-2">Biar Monster pilihin menu yang pas sama perasaanmu.</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            
            <a href="products.php?kategori=cake" class="group bg-pink-50 border border-pink-100 p-6 rounded-3xl text-center hover:shadow-xl hover:border-pink-300 transition duration-300 cursor-pointer">
                <div class="text-6xl mb-4 animate-bounce">💔</div>
                <h3 class="font-bold text-pink-700 text-lg">Lagi Galau</h3>
                <p class="text-xs text-pink-500 mt-1 font-medium">Obati pake Cake Coklat lumer.</p>
            </a>

            <a href="products.php?kategori=donat" class="group bg-yellow-50 border border-yellow-100 p-6 rounded-3xl text-center hover:shadow-xl hover:border-yellow-300 transition duration-300 cursor-pointer">
                <div class="text-6xl mb-4 animate-wiggle">🤩</div>
                <h3 class="font-bold text-yellow-700 text-lg">Happy Parah</h3>
                <p class="text-xs text-yellow-500 mt-1 font-medium">Party pake Donat warna-warni!</p>
            </a>

            <a href="products.php?kategori=roti" class="group bg-orange-50 border border-orange-100 p-6 rounded-3xl text-center hover:shadow-xl hover:border-orange-300 transition duration-300 cursor-pointer">
                <div class="text-6xl mb-4 animate-heartbeat">🤤</div>
                <h3 class="font-bold text-orange-700 text-lg">Laper Berat</h3>
                <p class="text-xs text-orange-500 mt-1 font-medium">Gas Roti Daging / Pizza.</p>
            </a>

            <a href="products.php?kategori=pastry" class="group bg-blue-50 border border-blue-100 p-6 rounded-3xl text-center hover:shadow-xl hover:border-blue-300 transition duration-300 cursor-pointer">
                <div class="text-6xl mb-4 animate-float">☕</div>
                <h3 class="font-bold text-blue-700 text-lg">Temen Ngopi</h3>
                <p class="text-xs text-blue-500 mt-1 font-medium">Croissant renyah paling pas.</p>
            </a>

        </div>
    </section>

    <section class="bg-gray-100 py-16 mb-20">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-black text-gray-800 mb-8">Paket Hemat 🎁</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition">
                    <img src="https://images.unsplash.com/photo-1550617931-e17a7b70dce2?q=80&w=800&auto=format&fit=crop" class="h-48 w-full object-cover">
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2">Breakfast Starter Pack</h3>
                        <p class="text-sm text-gray-500 mb-4">1 Roti Tawar + 1 Selai Srikaya + 2 Croissant.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-black text-orange-600">Rp 55.000</span>
                            <button onclick="alert('Fitur Bundle Coming Soon!')" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-bold">Add to Cart</button>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition">
                    <img src="https://images.unsplash.com/photo-1517433670267-08bbd4be890f?q=80&w=800&auto=format&fit=crop" class="h-48 w-full object-cover">
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2">Meeting Office Kit</h3>
                        <p class="text-sm text-gray-500 mb-4">1 Box Donat (Isi 12) + 5 Roti Asin.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-black text-orange-600">Rp 120.000</span>
                            <button onclick="alert('Fitur Bundle Coming Soon!')" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-bold">Add to Cart</button>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition">
                    <img src="https://images.unsplash.com/photo-1623334044303-241021148842?q=80&w=800&auto=format&fit=crop" class="h-48 w-full object-cover">
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2">Date Night Sweet</h3>
                        <p class="text-sm text-gray-500 mb-4">2 Slice Red Velvet + 2 Cold Brew.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-black text-orange-600">Rp 85.000</span>
                            <button onclick="alert('Fitur Bundle Coming Soon!')" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-bold">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container mx-auto px-6 mb-20">
        <h2 class="text-3xl font-black text-gray-800 text-center mb-10">Kata Mereka 🗣️</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="text-yellow-400 text-lg mb-2">★★★★★</div>
                <p class="text-gray-600 italic text-sm mb-4">"Gila sih, donatnya lembut banget kayak pipi bayi. Bakal langganan terus!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center font-bold text-orange-600">AD</div>
                    <div>
                        <p class="font-bold text-sm">Andi Firmansyah</p>
                        <p class="text-xs text-gray-400">Jakarta Selatan</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="text-yellow-400 text-lg mb-2">★★★★★</div>
                <p class="text-gray-600 italic text-sm mb-4">"Sourdough-nya juara! Persis kayak yang aku makan pas liburan di Prancis."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600">S</div>
                    <div>
                        <p class="font-bold text-sm">Sarah Amelia</p>
                        <p class="text-xs text-gray-400">Bandung</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="text-yellow-400 text-lg mb-2">★★★★☆</div>
                <p class="text-gray-600 italic text-sm mb-4">"Enak banget, tapi sayang cepet abis stoknya. Admin tolong restock cepet ya!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center font-bold text-purple-600">B</div>
                    <div>
                        <p class="font-bold text-sm">Budi Santoso</p>
                        <p class="text-xs text-gray-400">Depok</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container mx-auto px-6 mb-20">
        <div class="bg-orange-600 rounded-3xl p-10 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="relative z-10">
                <h2 class="text-3xl font-black text-white mb-2">Dapet Diskon 15%? 🤑</h2>
                <p class="text-orange-100 mb-6">Daftar newsletter sekarang, dapet voucher diskon langsung.</p>
                <form class="flex max-w-md mx-auto bg-white p-1 rounded-full shadow-lg">
                    <input type="email" class="flex-1 bg-transparent border-none focus:ring-0 px-4 text-sm" placeholder="Email kamu disini...">
                    <button type="button" onclick="Swal.fire('Hore!', 'Kamu udah terdaftar!', 'success')" class="bg-gray-900 text-white px-6 py-2 rounded-full text-sm font-bold hover:bg-gray-800">Gabung</button>
                </form>
            </div>
        </div>
    </section>

</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    AOS.init();

    // Swiper Config
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        pagination: { el: ".swiper-pagination", clickable: true },
        effect: "fade",
    });

    // Countdown Timer (2 Jam 45 Menit dari sekarang)
    function startTimer(duration) {
        var timer = duration, hours, minutes, seconds;
        setInterval(function () {
            hours = parseInt(timer / 3600, 10);
            minutes = parseInt((timer % 3600) / 60, 10);
            seconds = parseInt(timer % 60, 10);

            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            document.getElementById('hours').textContent = hours;
            document.getElementById('minutes').textContent = minutes;
            document.getElementById('seconds').textContent = seconds;

            if (--timer < 0) timer = duration;
        }, 1000);
    }
    startTimer(9900); // Mulai timer
</script>

<?php include 'layout/footer.php'; ?>