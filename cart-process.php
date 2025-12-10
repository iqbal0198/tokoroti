<?php
session_start();

// ... session_start() di atas ...

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // --- BAGIAN INI KITA UBAH ---
    // Jangan echo script alert, tapi simpen ke session info
    $_SESSION['info'] = [
        'type' => 'success',
        'title' => 'Mantap!',
        'message' => 'Roti berhasil masuk keranjang 🛒'
    ];

    header("Location: cart.php");
    exit;
}

// 2. HAPUS ITEM DARI KERANJANG
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    unset($_SESSION['cart'][$id]); // Hapus session array index ke-ID
    header("Location: cart.php");
}

// 3. UPDATE JUMLAH (Kalo user ganti angka di keranjang)
if (isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $id => $jumlah) {
        if($jumlah == 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $jumlah;
        }
    }
    header("Location: cart.php");
}
?>