<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - BakeryPro Story</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #000; color: #fff; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #111; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        .text-gradient { background: linear-gradient(to right, #F97316, #EAB308); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="overflow-x-hidden">

    <nav class="w-full border-b border-white/10 bg-black/50 backdrop-blur-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="../landing.php" class="text-2xl font-black tracking-tighter flex items-center gap-2">
                BAKERY<span class="text-orange-500">PRO.</span>
            </a>
            <div class="flex gap-6 text-sm font-medium text-gray-400">
                <a href="../landing.php" class="hover:text-white transition">Home</a>
                <a href="career.php" class="hover:text-white transition">Career</a>
                <a href="../index.php" class="text-orange-500 hover:text-white transition">Store ↗</a>
            </div>
        </div>
    </nav>

    <section class="py-24 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-orange-600/20 rounded-full blur-[120px]"></div>
        
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-orange-500 font-bold tracking-[0.3em] text-sm uppercase mb-4" data-aos="fade-down">Our Philosophy</h2>
            <h1 class="text-5xl md:text-7xl font-black mb-8 leading-tight" data-aos="fade-up">
                WE BAKE WITH <br> <span class="text-gradient">SOUL & SCIENCE.</span>
            </h1>
            <p class="text-xl text-gray-400 max-w-3xl mx-auto leading-relaxed font-light" data-aos="fade-up" data-aos-delay="100">
                BakeryPro bukan sekadar toko roti. Ini adalah laboratorium rasa dimana tradisi Prancis bertemu dengan teknologi modern. Kami terobsesi dengan satu hal: **Kesempurnaan**.
            </p>
        </div>
    </section>

    <section class="pb-24">
        <div class="container mx-auto px-6">
            <div class="relative rounded-3xl overflow-hidden h-[500px]" data-aos="zoom-in">
                <img src="https://images.unsplash.com/photo-1517433670267-08bbd4be890f?q=80&w=2880&auto=format&fit=crop" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition duration-700 hover:scale-105">
                <div class="absolute bottom-0 left-0 w-full p-10 bg-gradient-to-t from-black to-transparent">
                    <h3 class="text-3xl font-bold">The Kitchen</h3>
                    <p class="text-gray-300">Dimana magis terjadi setiap jam 4 pagi.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 border-y border-white/10 bg-zinc-900/50">
        <div class="container mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div data-aos="fade-up" data-aos-delay="0">
                <div class="text-4xl md:text-5xl font-black text-white mb-2">2025</div>
                <div class="text-sm text-gray-500 uppercase tracking-widest">Est. Year</div>
            </div>
            <div data-aos="fade-up" data-aos-delay="100">
                <div class="text-4xl md:text-5xl font-black text-orange-500 mb-2">48h</div>
                <div class="text-sm text-gray-500 uppercase tracking-widest">Fermentation</div>
            </div>
            <div data-aos="fade-up" data-aos-delay="200">
                <div class="text-4xl md:text-5xl font-black text-white mb-2">150+</div>
                <div class="text-sm text-gray-500 uppercase tracking-widest">Secret Recipes</div>
            </div>
            <div data-aos="fade-up" data-aos-delay="300">
                <div class="text-4xl md:text-5xl font-black text-orange-500 mb-2">10k+</div>
                <div class="text-sm text-gray-500 uppercase tracking-widest">Happy Monsters</div>
            </div>
        </div>
    </section>

    <section class="py-24">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-black mb-12 text-center">Meet the Creators</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="group bg-zinc-900 rounded-2xl p-6 border border-zinc-800 hover:border-orange-500/50 transition duration-300" data-aos="fade-up">
                    <div class="h-64 rounded-xl bg-gray-800 mb-6 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1583394293214-28ded15ee548?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-500">
                    </div>
                    <h3 class="text-xl font-bold">Chef Junaedi</h3>
                    <p class="text-orange-500 text-sm mb-4">Head Baker</p>
                    <p class="text-gray-400 text-sm">"Roti itu seperti cinta, butuh kesabaran dan suhu yang tepat."</p>
                </div>

                <div class="group bg-zinc-900 rounded-2xl p-6 border border-zinc-800 hover:border-orange-500/50 transition duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="h-64 rounded-xl bg-gray-800 mb-6 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1595273670150-bd0c3c392e46?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-500">
                    </div>
                    <h3 class="text-xl font-bold">Sarah Dough</h3>
                    <p class="text-orange-500 text-sm mb-4">Pastry Artist</p>
                    <p class="text-gray-400 text-sm">Spesialis lapisan croissant. Bisa membedakan butter Prancis dan lokal dari aromanya.</p>
                </div>

                <div class="group bg-zinc-900 rounded-2xl p-6 border border-zinc-800 hover:border-orange-500/50 transition duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="h-64 rounded-xl bg-orange-900/20 mb-6 flex items-center justify-center relative overflow-hidden">
                        <svg width="120" height="120" viewBox="0 0 200 200" class="group-hover:scale-110 transition duration-300">
                            <g transform="translate(60, 50)">
                                <path d="M 0 40 Q 0 0 40 0 Q 80 0 80 40 L 80 80 Q 80 90 70 90 L 10 90 Q 0 90 0 80 Z" fill="#F97316"/>
                                <g transform="translate(40, 35)">
                                    <circle cx="-15" cy="0" r="8" fill="white"/><circle cx="-15" cy="0" r="3" fill="#1F2937"/>
                                    <circle cx="15" cy="0" r="8" fill="white"/><circle cx="15" cy="0" r="3" fill="#1F2937"/>
                                    <path d="M -10 15 Q 0 25 10 15" fill="none" stroke="#7C2D12" stroke-width="3" stroke-linecap="round"/>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold">Mr. Chibi</h3>
                    <p class="text-orange-500 text-sm mb-4">Chief Tasting Officer</p>
                    <p class="text-gray-400 text-sm">Bekerja demi bayaran roti. Sangat kritis terhadap donat yang kurang manis.</p>
                </div>

            </div>
        </div>
    </section>

    <footer class="border-t border-white/10 py-8 text-center text-gray-600 text-sm">
        <p>&copy; 2025 BakeryPro. All rights reserved.</p>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script> AOS.init({ duration: 800, once: true }); </script>
</body>
</html>