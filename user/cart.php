<?php
session_start();
include '../includes/db.php';

$userId = $_SESSION['user_id'];

// Fetch cart items including product info
$items = $conn->query("SELECT c.*, p.name, p.image 
                       FROM cart c 
                       JOIN products p ON c.product_id = p.id 
                       WHERE c.user_id = $userId");

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['qty'])) {
        foreach ($_POST['qty'] as $cartId => $quantity) {
            $conn->query("UPDATE cart SET quantity = $quantity WHERE id = $cartId");
        }
    }

    // Checkout
// Handle quantity update and checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Update quantities
    if (isset($_POST['qty'])) {
        foreach ($_POST['qty'] as $cartId => $quantity) {
            $conn->query("UPDATE cart SET quantity = $quantity WHERE id = $cartId");
        }
    }

    // Final order confirmation
// Final order confirmation
if (isset($_POST['checkout'])) {
    $payment_method = $_POST['payment_method'] ?? 'Cash'; // default to COD
if ($payment_method === 'Cash') $payment_method = 'COD';
    elseif ($payment_method === 'Gcash') $payment_method = 'Gcash';
    elseif ($payment_method === 'Card') $payment_method = 'Card';

    $orders = $conn->query("SELECT * FROM cart WHERE user_id = $userId");
    while ($o = $orders->fetch_assoc()) {
        $pid = $o['product_id'];
        $qty = $o['quantity'];
        $price = $o['price']; // stored price
        $total = $qty * $price;
        $size = $o['size']; // get size from cart

        $conn->query("INSERT INTO orders 
            (user_id, product_id, quantity, price, total_price, status, created_at, size, payment_method) 
            VALUES 
            ($userId, $pid, $qty, $price, $total, 'Pending', NOW(), '$size', '$payment_method')");
    }

    // Clear the cart
    $conn->query("DELETE FROM cart WHERE user_id = $userId");

    header("Location: orders.php");
    exit;
}

}


}

// Handle removal
if (isset($_GET['remove'])) {
    $conn->query("DELETE FROM cart WHERE id=" . $_GET['remove']);
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart - SipNServe</title> 
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
            <li><a class="active" href="cart.php"><i class="fa fa-shopping-cart"></i></a></li>
            <li>
                <form action="menu.php" method="get" class="search-form">
                    <input type="text" name="search" placeholder="Search product..." required>
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>

            </li>
            <li>
                <div class="profile-dropdown1">
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

<section id="page-header">
    <h2>My Cart</h2>
</section>

<!-- CART -->
<section id="cart" class="section-p1">
<form method="post" id="cart-form">

  <?php if ($items->num_rows > 0): ?>
    <!-- Select All -->
    <div class="cart-select-all">
      <label><input type="checkbox" id="select-all" checked> Select All</label>
    </div>
  <?php endif; ?>

  <!-- Cart Items -->
  <div class="cart-items">
    <?php if ($items->num_rows > 0): ?>
      <?php while ($item = $items->fetch_assoc()): ?>
        <div class="cart-item" data-id="<?= $item['id'] ?>" data-price="<?= $item['price'] ?>">

          <input type="checkbox" class="item-checkbox" checked>

          <div class="item-image">
            <img src="../assets/images/products/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
          </div>

          <div class="item-details">
            <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
            <div class="item-price">₱<?= number_format($item['price'], 2) ?> (<?= ucfirst($item['size']) ?>)</div>


            <div class="quantity-selector">
              <button type="button" class="qty-btn minus">−</button>
              <input
                type="number"
                name="qty[<?= $item['id'] ?>]"
                value="<?= $item['quantity'] ?>"
                min="1"
                class="qty-input"
              >
              <button type="button" class="qty-btn plus">+</button>
            </div>

            <!-- Remove button -->
            <button type="button" class="remove-btn" data-id="<?= $item['id'] ?>">Remove</button>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="empty-cart-message">Your cart is empty.</p>
    <?php endif; ?>
  </div>
</form>

<!-- Checkout Summary Popup -->
<!-- Checkout Summary Popup -->
<div id="checkout-popup" style="
    position: fixed; top:0; left:0; width:100%; height:100%;
    background: rgba(0,0,0,0.6); display:flex; justify-content:center; align-items:center;
    z-index:9999; opacity:0; visibility:hidden; transition: opacity 0.3s ease;">

    <div id="checkout-content" style="
        background:#fff; padding:30px 25px; border-radius:15px; width:90%; max-width:450px;
        position:relative; transform: scale(0.8); transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 15px 40px rgba(0,0,0,0.3); font-family: Arial, sans-serif;">
        
        <span id="checkout-close" style="
            position:absolute; top:12px; right:12px; cursor:pointer; font-size:26px; color:#333;">&times;</span>
        
        <h2 style="text-align:center; margin-bottom:20px; color:#222;">Order Summary</h2>
        
        <div id="order-details" style="
            max-height:200px; overflow-y:auto; margin-bottom:15px; padding:10px; 
            border:1px solid #ddd; border-radius:10px; background:#f9f9f9; font-size:14px;"></div>
        
        <h3 style="text-align:right; margin-bottom:15px; color:#111;">Total: ₱<span id="order-total">0.00</span></h3>
        
        <!-- Payment Method -->
        <div style="margin-bottom:20px; font-size:14px; text-align:center;">
            <label style="margin-right:15px; cursor:pointer;">
                <input type="radio" name="payment_method" value="Cash" checked> COD
            </label>
            <label style="margin-right:15px; cursor:pointer;">
                <input type="radio" name="payment_method" value="Gcash"> GCash
            </label>
            <label style="cursor:pointer;">
                <input type="radio" name="payment_method" value="Card"> Card
            </label>
        </div>
        
<form method="post" id="finalize-order-form" style="text-align:center;">
    <input type="hidden" name="checkout" value="1">
    <input type="hidden" name="payment_method" id="selected-payment" value="Cash">
    <button type="submit" style="
        background: #ff6f61; color:#fff; border:none; padding:12px 25px; 
        border-radius:8px; font-size:16px; cursor:pointer; transition: background 0.3s;">
        Confirm Order
    </button>
</form>

    </div>
</div>






  <!-- Fixed Checkout Footer -->
  <div class="cart-summary">
    <h3>Total: ₱<span id="grand-total">0.00</span></h3>
    <button id="checkout-btn" form="cart-form" name="checkout">Checkout</button>
  </div>
</section>






<script>
document.addEventListener("DOMContentLoaded", function () {
  const updateTotal = () => {
    let total = 0;
    document.querySelectorAll(".cart-item").forEach(item => {
      const checkbox = item.querySelector(".item-checkbox");
      if (!checkbox || !checkbox.checked) return;

      const price = parseFloat(item.dataset.price);
      const qty = parseInt(item.querySelector(".qty-input").value);
      total += price * qty;
    });
    document.getElementById("grand-total").textContent = total.toFixed(2);
  };

  // Quantity +
  document.querySelectorAll(".plus").forEach(btn => {
    btn.addEventListener("click", () => {
      const input = btn.parentElement.querySelector(".qty-input");
      input.value = parseInt(input.value) + 1;
      updateTotal();
    });
  });

  // Quantity -
  document.querySelectorAll(".minus").forEach(btn => {
    btn.addEventListener("click", () => {
      const input = btn.parentElement.querySelector(".qty-input");
      if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        updateTotal();
      }
    });
  });

  // Select all toggle
  const selectAll = document.getElementById("select-all");
  if (selectAll) {
    selectAll.addEventListener("change", () => {
      document.querySelectorAll(".item-checkbox").forEach(cb => cb.checked = selectAll.checked);
      updateTotal();
    });
  }

  // Individual checkbox updates total
  document.querySelectorAll(".item-checkbox").forEach(cb => {
    cb.addEventListener("change", updateTotal);
  });

  // Quantity input change updates total
  document.querySelectorAll(".qty-input").forEach(input => {
    input.addEventListener("input", updateTotal);
  });

  // REMOVE ITEM (with fade out + DB delete)
  document.querySelectorAll(".remove-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const cartItem = btn.closest(".cart-item");
      const id = btn.dataset.id;

      fetch("remove_from_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + encodeURIComponent(id)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "success") {
          // Fade-out animation
          cartItem.style.transition = "opacity 0.4s ease, transform 0.4s ease";
          cartItem.style.opacity = "0";
          cartItem.style.transform = "translateX(-20px)";

          setTimeout(() => {
            cartItem.remove();
            updateTotal();

            if (!document.querySelector(".cart-item")) {
              document.querySelector(".cart-select-all")?.remove();
              document.querySelector(".cart-items").innerHTML =
                '<p class="empty-cart-message">Your cart is empty.</p>';
            }
          }, 400);
        } else {
          alert("Failed to remove product.");
        }
      })
      .catch(() => alert("Error removing product."));
    });
  });

  updateTotal();
});


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

