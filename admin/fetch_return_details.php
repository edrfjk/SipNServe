<?php
include '../includes/db.php';

if (!isset($_GET['id'])) exit;
$order_id = intval($_GET['id']);

$order = $conn->query("
    SELECT o.*, u.username, p.name, p.image 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    JOIN products p ON o.product_id = p.id 
    WHERE o.id = $order_id
")->fetch_assoc();

if (!$order) exit;

// Start output
echo '<div style="font-family:Arial,sans-serif; color:#333;">';
echo '<h2 style="text-align:center; color:#4caf50; margin-bottom:15px;">Order Details</h2>';

echo '<div style="display:flex; gap:15px; margin-bottom:15px;">';
echo '<div style="flex-shrink:0;"><img src="../assets/images/products/'.$order['image'].'" alt="'.$order['name'].'" style="width:100px; border-radius:6px; object-fit:cover;"></div>';
echo '<div style="flex-grow:1;">';
echo '<p><strong>Order ID:</strong> '.$order['id'].'</p>';
echo '<p><strong>User:</strong> '.$order['username'].'</p>';
echo '<p><strong>Product:</strong> '.$order['name'].'</p>';
echo '<p><strong>Quantity:</strong> '.$order['quantity'].'</p>';
echo '<p><strong>Size:</strong> '.$order['size'].'</p>';
echo '<p><strong>Total Price:</strong> ₱'.number_format($order['total_price'],2).'</p>';
echo '<p><strong>Payment Method:</strong> '.$order['payment_method'].'</p>';
echo '<p><strong>Date Ordered:</strong> '.$order['created_at'].'</p>';
echo '<p><strong>Status:</strong> '.$order['status'].'</p>';
echo '</div>';
echo '</div>';

echo '</div>';
?>
