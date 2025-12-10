<?php
session_start();
include 'config/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeryPro - The Art of Baking</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #000; color: #fff; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #111; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        
        /* Glass Navbar Effect */
        .glass {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        /* Text Gradient */
        .text-gradient {
            background: linear-gradient(to right, #F97316, #EAB308);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Hide Video Controls */
        video::-webkit-media-controls { display: none !important; }
    </style>
</head>
<body class="overflow-x-hidden">

    <nav class="fixed top-0 w-full z-50 glass transition-all duration-300" id="navbar">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="landing.php" class="text-2xl font-black tracking-tighter flex items-center gap-2">
                BAKERY<span class="text-orange-500">PRO.</span>
            </a>

            <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-gray-300">
                <a href="#home" class="hover:text-white transition">Overview</a>
                
                <a href="company/about.php" class="hover:text-white transition group relative">
                    About Us
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-orange-500 transition-all group-hover:w-full"></span>
                </a>
                
                <a href="company/career.php" class="hover:text-orange-500 transition flex items-center gap-1">
                    Career <span class="bg-orange-600/20 text-orange-500 border border-orange-500/50 text-[10px] px-1.5 py-0.5 rounded ml-1">Hiring</span>
                </a>
                
                <a href="index.php" class="hover:text-white transition">Store ↗</a>
            </div>

            <div class="flex items-center gap-4">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <span class="hidden md:block text-sm text-gray-400">Hi, <?= explode(" ", $_SESSION['name'])[0] ?></span>
                    <a href="index.php" class="bg-white text-black px-6 py-2 rounded-full text-sm font-bold hover:bg-gray-200 transition transform hover:scale-105">
                        Shop Now
                    </a>
                <?php else: ?>
                    <a href="login.php" class="text-sm font-bold text-white hover:text-orange-500">Login</a>
                    <a href="register.php" class="border border-white/30 text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-white hover:text-black transition">
                        Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <section id="home" class="relative h-screen w-full flex items-center justify-center overflow-hidden">
        <video autoplay muted loop playsinline class="absolute w-full h-full object-cover opacity-60 scale-105">
            <source src="https://videos.pexels.com/video-files/4109869/4109869-uhd_2560_1440_25fps.mp4" type="video/mp4">
        </video>
        
        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black/80"></div>

        <div class="relative z-10 text-center px-4 mt-16">
            <div class="inline-block border border-white/20 bg-white/5 backdrop-blur-md rounded-full px-4 py-1 mb-6" data-aos="fade-down">
                <span class="text-orange-400 text-xs font-bold tracking-widest uppercase">Est. 2025 • Jakarta</span>
            </div>
            <h1 class="text-6xl md:text-8xl font-black tracking-tighter mb-6 leading-none" data-aos="fade-up">
                BEYOND <br> <span class="text-gradient">TASTE.</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto font-light leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                Mendefinisikan ulang seni membuat roti. 100% ragi alami, fermentasi 48 jam, dan dedikasi penuh di setiap gigitan.
            </p>
            
            <div class="flex flex-col md:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="400">
                <a href="index.php" class="bg-orange-600 text-white px-10 py-4 rounded-full text-lg font-bold hover:bg-orange-700 hover:shadow-lg hover:shadow-orange-600/30 transition duration-300">
                    Masuk Marketplace
                </a>
                <a href="company/about.php" class="border border-white/30 backdrop-blur-sm text-white px-10 py-4 rounded-full text-lg font-bold hover:bg-white hover:text-black transition duration-300">
                    Explore Story
                </a>
            </div>
        </div>
        
        <div class="absolute bottom-10 animate-bounce text-gray-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
        </div>
    </section>

    <section class="py-32 bg-black relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-orange-600/10 rounded-full blur-[100px]"></div>

        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-20">
                <div class="md:w-1/2 relative" data-aos="fade-right">
                    <div class="absolute -inset-4 bg-gradient-to-r from-orange-600 to-purple-600 rounded-2xl blur-lg opacity-30"></div>
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=2072&auto=format&fit=crop" 
                         class="relative rounded-2xl shadow-2xl grayscale hover:grayscale-0 transition duration-700 transform hover:scale-[1.02] cursor-pointer">
                </div>
                <div class="md:w-1/2" data-aos="fade-left">
                    <h2 class="text-orange-500 font-bold tracking-widest uppercase mb-4 text-sm">Our Philosophy</h2>
                    <h3 class="text-4xl md:text-5xl font-black mb-6 leading-tight text-white">Bukan Sekadar Tepung & Air.</h3>
                    <p class="text-gray-400 text-lg leading-relaxed mb-6">
                        Kami percaya bahwa roti yang hebat membutuhkan waktu. Tidak ada jalan pintas. Adonan kami beristirahat lebih lama daripada Anda tidur, untuk menghasilkan tekstur yang sempurna.
                    </p>
                    <a href="company/about.php" class="inline-flex items-center text-white border-b border-orange-500 pb-1 hover:text-orange-500 transition font-bold">
                        Baca Selengkapnya tentang Kami →
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="relative py-32 flex items-center justify-center bg-fixed bg-center bg-cover" 
             style="background-image: url('https://images.unsplash.com/photo-1550617931-e17a7b70dce2?q=80&w=2070&auto=format&fit=crop');">
        <div class="absolute inset-0 bg-black/80"></div>
        
        <div class="relative z-10 text-center px-6 container mx-auto">
            <h2 class="text-4xl md:text-6xl font-black mb-12 text-white" data-aos="fade-up">PREMIUM INGREDIENTS.</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="bg-white/5 backdrop-blur-md p-10 rounded-3xl border border-white/10 hover:border-orange-500/50 transition duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-5xl mb-6">🌾</div>
                    <h4 class="text-xl font-bold mb-3 text-white">Japanese Flour</h4>
                    <p class="text-sm text-gray-400 leading-relaxed">Tepung komachi premium untuk tekstur roti yang super lembut dan *moist* tahan lama.</p>
                </div>

                <div class="bg-white/5 backdrop-blur-md p-10 rounded-3xl border border-white/10 hover:border-orange-500/50 transition duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-5xl mb-6">🧈</div>
                    <h4 class="text-xl font-bold mb-3 text-white">French Butter</h4>
                    <p class="text-sm text-gray-400 leading-relaxed">Butter Elle & Vire asli Prancis memberikan aroma wangi yang tidak bisa ditiru margarin biasa.</p>
                </div>

                <div class="bg-white/5 backdrop-blur-md p-10 rounded-3xl border border-white/10 hover:border-orange-500/50 transition duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-5xl mb-6">🍯</div>
                    <h4 class="text-xl font-bold mb-3 text-white">Natural Yeast</h4>
                    <p class="text-sm text-gray-400 leading-relaxed">Ragi alami tanpa bahan pengawet kimia. Lebih sehat dan aman untuk lambung.</p>
                </div>

            </div>
        </div>
    </section>

    <section class="py-24 bg-zinc-900 border-t border-zinc-800">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12" data-aos="fade-down">
                <div>
                    <h2 class="text-3xl md:text-5xl font-black text-white">Favorite Picks 🔥</h2>
                    <p class="text-gray-500 mt-2">Menu yang paling sering dicari monster lapar.</p>
                </div>
                <a href="products.php" class="hidden md:block text-orange-500 font-bold hover:text-white transition border-b border-orange-500 pb-1 mt-4 md:mt-0">
                    Lihat Semua Katalog →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                // Ambil 3 produk random buat teaser
                $q_teaser = mysqli_query($conn, "SELECT * FROM products ORDER BY RAND() LIMIT 3");
                while($t = mysqli_fetch_array($q_teaser)):
                ?>
                <div class="group relative overflow-hidden rounded-2xl bg-black border border-gray-800 hover:border-orange-500/50 transition duration-500" data-aos="fade-up">
                    <div class="h-80 overflow-hidden">
                        <img src="assets/img/<?= $t['image'] ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110 opacity-70 group-hover:opacity-100">
                    </div>
                    <div class="absolute bottom-0 left-0 w-full p-8 bg-gradient-to-t from-black via-black/80 to-transparent">
                        <h3 class="text-2xl font-bold mb-2 text-white"><?= $t['name'] ?></h3>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-500 font-bold text-lg">Rp <?= number_format($t['price']) ?></span>
                            <a href="product_detail.php?id=<?= $t['id'] ?>" class="bg-white text-black w-10 h-10 flex items-center justify-center rounded-full hover:bg-orange-500 hover:text-white transition">
                                ↗
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <div class="mt-12 text-center md:hidden">
                <a href="products.php" class="bg-gray-800 text-white px-8 py-3 rounded-full font-bold">Buka Katalog</a>
            </div>
        </div>
    </section>

    <footer class="bg-black border-t border-gray-900 pt-20 pb-10">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-1">
                    <h2 class="text-2xl font-black mb-4 text-white">BAKERY<span class="text-orange-500">PRO.</span></h2>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Toko roti masa depan dengan sentuhan teknologi dan hati. Dibuat di Jakarta, dinikmati di mana saja.
                    </p>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4">Company</h3>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="company/about.php" class="hover:text-orange-500 transition">About Us</a></li>
                        <li><a href="company/career.php" class="hover:text-orange-500 transition">Career</a></li>
                        <li><a href="admin/login.php" class="hover:text-orange-500 transition">Admin Portal</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4">Shop</h3>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="products.php" class="hover:text-orange-500 transition">All Products</a></li>
                        <li><a href="products.php?kategori=cake" class="hover:text-orange-500 transition">Signature Cakes</a></li>
                        <li><a href="index.php" class="hover:text-orange-500 transition">Marketplace</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4">Lokasi</h3>
                    <div class="rounded-xl overflow-hidden h-32 border border-gray-800 grayscale hover:grayscale-0 transition duration-500 opacity-70 hover:opacity-100">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0515646130454!2d106.82522431476932!3d-6.256938995471012!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3d32fe5270d%3A0x2e06173775432316!2sKemang%20Village!5e0!3m2!1sen!2sid!4v1633000000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-900 pt-8 text-center text-gray-600 text-xs">
                © 2025 BakeryPro Project. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: false });
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) { navbar.classList.add('shadow-lg'); } 
            else { navbar.classList.remove('shadow-lg'); }
        });
    </script>
</body>
</html>