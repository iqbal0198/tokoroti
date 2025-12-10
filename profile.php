<?php
session_start();
include 'config/koneksi.php'; // Pastiin ini nyambung ke db

// 1. CEK LOGIN
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 2. AMBIL DATA REAL DARI DATABASE
// Biar kalo diedit, datanya langsung berubah tanpa logout login
$uid = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$uid'");
$user = mysqli_fetch_array($query);

// Fallback data kalo user belum lengkap
$nama_lengkap = $user['name'] ?? 'User Tanpa Nama';
$email_user   = $user['email'] ?? 'email@kosong.com';
$no_hp        = $user['phone'] ?? ''; 
$alamat       = $user['address'] ?? ''; 
$role         = $user['role'] ?? 'user';
$created_at   = $user['created_at'] ?? date('Y-m-d');

// Simulasi Avatar
$avatar_letter = strtoupper(substr($nama_lengkap, 0, 1));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - BakeryShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <?php include 'layout/header.php'; ?>

    <div class="container mx-auto px-6 py-10 max-w-6xl">
        
        <div class="mb-8 flex items-center text-sm text-gray-500">
            <a href="index.php" class="hover:text-orange-600">Home</a>
            <span class="mx-2">/</span>
            <span class="text-orange-600 font-bold">Profil Saya</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center sticky top-32">
                    
                    <div class="relative w-32 h-32 mx-auto mb-6">
                        <div class="w-full h-full rounded-full bg-gradient-to-tr from-orange-100 to-orange-200 flex items-center justify-center text-5xl font-bold text-orange-600 border-4 border-white shadow-lg">
                            <?= $avatar_letter ?>
                        </div>
                        <button class="absolute bottom-0 right-0 bg-gray-900 text-white p-2 rounded-full hover:bg-orange-600 transition shadow-md" title="Ganti Foto">
                            <i class="fas fa-camera text-sm"></i>
                        </button>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-1"><?= $nama_lengkap ?></h2>
                    <p class="text-gray-400 text-sm mb-4"><?= $email_user ?></p>

                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-orange-50 text-orange-700 text-xs font-bold uppercase tracking-wider mb-6">
                        <i class="fas fa-crown"></i> <?= $role ?>
                    </div>

                    <div class="border-t border-gray-100 pt-6 flex justify-between text-sm text-gray-500">
                        <div class="text-center w-1/2 border-r border-gray-100">
                            <p class="font-bold text-gray-900 text-lg">0</p>
                            <p>Pesanan</p>
                        </div>
                        <div class="text-center w-1/2">
                            <p class="font-bold text-gray-900 text-lg"><?= date('M Y', strtotime($created_at)) ?></p>
                            <p>Bergabung</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xl font-bold flex items-center gap-3">
                            <i class="fas fa-user-edit text-orange-600"></i> Edit Biodata
                        </h3>
                        <button onclick="toggleEdit()" id="btn-edit" class="text-sm font-bold text-orange-600 hover:text-orange-700 border border-orange-200 px-4 py-2 rounded-lg hover:bg-orange-50 transition">
                            <i class="fas fa-pencil-alt mr-1"></i> Ubah Data
                        </button>
                    </div>

                    <form action="proses_update_profil.php" method="POST">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            
                            <div class="group">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-user"></i></span>
                                    <input type="text" name="nama" value="<?= $nama_lengkap ?>" class="input-field w-full pl-10 pr-4 py-3 rounded-xl bg-gray-50 border-transparent focus:bg-white focus:border-orange-500 transition outline-none font-medium text-gray-600" readonly>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-envelope"></i></span>
                                    <input type="email" value="<?= $email_user ?>" class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-100 border-transparent text-gray-400 cursor-not-allowed font-medium" readonly title="Email tidak dapat diubah">
                                </div>
                                <p class="text-[10px] text-red-400 mt-1 italic">*Email permanen</p>
                            </div>

                            <div class="group">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nomor WhatsApp</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fab fa-whatsapp"></i></span>
                                    <input type="text" name="nohp" value="<?= $no_hp ?>" placeholder="0812..." class="input-field w-full pl-10 pr-4 py-3 rounded-xl bg-gray-50 border-transparent focus:bg-white focus:border-orange-500 transition outline-none font-medium text-gray-600" readonly>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Password</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-lock"></i></span>
                                    <input type="password" value="********" class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-50 border-transparent transition outline-none font-medium text-gray-600" readonly>
                                </div>
                            </div>

                        </div>

                        <div class="mb-8">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Alamat Pengiriman</label>
                            <div class="relative">
                                <span class="absolute top-3 left-3 text-gray-400"><i class="fas fa-map-marker-alt"></i></span>
                                <textarea name="alamat" rows="3" class="input-field w-full pl-10 pr-4 py-3 rounded-xl bg-gray-50 border-transparent focus:bg-white focus:border-orange-500 transition outline-none font-medium text-gray-600 resize-none" readonly><?= $alamat ?></textarea>
                            </div>
                        </div>

                        <div id="action-buttons" class="hidden flex items-center justify-end gap-4">
                            <button type="button" onclick="toggleEdit()" class="px-6 py-3 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-100 transition">Batal</button>
                            <button type="submit" class="px-8 py-3 rounded-xl text-sm font-bold bg-gray-900 text-white hover:bg-orange-600 shadow-lg hover:shadow-orange-500/30 transition transform hover:-translate-y-1">Simpan</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleEdit() {
            const inputs = document.querySelectorAll('.input-field');
            const actionButtons = document.getElementById('action-buttons');
            const btnEdit = document.getElementById('btn-edit');
            const isReadonly = inputs[0].hasAttribute('readonly');

            if (isReadonly) {
                inputs.forEach(input => {
                    input.removeAttribute('readonly');
                    input.classList.remove('bg-gray-50');
                    input.classList.add('bg-white', 'border-gray-200', 'border');
                });
                actionButtons.classList.remove('hidden');
                btnEdit.classList.add('hidden');
                inputs[0].focus();
            } else {
                inputs.forEach(input => {
                    input.setAttribute('readonly', true);
                    input.classList.add('bg-gray-50');
                    input.classList.remove('bg-white', 'border-gray-200', 'border');
                });
                actionButtons.classList.add('hidden');
                btnEdit.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>