<?php
// Redesigned Orders Page (orders.php)

session_start();
include '../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $_POST['status'];

    // Allowed statuses
    $allowedStatuses = ['Pending', 'Confirmed', 'Completed', 'Cancelled', 'Return Requested', 'Return Approved', 'Return Rejected'];

    if (in_array($newStatus, $allowedStatuses)) {
        // Determine delivery_status for return actions
        $deliveryStatus = $newStatus;
        if ($newStatus === 'Return Rejected') $deliveryStatus = 'Delivered';

        $stmt = $conn->prepare("UPDATE orders SET status = ?, delivery_status = ? WHERE id = ?");
        $stmt->bind_param("ssi", $newStatus, $deliveryStatus, $orderId);
        $stmt->execute();
    }

    header("Location: orders.php");
    exit;
}

if (!isset($_SESSION['admin'])) header("Location: ../login.php");

// Handle Status Update
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


// Handle Return Approval/Rejection
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['approve_return'], $_POST['order_id'])) {
        $stmt = $conn->prepare("UPDATE orders SET status='Return Approved', delivery_status='Return Approved' WHERE id=?");
        $stmt->bind_param("i", $_POST['order_id']);
        $stmt->execute();
        header("Location: orders.php");
        exit;
    }
    if(isset($_POST['reject_return'], $_POST['order_id'])) {
        $stmt = $conn->prepare("UPDATE orders SET status='Return Rejected', delivery_status='Delivered' WHERE id=?");
        $stmt->bind_param("i", $_POST['order_id']);
        $stmt->execute();
        header("Location: orders.php");
        exit;
    }
}

// Handle Delivery Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['delivery_status'])) {
    $orderId = intval($_POST['order_id']);
    $newDelivery = $_POST['delivery_status'];
    $allowedDelivery = ['Pending', 'Picked Up', 'On the Way', 'Delivered', 'Cancelled', 'Return Approved'];
    
    if (in_array($newDelivery, $allowedDelivery)) {
        $stmt = $conn->prepare("UPDATE orders SET delivery_status = ? WHERE id = ?");
        $stmt->bind_param("si", $newDelivery, $orderId);
        $stmt->execute();
    }

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
            <th>Payment Method</th>
            <th>Delivery Status</th>
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
  <td><?= htmlspecialchars($o['payment_method']) ?></td>

  <!-- Delivery Status + Call Button -->
  <td>
    <div style="display:flex; align-items:center; gap:5px;">
      <form method="post" action="orders.php" style="margin:0;">
        <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
        <select name="delivery_status" onchange="this.form.submit()"
            style="padding:5px 8px; border-radius:5px; border:1px solid #ccc;
                   <?php
                       switch ($o['delivery_status']) {
                           case 'Pending': echo 'background-color:#fdd835; color:#000;'; break;
                           case 'Picked Up': echo 'background-color:#42a5f5; color:#fff;'; break;
                           case 'On the Way': echo 'background-color:#fb8c00; color:#fff;'; break;
                           case 'Delivered': echo 'background-color:#66bb6a; color:#fff;'; break;
                           case 'Cancelled': echo 'background-color:#ef5350; color:#fff;'; break;
                           default: echo 'background-color:#fff; color:#000;';
                       }
                   ?>"
        >
            <option value="Pending" <?= $o['delivery_status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Picked Up" <?= $o['delivery_status'] === 'Picked Up' ? 'selected' : '' ?>>Picked Up</option>
            <option value="On the Way" <?= $o['delivery_status'] === 'On the Way' ? 'selected' : '' ?>>On the Way</option>
            <option value="Delivered" <?= $o['delivery_status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
            <option value="Cancelled" <?= $o['delivery_status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
      </form>

      <a href="https://www.foodpanda.ph" target="_blank" 
         title="Assign to delivery rider" 
         style="font-size:18px; color:#ff6f61; text-decoration:none; display:flex; align-items:center;">
         📞
      </a>
    </div>
  </td>

  <!-- Order Status + Delete -->
<td>
    <div style="display:flex; align-items:center; gap:5px;">

<!-- Status Dropdown -->
<form method="post" action="orders.php" style="margin:0;">
    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
    <select name="status" onchange="this.form.submit()"
        style="padding:5px 8px; border-radius:5px; border:1px solid #ccc;
            <?php
                switch ($o['status']) {
                    case 'Pending': echo 'background-color:#fdd835; color:#000;'; break;
                    case 'Completed': echo 'background-color:#66bb6a; color:#fff;'; break;
                    case 'Cancelled': echo 'background-color:#ef5350; color:#fff;'; break;
                    case 'Return Requested': echo 'background-color:#ab47bc; color:#fff;'; break;
                    case 'Return Approved': echo 'background-color:#4caf50; color:#fff;'; break;
                    case 'Return Rejected': echo 'background-color:#f44336; color:#fff;'; break;
                    default: echo 'background-color:#fff; color:#000;';
                }
            ?>"
    >
        <option value="Pending" <?= $o['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Completed" <?= $o['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
        <option value="Cancelled" <?= $o['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
        <option value="Return Requested" <?= $o['status'] === 'Return Requested' ? 'selected' : '' ?>>Return Requested</option>
        <option value="Return Approved" <?= $o['status'] === 'Return Approved' ? 'selected' : '' ?>>Return Approved</option>
        <option value="Return Rejected" <?= $o['status'] === 'Return Rejected' ? 'selected' : '' ?>>Return Rejected</option>
    </select>
</form>


        <!-- View Return Button OUTSIDE the form -->
        <?php if($o['status'] === 'Return Requested'): ?>
            <button class="return-btn" 
                type="button"
                onclick="showReturnPopup(<?= $o['id'] ?>)"
                style="padding:4px 8px; border-radius:6px; background:#ab47bc; color:white; border:none; cursor:pointer;">
                View Return
            </button>
        <?php endif; ?>

        <!-- Delete Button
        <a href="?delete=<?= $o['id'] ?>" 
           class="btn danger-btn action-btn" 
           onclick="return confirm('Are you sure you want to delete this order?');"
           style="padding:5px 8px; border-radius:5px; background:#ef5350; color:#fff; text-decoration:none; display:flex; align-items:center;">
           Delete
        </a> -->
    </div>
</td>


</tr>


          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </main>
</div>

<div id="returnPopup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
    <div style="background:#fff; padding:20px; border-radius:8px; width:400px; max-width:90%; position:relative;">
        <span style="position:absolute; top:10px; right:15px; cursor:pointer; font-weight:bold; font-size:18px;" onclick="closeReturnPopup()">×</span>
        <div id="returnPopupContent"></div>
    </div>
</div>


<script src="../assets/js/script.js">
</script>
<script>
  document.querySelectorAll('.status-dropdown').forEach(dropdown => {
    dropdown.addEventListener('change', function() {
        const value = this.value.toLowerCase();
        this.classList.remove('pending-option', 'confirmed-option', 'cancelled-option');
        this.classList.add(value + '-option');
    });
});

function showReturnPopup(orderId) {
    fetch('fetch_return_details.php?id=' + orderId)
    .then(res => res.text())
    .then(html => {
        document.getElementById('returnPopupContent').innerHTML = html;
        document.getElementById('returnPopup').style.display = 'flex';
    })
    .catch(err => alert("Failed to load return details: " + err));
}

function closeReturnPopup() {
    document.getElementById('returnPopup').style.display = 'none';
}
</script>
</body>
</html>
