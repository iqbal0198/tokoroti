<?php
session_start();
include 'config/koneksi.php';

if (isset($_POST['submit_rating'])) {
    $user_id = $_SESSION['user_id'];
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Simpan ke DB
    $query = "INSERT INTO reviews (order_id, product_id, user_id, rating, comment) 
              VALUES ('$order_id', '$product_id', '$user_id', '$rating', '$comment')";
    
    if(mysqli_query($conn, $query)) {
        $_SESSION['info'] = [
            'type' => 'success',
            'title' => 'Makasih Reviewnya!',
            'message' => 'Masukan lo sangat berarti buat kami ⭐'
        ];
    } else {
        $_SESSION['info'] = [
            'type' => 'error',
            'title' => 'Gagal',
            'message' => 'Ada masalah pas nyimpen rating.'
        ];
    }
    
    header("Location: cart.php"); // Balik ke cart
    exit;
}
?>