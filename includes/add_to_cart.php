<?php
session_start();
include '../includes/db.php';  // Adjust the path based on your folder structure

$user = $_SESSION['user_id'];
$pid = $_POST['product_id'];
$qty = $_POST['quantity'];

$check = $conn->query("SELECT * FROM cart WHERE user_id=$user AND product_id=$pid");
if ($check->num_rows > 0) {
    $conn->query("UPDATE cart SET quantity = quantity + $qty WHERE user_id=$user AND product_id=$pid");
} else {
    $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user, $pid, $qty)");
}
header("Location: ../user/cart.php");