// CHECKOUT POPUP LOGIC
const checkoutBtn = document.getElementById("checkout-btn");
const popup = document.getElementById("checkout-popup");
const content = document.getElementById("checkout-content");
const closePopup = document.getElementById("checkout-close");

checkoutBtn.addEventListener("click", (e) => {
    e.preventDefault(); // prevent immediate form submission

    const items = document.querySelectorAll(".cart-item");
    let detailsHTML = "<ul>";
    let total = 0;

    items.forEach(item => {
        const checkbox = item.querySelector(".item-checkbox");
        if (!checkbox.checked) return;

        const name = item.querySelector(".item-name").textContent;
        const price = parseFloat(item.dataset.price);
        const qty = parseInt(item.querySelector(".qty-input").value);
        const subtotal = price * qty;
        total += subtotal;

        detailsHTML += `<li>${name} - ${qty} x ₱${price.toFixed(2)} = ₱${subtotal.toFixed(2)}</li>`;
    });

    detailsHTML += "</ul>";
    document.getElementById("order-details").innerHTML = detailsHTML;
    document.getElementById("order-total").textContent = total.toFixed(2);

    // Show popup with fade-in and scale
    popup.style.visibility = "visible";
    popup.style.opacity = "1";
    content.style.transform = "scale(1)";
});

// Close popup
closePopup.addEventListener("click", () => {
    popup.style.opacity = "0";
    content.style.transform = "scale(0.8)";
    setTimeout(() => popup.style.visibility = "hidden", 300);
});

// Optional: click outside content closes popup
popup.addEventListener("click", (e) => {
    if (e.target === popup) {
        popup.style.opacity = "0";
        content.style.transform = "scale(0.8)";
        setTimeout(() => popup.style.visibility = "hidden", 300);
    }
});

// Update hidden input with selected payment method before form submit
const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
const paymentInput = document.getElementById("selected-payment");
const finalizeForm = document.getElementById("finalize-order-form");

paymentRadios.forEach(radio => {
    radio.addEventListener("change", () => {
        paymentInput.value = radio.value;
    });
});

// Optional: ensure payment input has correct default on page load
paymentInput.value = document.querySelector('input[name="payment_method"]:checked').value;

paymentRadios.forEach(radio => {
    radio.addEventListener("change", () => {
        paymentInput.value = radio.value;
    });
});

// set default on page load
paymentInput.value = document.querySelector('input[name="payment_method"]:checked').value;



</script>

</body>
</html>
