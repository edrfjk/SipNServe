<?php
// Manage Users Page (users.php)

session_start();
include '../includes/db.php';
if (!isset($_SESSION['admin'])) header("Location: ../login.php");

// Activate / Deactivate user logic
if (isset($_GET['deactivate'])) {
  $userId = intval($_GET['deactivate']);
  if ($userId > 0) {
    $conn->query("UPDATE users SET status = 'inactive' WHERE id = $userId");
    header("Location: users.php");
    exit;
  }
}


if (isset($_GET['activate'])) {
  $userId = intval($_GET['activate']);
  if ($userId > 0) {
    $conn->query("UPDATE users SET status = 'active' WHERE id = $userId");
    header("Location: users.php?updated=1");
    exit;
  }
}

// Get all users
$users = $conn->query("SELECT * FROM users");
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users</title>
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
      <a href="users.php" class="active">
        <span class="material-symbols-sharp">person_outline</span>
        <h3>Users</h3>
      </a>
      <a href="products.php">
        <span class="material-symbols-sharp">receipt_long</span>
        <h3>Products</h3>
      </a>
      <a href="orders.php">
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
  <h1>Manage Users</h1>



  <div class="recent_order">
    <h2>User List</h2>
    <table>
      <thead>
        <tr>
          <th>Username</th>
          <th>Email</th>
          <th>Location</th>
          <th>Profile</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <?php while ($u = $users->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['address']) ?></td>
            <td><img src="../assets/images/profiles/<?= $u['profile_img'] ?>" width="40" style="border-radius: 50%;"></td>
            <td>
              <?php
                $userStatus = $u['status'] ?? 'active';
                $statusClass = $userStatus === 'active' ? 'success' : 'warning';
              ?>
              <span class="<?= $statusClass ?>"><?= ucfirst($userStatus) ?></span>
            </td>
            <td>
              <form method="GET" style="display:inline;">
                <input type="hidden" name="<?= $userStatus === 'active' ? 'deactivate' : 'activate' ?>" value="<?= $u['id'] ?>">
                <button type="submit" class="status-btn <?= $userStatus === 'active' ? 'danger' : 'success' ?>">
                  <?= $userStatus === 'active' ? 'Deactivate' : 'Activate' ?>
                </button>
              </form>

            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</main>

</div>
<script src="../assets/js/script.js"></script>
</body>
</html>
