<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $cartId = intval($_POST['id']);
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cartId, $userId);
    $success = $stmt->execute();

    echo json_encode(['status' => $success ? 'success' : 'error']);
    exit;
}
echo json_encode(['status' => 'error']);
