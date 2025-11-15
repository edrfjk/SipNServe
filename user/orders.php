<?php
session_start();
include '../includes/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

if (isset($_GET['return'])) {
    $orderId = intval($_GET['return']);

    // Only delivered or completed orders can be returned
    $stmt = $conn->prepare("
        UPDATE orders 
        SET status = 'Return Requested', delivery_status = 'Return Requested'
        WHERE id = ? AND user_id = ?
        AND status IN ('Delivered', 'Completed')
    ");

    $stmt->bind_param("ii", $orderId, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Return request submitted successfully.');</script>";
        header("Location: orders.php");
        exit;
    } else {
        echo "<script>alert('Return request cannot be processed.');</script>";
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $orderId = intval($_GET['delete']);

    // User can cancel only if not delivered or completed
$stmt = $conn->prepare("
    UPDATE orders 
    SET status = 'Cancelled', delivery_status = 'Cancelled' 
    WHERE id = ? 
      AND user_id = ? 
      AND delivery_status NOT IN ('Delivered', 'Completed')
");


    $stmt->bind_param("ii", $orderId, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: orders.php");
        exit;
    } else {
        echo "<script>alert('Order cannot be cancelled because it is already delivered or completed.');</script>";
    }
}


// Fetch orders
$orders = $conn->query("
    SELECT o.*, p.name, p.image 
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE o.user_id = $userId
    ORDER BY o.created_at DESC
");
?>


<!DOCTYPE html>
<html>
<head>
    <title>My Orders - SipNServe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<!-- HEADER -->
<section id="header">
    <a href="#"><img src="../assets/images/siplogo.png" class="logo"></a>
    <div>
        <ul id="navbar">
            <li><a href="home.php">Home</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="cart.php"><i class="fa fa-shopping-cart"></i></a></li>
            <li>
                <form action="menu.php" method="get" class="search-form">
                    <input type="text" name="search" placeholder="Search product..." required>
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>

            </li>
            <li>
                <div class="profile-dropdown1 active">
                    <img src="../assets/images/profiles/<?=$_SESSION['profile_img']?>" 
                         style="width:35px; height:35px; border-radius:50%; vertical-align:middle; cursor:pointer;" 
                         onclick="toggleDropdown()">
                    <div id="dropdown-content1" class="dropdown-content1">
                        <a href="orders.php">My Orders</a>
                        <a href="../logout.php">Logout</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>


    <!-- MOBILE ICONS -->
        <div id="mobile-icons">
            <i class="fa fa-search" id="mobile-search-icon"></i>
            <i class="fa fa-bars" id="hamburger-icon"></i>
            <img src="../assets/images/profiles/<?=$_SESSION['profile_img']?>" id="mobile-profile-icon" />
        </div>

        <!-- MOBILE DROPDOWNS -->
        <div id="mobile-search-box">
            <form action="menu.php" method="get">
                <input type="text" name="search" placeholder="Search product..." required>
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>

        <div id="mobile-menu-dropdown">
            <a href="home.php">Home</a>
            <a href="menu.php">Menu</a>
            <a href="about.php">About</a>
            <a href="cart.php"><i class="fa fa-shopping-cart"></i> Cart</a>
        </div>

        <div id="mobile-profile-dropdown">
            <a href="orders.php">My Orders</a>
            <a href="../logout.php">Logout</a>
        </div>

</section>

<!-- ORDERS CONTENT -->
<section class="orders-container">
  <h2 style="text-align:center; color:#5c4033; margin-bottom: 20px;">My Orders</h2>
  <table>
    <thead>
      <tr>
        <th></th> <!-- delete column -->
        <th>Image</th>
        <th>Name</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Payment</th>
        <th>Delivery</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($o = $orders->fetch_assoc()): ?>
      <tr>
        
      <!-- Delete button -->
<td>
    <?php if ($o['delivery_status'] != 'Delivered' && $o['delivery_status'] != 'Completed' 
              && $o['status'] != 'Cancelled' && $o['status'] != 'Return Requested'): ?>
        <a href="?delete=<?= $o['id'] ?>" class="action-btn cancel" 
           title="Cancel Order" onclick="return confirm('Are you sure you want to cancel this order?')">
           <i class="fas fa-times"></i>
        </a>
    <?php endif; ?>

</td>



        <td>
            <img src="../assets/images/products/<?= $o['image'] ?>" width="60">
        </td>
        <td><?= $o['name'] ?></td>
        <td><?= $o['quantity'] ?></td>
        <td>₱<?= number_format($o['total_price'],2) ?></td>
        <td><?= htmlspecialchars($o['payment_method']) ?></td>
        <td>
            <span class="status-badge" style="<?php
                switch($o['delivery_status']){
                    case 'Pending': echo 'background:#fdd835;color:#000;'; break;
                    case 'Picked Up': echo 'background:#42a5f5;color:#fff;'; break;
                    case 'On the Way': echo 'background:#fb8c00;color:#fff;'; break;
                    case 'Delivered': echo 'background:#66bb6a;color:#fff;'; break;
                    case 'Cancelled': echo 'background:#ef5350;color:#fff;'; break;
                    default: echo 'background:#ccc;color:#000;';
                }
            ?>"><?= $o['delivery_status'] ?></span>
        </td>
<td>
    <span class="status-badge" style="<?php
        switch($o['status']){
            case 'Pending': echo 'background:#fdd835;color:#000;'; break;
            case 'Confirmed': echo 'background:#66bb6a;color:#fff;'; break;
            case 'Cancelled': echo 'background:#ef5350;color:#fff;'; break;
            case 'Delivered': echo 'background:#42a5f5;color:#fff;'; break;
            case 'Completed': echo 'background:#6a1b9a;color:#fff;'; break;
            case 'Return Requested': echo 'background:#ab47bc;color:#fff;'; break;

            default: echo 'background:#ccc;color:#000;';
        }
    ?>"><?= $o['status'] ?></span>

    <!-- Return Icon (only if delivered or completed) -->
    <?php if (in_array($o['status'], ['Delivered', 'Completed'])): ?>
        <a href="?return=<?= $o['id'] ?>" class="action-btn return" 
           title="Request Return" onclick="return confirm('Request return for this order?')">
           <i class="fas fa-undo"></i>
        </a>
    <?php endif; ?>
</td>

      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</section>



<!-- FOOTER -->
<footer class="section-p1">
  <div class="footer-container">
    <div class="footer-section">
      <h3>SipNServe</h3>
      <p>Serving you the finest brews with a touch of luxury.</p>
    </div>

    <div class="footer-section">
      <h3>Members</h3>
      <p>Eidref Jake S. Manalansan</p>
    </div>

    <div class="footer-section">
      <h3>Follow Us</h3>
      <div class="follow">
        <i class="fab fa-facebook-f"></i>
        <i class="fab fa-twitter"></i>
        <i class="fab fa-instagram"></i>
        <i class="fab fa-pinterest-p"></i>
        <i class="fab fa-youtube"></i>
      </div>
    </div>

    <div class="copyright">
      <p>&copy; 2025 SipNServe Coffee Shop</p>
    </div>
  </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
function openPopup(id) {
    axios.get("../includes/fetch_product.php?id=" + id).then(res => {
        document.getElementById("popup").style.display = "block";
        document.getElementById("popupContent").innerHTML = res.data;

        // Make sure to bind close to sold out AND regular popups
        const closeBtn = document.querySelector(".popup-close-btn");
        if (closeBtn) {
            closeBtn.addEventListener("click", () => {
                document.getElementById("popup").style.display = "none";
            });
        }
    });
}


// Function to toggle the dropdown menu (slide effect)
function toggleDropdown() {
    var dropdown = document.getElementById("dropdown-content1");

    // Check if dropdown is already open
    if (dropdown.style.maxHeight) {
        // If it's open, close it
        dropdown.style.maxHeight = null;
    } else {
        // If it's closed, open it
        dropdown.style.maxHeight = dropdown.scrollHeight + "px";  // Set max-height to the full height of the dropdown
    }
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.profile-dropdown1 img')) {
        var dropdown = document.getElementById("dropdown-content1");
        if (dropdown.style.maxHeight) {
            dropdown.style.maxHeight = null;
        }
    }
};


// MOBILE TOGGLE FUNCTIONALITY
document.getElementById("hamburger-icon").addEventListener("click", () => {
    toggleMobileDropdown("mobile-menu-dropdown");
});

document.getElementById("mobile-search-icon").addEventListener("click", () => {
    toggleMobileDropdown("mobile-search-box");
});

document.getElementById("mobile-profile-icon").addEventListener("click", () => {
    toggleMobileDropdown("mobile-profile-dropdown");
});

function toggleMobileDropdown(id) {
    const allDropdowns = ["mobile-search-box", "mobile-menu-dropdown", "mobile-profile-dropdown"];
    allDropdowns.forEach(drop => {
        if (drop === id) {
            const el = document.getElementById(drop);
            el.style.display = el.style.display === "block" ? "none" : "block";
        } else {
            document.getElementById(drop).style.display = "none";
        }
    });
}

// Optional: Close when clicking outside
window.addEventListener("click", function(e) {
    const dropdowns = ["mobile-search-box", "mobile-menu-dropdown", "mobile-profile-dropdown"];
    if (!e.target.closest("#mobile-icons") && !dropdowns.some(id => e.target.closest(`#${id}`))) {
        dropdowns.forEach(id => {
            document.getElementById(id).style.display = "none";
        });
    }
});


// products animation
document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector('#product1 .pro-container');
    const slide = container.querySelector('.pro');
    if (!container || !slide) return;

    const slideWidth = slide.offsetWidth + 15; // includes gap
    let scrollPosition = 0;

    function autoSlide() {
        scrollPosition += slideWidth;
        if (scrollPosition >= container.scrollWidth - container.clientWidth) {
            scrollPosition = 0;
        }
        container.scrollTo({
            left: scrollPosition,
            behavior: 'smooth'
        });
    }

    let interval = setInterval(autoSlide, 3000); // every 3 seconds

    container.addEventListener('mouseenter', () => clearInterval(interval));
    container.addEventListener('mouseleave', () => interval = setInterval(autoSlide, 3000));
});


document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {
      const faqItem = button.parentElement;
      faqItem.classList.toggle('active');
    });
  });





</script>


</body>
</html>
