<?php
session_start();
include '../config/koneksi.php';

// Cek Login Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Logic Simpan Produk
if (isset($_POST['simpan'])) {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    // Upload Gambar
    $filename = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $type1 = explode('.', $filename);
    $type2 = $type1[1];
    $newname = 'produk' . time() . '.' . $type2;
    $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif');

    if (!in_array($type2, $tipe_diizinkan)) {
        echo '<script>alert("Format gambar tidak diizinkan")</script>';
    } else {
        move_uploaded_file($tmp_name, '../assets/img/' . $newname);
        
        $insert = mysqli_query($conn, "INSERT INTO products (name, category_id, price, description, image, stock) VALUES ('$name', '$category_id', '$price', '$description', '$newname', '$stock')");

        if ($insert) {
            echo '<script>alert("Simpan data berhasil"); window.location="products.php"</script>';
        } else {
            echo '<script>alert("Gagal simpan data")</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - BakeryPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }</style>
</head>
<body class="flex h-screen overflow-hidden text-gray-800">

    <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0 hidden md:flex flex-col">
        <div class="p-6 flex items-center gap-3">
            <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center font-bold text-white shadow-lg">B</div>
            <span class="text-xl font-bold tracking-tight text-gray-900">BakeryPro.</span>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <p class="text-xs text-gray-400 font-bold uppercase px-2 mb-2">Menu Utama</p>
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium"><i class="fas fa-home w-5"></i> Dashboard</a>
            <a href="products.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium"><i class="fas fa-box-open w-5"></i> Kelola Produk</a>
            <a href="product_add.php" class="flex items-center gap-3 px-4 py-3 bg-orange-50 text-orange-700 rounded-xl font-bold transition"><i class="fas fa-plus-circle w-5"></i> Tambah Baru</a>
            <a href="orders.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium"><i class="fas fa-shopping-bag w-5"></i> Pesanan</a>
            <a href="users.php" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition font-medium"><i class="fas fa-users w-5"></i> Pelanggan</a>
        </nav>
        <div class="p-4 border-t border-gray-100">
            <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition font-bold"><i class="fas fa-sign-out-alt w-5"></i> Keluar</a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white/80 backdrop-blur-md border-b border-gray-200 h-20 flex items-center justify-between px-8 sticky top-0 z-30">
            <h2 class="text-xl font-bold text-gray-800">Form Produk Baru</h2>
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center font-bold text-orange-600">A</div>
        </header>

        <div class="p-8 flex justify-center">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm w-full max-w-3xl overflow-hidden">
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Detail Produk</h3>
                    <a href="products.php" class="text-sm text-gray-500 hover:text-orange-600">← Kembali</a>
                </div>
                
                <form action="" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Roti</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 transition" placeholder="Contoh: Roti Abon Sapi">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                            <select name="category_id" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                                <option value="">-- Pilih Kategori --</option>
                                <?php
                                $kategori = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
                                while($k = mysqli_fetch_array($kategori)){
                                ?>
                                    <option value="<?php echo $k['id'] ?>"><?php echo $k['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp)</label>
                            <input type="number" name="price" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 transition" placeholder="15000">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Stok Awal</label>
                            <input type="number" name="stock" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 transition" placeholder="10">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Produk</label>
                        <textarea name="description" rows="4" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 transition" placeholder="Jelaskan rasa dan tekstur roti ini..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Foto Produk</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col w-full h-32 border-2 border-dashed border-gray-300 rounded-xl hover:bg-gray-50 hover:border-orange-400 transition cursor-pointer">
                                <div class="flex flex-col items-center justify-center pt-7">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-500 font-medium">Klik untuk upload foto</p>
                                </div>
                                <input type="file" name="image" class="opacity-0" required>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" name="simpan" class="w-full bg-orange-600 text-white font-bold py-4 rounded-xl hover:bg-orange-700 transition shadow-lg shadow-orange-200">
                            Simpan Produk Baru
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>
</body>
</html>