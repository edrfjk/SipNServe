<?php
// Redesigned Admin Dashboard (dashboard.php)

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';

// Fetch totals
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$totalOrders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$totalSales = $conn->query("SELECT SUM(total_price) AS total FROM orders WHERE status='Confirmed'")->fetch_assoc()['total'];

// Fetch recent orders
$recentOrders = $conn->query("SELECT o.*, u.username, p.name AS product_name FROM orders o JOIN users u ON o.user_id = u.id JOIN products p ON o.product_id = p.id ORDER BY o.id DESC LIMIT 5");

// Fetch user list
$usersList = $conn->query("SELECT username, profile_img FROM users ORDER BY id DESC LIMIT 5");

// Fetch top 3 products
$topProducts = $conn->query("SELECT p.name, p.image, SUM(o.quantity) AS sold_qty FROM orders o JOIN products p ON o.product_id = p.id WHERE o.status='Confirmed' GROUP BY o.product_id ORDER BY sold_qty DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SipNServe Dashboard</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp" />
  <link rel="stylesheet" href="newadmin.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .dashboard-top {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      margin-bottom: 2rem;
    }
    .insights { flex: 2; }
    .recent_updates { flex: 1; }
  </style>
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
      <a href="#" class="active">
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

<!-- MAIN -->
  <main>
    <h1>Dashboard</h1>
<form action="export_pdf.php" method="GET" style="margin-top: 2rem;">
  <label for="from">From:</label>
  <input type="date" id="from" name="from">
  
  <label for="to">To:</label>
  <input type="date" id="to" name="to">

  <button type="submit">Export PDF</button>
</form>

    <!-- TOP FLEX: INSIGHTS + USERS -->
    <div class="dashboard-top">
      <!-- INSIGHTS -->
      <div class="insights">
        <div class="sales">
          <span class="material-symbols-sharp">trending_up</span>
          <div class="middle">
            <div class="left">
              <h3>Total Sales</h3>
              <h1>₱<?= number_format($totalSales, 2) ?></h1>
            </div>
          </div>
          <small>Confirmed Orders</small>
        </div>

        <div class="expenses">
          <span class="material-symbols-sharp">local_mall</span>
          <div class="middle">
            <div class="left">
              <h3>Total Orders</h3>
              <h1><?= $totalOrders ?></h1>
            </div>
          </div>
          <small>All Time</small>
        </div>

        <div class="income">
          <span class="material-symbols-sharp">person</span>
          <div class="middle">
            <div class="left">
              <h3>Total Users</h3>
              <h1><?= $totalUsers ?></h1>
            </div>
          </div>
          <small>Registered</small>
        </div>
      </div>

      <div class="recent_updates">
        <h2>Latest Users</h2>
        <div class="recent-scroll">
          <?php while ($u = $usersList->fetch_assoc()): ?>
          <div class="update">
            <div class="profile-photo">
              <img src="../assets/images/profiles/<?= $u['profile_img'] ?>" alt="Profile" />
            </div>
            <div class="message">
              <p><b><?= htmlspecialchars($u['username']) ?></b> just registered.</p>
            </div>
          </div>
          <?php endwhile; ?>
        </div>
      </div>



<div class="dashboard-bottom">
  <!-- Chart Section -->
  <div class="chart-box" id="chart-analytics">
    <h2>Sales Overview</h2>
    <canvas id="myChart"></canvas>
  </div>

  <!-- Top Selling Products Section -->
  <div class="top-selling">
    <h2>Top Selling Products</h2>
    <div class="top-selling-scroll">
      <?php while ($p = $topProducts->fetch_assoc()): ?>
        <div class="item">
          <div class="profile-photo">
            <img src="../assets/images/products/<?= $p['image'] ?>" alt="Product Image" />
          </div>
          <div class="product-info">
            <h3><?= htmlspecialchars($p['name']) ?></h3>
            <small>Sold: <?= $p['sold_qty'] ?></small>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</div>




  <!-- RECENT ORDERS -->
    <div class="recent_order">
      <h2>Recent Orders</h2>
      <table>
        <thead>
          <tr>
            <th>User</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($o = $recentOrders->fetch_assoc()): ?>
          <tr>
            <td><?= $o['username'] ?></td>
            <td><?= $o['product_name'] ?></td>
            <td><?= $o['quantity'] ?></td>
            <td>₱<?= number_format($o['total_price'], 2) ?></td>
            <td class="<?= $o['status'] == 'Pending' ? 'warning' : 'success' ?>"><?= $o['status'] ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <a href="orders.php">Show All</a>
    </div>

  </main>
</div>

<script>
  // Chart Example
  const ctx = document.getElementById('myChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Users', 'Orders', 'Sales'],
      datasets: [{
        label: 'Statistics',
        data: [<?= $totalUsers ?>, <?= $totalOrders ?>, <?= $totalSales ?>],
        backgroundColor: ['#7380ec', '#ff7782', '#41f1b6']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false
    }
  });
</script>
<script src="../assets/js/script.js"></script>
</body>
</html>