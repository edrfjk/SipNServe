<?php
// Redesigned Orders Page (orders.php)

session_start();
include '../includes/db.php';
if (!isset($_SESSION['admin'])) header("Location: ../login.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $_POST['status'];

    // Validate status
    $allowedStatuses = ['Pending', 'Confirmed', 'Cancelled'];
    if (in_array($newStatus, $allowedStatuses)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $orderId);
        $stmt->execute();
    }

    // Refresh to avoid form resubmission
    header("Location: orders.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $orderId = intval($_GET['delete']);
    $conn->query("DELETE FROM orders WHERE id = $orderId");
    header("Location: orders.php");
    exit;
}



$orders = $conn->query("SELECT o.*, u.username, p.name, p.image 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    JOIN products p ON o.product_id = p.id 
    ORDER BY o.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Orders</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp" />
  <link rel="stylesheet" href="orders.css">
</head>
<body>
<div class="container">
  <!-- SIDEBAR -->
  <aside>
    <div class="sidebar">
    <div class="top">
      <div class="logo">
      <h2>SipNServe <span class="danger">PANEL</span></h2>
      </div>
      <div class="close" id="close_btn">
        <span class="material-symbols-sharp">close</span>
      </div>
    </div>
    <a href="dashboard.php">
        <span class="material-symbols-sharp">grid_view</span>
        <h3>Dashboard</h3>
      </a>
      <a href="users.php">
        <span class="material-symbols-sharp">person_outline</span>
        <h3>Users</h3>
      </a>
      <a href="products.php">
        <span class="material-symbols-sharp">receipt_long</span>
        <h3>Products</h3>
      </a>
      <a href="orders.php" class="active">
        <span class="material-symbols-sharp">shopping_cart</span>
        <h3>Orders</h3>
      </a>
      <a href="../logout.php">
        <span class="material-symbols-sharp">logout</span>
        <h3>Logout</h3>
      </a>
    </div>
  </aside>

  <main>
    <h1>Manage Orders</h1>

    <div class="recent_order">
      <h2>All Orders</h2>
      <table>
        <thead>
          <tr>
            <th>User</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($o = $orders->fetch_assoc()): ?>
          <tr>
            <td><?= $o['username'] ?></td>
            <td><img src="../assets/images/products/<?= $o['image'] ?>" width="40"> <?= $o['name'] ?></td>
            <td><?= $o['quantity'] ?></td>
            <td>₱<?= number_format($o['total_price'], 2) ?></td>
            
                      <td colspan="2">
              <form method="post" action="orders.php" style="display: flex; align-items: center; gap: 8px;">
                  <input type="hidden" name="order_id" value="<?= $o['id'] ?>">

                  <select name="status" 
                      class="status-dropdown <?= strtolower($o['status']) ?>-option" 
                      onchange="this.form.submit()">
                      <option value="Pending" <?= $o['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                      <option value="Confirmed" <?= $o['status'] === 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                      <option value="Cancelled" <?= $o['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                  </select>

                  <a href="?delete=<?= $o['id'] ?>" 
                    class="btn danger-btn action-btn" 
                    onclick="return confirm('Are you sure you want to delete this order?');">
                    🗑 Delete
                  </a>
              </form>

          </td>

          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </main>
</div>
<script src="../assets/js/script.js">
document.querySelectorAll('.status-dropdown').forEach(dropdown => {
    dropdown.addEventListener('change', function() {
        const value = this.value.toLowerCase();
        this.classList.remove('pending-option', 'confirmed-option', 'cancelled-option');
        this.classList.add(value + '-option');
    });
});

</script>
</body>
</html>
