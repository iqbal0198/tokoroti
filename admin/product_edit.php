<?php
session_start();
include '../config/koneksi.php';

// Cek Login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil ID Produk dari URL
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
$data = mysqli_fetch_array($query);

// Logic Update Data
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $gambar_lama = $_POST['gambar_lama'];

    // Cek ada upload gambar baru gak?
    if ($_FILES['image']['error'] === 4) {
        $gambar = $gambar_lama; // Pake gambar lama
    } else {
        // Upload gambar baru
        $filename = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $gambar = uniqid() . "." . $ext;
        
        move_uploaded_file($tmp_name, '../assets/img/' . $gambar);
        
        // Hapus gambar lama biar gak nyampah (kecuali dummy)
        if($gambar_lama != 'dummy.jpg' && file_exists('../assets/img/'.$gambar_lama)){
            unlink('../assets/img/'.$gambar_lama);
        }
    }

    $update = mysqli_query($conn, "UPDATE products SET 
        name='$name', 
        category_id='$category_id', 
        price='$price', 
        stock='$stock', 
        description='$description', 
        image='$gambar' 
        WHERE id='$id'");

    if ($update) {
        echo "<script>alert('Produk Berhasil Diupdate!'); window.location='products.php';</script>";
    } else {
        echo "<script>alert('Gagal Update!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - BakeryPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }</style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden">
        <div class="bg-orange-600 p-6 flex justify-between items-center">
            <h2 class="text-white font-bold text-xl">Edit Produk</h2>
            <a href="products.php" class="text-orange-200 hover:text-white text-sm">Kembali</a>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            <input type="hidden" name="gambar_lama" value="<?= $data['image'] ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-600 text-sm font-bold mb-2">Nama Produk</label>
                    <input type="text" name="name" value="<?= $data['name'] ?>" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-orange-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm font-bold mb-2">Kategori</label>
                    <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-orange-500 focus:outline-none">
                        <?php
                        $cats = mysqli_query($conn, "SELECT * FROM categories");
                        while($c = mysqli_fetch_array($cats)):
                        ?>
                            <option value="<?= $c['id'] ?>" <?= ($data['category_id'] == $c['id']) ? 'selected' : '' ?>>
                                <?= $c['name'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-600 text-sm font-bold mb-2">Harga (Rp)</label>
                    <input type="number" name="price" value="<?= $data['price'] ?>" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-orange-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm font-bold mb-2">Stok</label>
                    <input type="number" name="stock" value="<?= $data['stock'] ?>" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-orange-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-gray-600 text-sm font-bold mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-orange-500 focus:outline-none"><?= $data['description'] ?></textarea>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-24 h-24 rounded-lg border border-gray-200 overflow-hidden flex-shrink-0">
                    <img src="../assets/img/<?= $data['image'] ?>" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Ganti Foto (Opsional)</label>
                    <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                    <p class="text-xs text-gray-400 mt-1">*Biarkan kosong kalau gak mau ganti gambar.</p>
                </div>
            </div>

            <button type="submit" name="update" class="w-full bg-orange-600 text-white font-bold py-3 rounded-xl hover:bg-orange-700 transition shadow-lg">
                Simpan Perubahan ✅
            </button>
        </form>
    </div>

</body>
</html>