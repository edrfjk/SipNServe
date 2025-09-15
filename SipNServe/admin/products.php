<?php
// Redesigned Products Page (products.php)

session_start();
include '../includes/db.php';
if (!isset($_SESSION['admin'])) header("Location: ../login.php");

if (isset($_POST['add'])) {
  $name = $_POST['name'];
  $cat = $_POST['category'];
  $desc = $_POST['description'];
  $price = $_POST['price'];
  $img = $_FILES['image']['name'];
  move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/products/" . $img);
  $conn->query("INSERT INTO products (name, category, price, description, image) VALUES ('$name', '$cat', $price, '$desc', '$img')");
}

if (isset($_GET['soldout'])) {
  $id = intval($_GET['soldout']);
  $conn->query("UPDATE products SET status = 'sold_out' WHERE id = $id");
  header("Location: products.php");
  exit();
}

if (isset($_GET['restock'])) {
  $id = intval($_GET['restock']);
  $conn->query("UPDATE products SET status = 'available' WHERE id = $id");
  header("Location: products.php");
  exit();
}



$products = $conn->query("SELECT * FROM products");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Products</title>
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
      <a href="products.php" class="active">
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
    <h1>Manage Products</h1>

    <div class="recent_order">
      <h2>Add Product</h2>
      <form method="post" enctype="multipart/form-data" style="display: grid; gap: 1rem;">
        <input name="name" placeholder="Name" required>
        <input name="category" placeholder="Category" required>
        <input name="price" type="number" step="0.01" placeholder="Price" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="file" name="image" required>
        <button name="add" class="btn primary-btn">Add Product</button>
      </form>
    </div>

    <div class="recent_order">
      <h2>Product List</h2>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Image</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($p = $products->fetch_assoc()): ?>
          <tr>
            <td><?= $p['name'] ?></td>
            <td><?= $p['category'] ?></td>
            <td>₱<?= number_format($p['price'], 2) ?></td>
            <td><img src="../assets/images/products/<?= $p['image'] ?>" width="50"></td>
            <td>
              <span class="<?= $p['status'] === 'available' ? 'success' : 'warning' ?>">
                <?= ucfirst($p['status']) ?>
              </span>
            </td>

            <td>
              <?php if ($p['status'] === 'available'): ?>
                <a href="?soldout=<?= $p['id'] ?>" class="btn danger-btn">Mark as Sold Out</a>
              <?php else: ?>
                <a href="?restock=<?= $p['id'] ?>" class="btn success-btn">Restock</a>
              <?php endif; ?>
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
