<?php
session_start();
include '../includes/db.php';

$user = $_SESSION['user_id'];
$pid = $_POST['product_id'];
$size = $_POST['size']; // get selected size
$price = $_POST['selected_price']; // selected price from hidden input
$qty = $_POST['quantity'];

// Check if product with same size already exists in cart
$check = $conn->prepare("SELECT * FROM cart WHERE user_id=? AND product_id=? AND size=?");
$check->bind_param("iis", $user, $pid, $size);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    $update = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id=? AND product_id=? AND size=?");
    $update->bind_param("iiis", $qty, $user, $pid, $size);
    $update->execute();
} else {
    $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, size, price, quantity) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("iisdi", $user, $pid, $size, $price, $qty);
    $insert->execute();
}

header("Location: ../user/cart.php");
exit;
?>
