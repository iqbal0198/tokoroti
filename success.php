<?php
include 'layout/header.php';
// Ambil ID order buat ditampilin (opsional)
$id_order = isset($_GET['id']) ? $_GET['id'] : '';
?>

<div class="min-h-[60vh] flex flex-col items-center justify-center text-center px-4">
    <div class="bg-green-100 text-green-600 p-6 rounded-full mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>
    
    <h1 class="text-4xl font-bold text-gray-800 mb-2">Order Berhasil! 🎉</h1>
    <p class="text-gray-600 mb-8 max-w-md">
        Makasih udah belanja bre. Pesanan lo dengan ID <span class="font-bold">#<?= $id_order ?></span> lagi diproses sama tim dapur kami.
    </p>

    <div class="space-x-4">
        <a href="index.php" class="text-gray-600 hover:text-orange-500 font-semibold">Kembali ke Home</a>
        <a href="my-orders.php" class="bg-orange-600 text-white px-6 py-3 rounded-full hover:bg-orange-700 transition shadow-lg">
            Lihat Pesanan Saya
        </a>
    </div>
</div>

<?php include 'layout/footer.php'; ?>