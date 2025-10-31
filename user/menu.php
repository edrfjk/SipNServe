<?php
session_start();
include '../includes/db.php';

$filter = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$type   = isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : '';

// Base query
$query = "SELECT * FROM products WHERE (name LIKE '%$filter%' OR category LIKE '%$filter%')";

// Add category filter if selected (Hot or Iced)
if ($type != '') {
    $query .= " AND category = '$type'";
}

$query .= " ORDER BY id DESC";

$products = $conn->query($query) or die($conn->error);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Menu - SipNServe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <section id="header">
        <a href="#"><img src="../assets/images/siplogo.png" class="logo"></a>
        <div>
            <ul id="navbar">
                <li><a href="home.php">Home</a></li>
                <li><a class="active" href="menu.php">Menu</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="cart.php"><i class="fa fa-shopping-cart"></i></a></li>
                <li>
                    <form action="menu.php" method="get" class="search-form">
                        <input type="text" name="search" placeholder="Search product..." required>
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>

                </li>
                <li>
                    <div class="profile-dropdown1">
                        <img src="../assets/images/profiles/<?= $_SESSION['profile_img'] ?>"
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
            <img src="../assets/images/profiles/<?= $_SESSION['profile_img'] ?>" id="mobile-profile-icon" />
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
        <h2>#SipMenu</h2>
        <p>Explore our handcrafted coffee creations</p>
    </section>



    <!-- WHY CHOOSE US -->
    <section id="why-choose">
        <h2>Why Sip with Us?</h2>
        <div class="why-container">
            <div class="why-box">
                <i class="fas fa-mug-hot"></i>
                <h4>Fresh Beans</h4>
                <p>We roast our coffee daily for peak freshness.</p>
            </div>
            <div class="why-box">
                <i class="fas fa-heart"></i>
                <h4>Made with Love</h4>
                <p>Every cup is brewed with passion and care.</p>
            </div>
            <div class="why-box">
                <i class="fas fa-coffee"></i>
                <h4>Signature Brews</h4>
                <p>Unique blends crafted to suit every taste.</p>
            </div>
        </div>
    </section>



    </section>
    <section id="product1" class="section-p1">
        <h2>Our Coffee Selection</h2>
        <p><?= $filter ? "Search results for '$filter'" : "Browse our categories" ?></p>
<!-- Coffee Type Filter -->
<div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 25px; margin-right: 20px;">
  <form method="GET" action="menu.php" 
        style="background-color: #f6f2ee; padding: 8px 15px; border-radius: 25px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;">
    
    <!-- Keep the current search if active -->
    <input type="hidden" name="search" value="<?= htmlspecialchars($filter) ?>">

    <label for="type" 
           style="font-weight: 600; color: #4b2e1e; font-family: 'Poppins', sans-serif; font-size: 14px;">
      Show:
    </label>

    <select name="type" id="type" onchange="this.form.submit()"
            style="border: none; background-color: #fff; padding: 6px 12px; border-radius: 20px; font-size: 14px; color: #4b2e1e; cursor: pointer; outline: none; font-family: 'Poppins', sans-serif;">
      <option value="">All</option>
      <option value="Hot Coffee" <?= (isset($_GET['type']) && $_GET['type'] == 'Hot Coffee') ? 'selected' : '' ?>>Hot</option>
      <option value="Iced Coffee" <?= (isset($_GET['type']) && $_GET['type'] == 'Iced Coffee') ? 'selected' : '' ?>>Iced</option>
    </select>
  </form>
</div>




        <div class="pro-container">
            <?php while ($p = $products->fetch_assoc()): ?>
                <div class="pro" onclick="openPopup(<?= $p['id'] ?>)">
                    <img src="../assets/images/products/<?= $p['image'] ?>">
                    <div class="des">
                        <span><?= $p['category'] ?></span>
                        <h5><?= $p['name'] ?></h5>
                        <div class="star">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <h4>₱<?= number_format($p['price_small'], 2) ?></h4>
                    </div>
                    <a class="cart"><i class="fas fa-cart-plus"></i></a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- VISIT US / PROMO BANNER -->
    <section id="visit-banner">
        <div class="visit-overlay">
            <h2>Come and Experience SipNServe</h2>
            <p>Visit us for fresh brews, warm smiles, and a cozy atmosphere.</p>
            <a href="about.php" class="visit-btn">Learn More</a>
        </div>
    </section>


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

    <div id="popup" class="popup" style="display:none;">
        <div id="popupContent"></div>
    </div>

    <!-- POPUP (loaded va JS) -->
    <div id="popup" class="popup" style="display:none;">
        <div id="popupContent"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        function openPopup(id) {
    axios.get("../includes/fetch_product.php?id=" + id).then(res => {
        const popup = document.getElementById("popup");
        popup.style.display = "block";
        document.getElementById("popupContent").innerHTML = res.data;

        // Close button
        const closeBtn = document.querySelector(".popup-close-btn");
        if (closeBtn) {
            closeBtn.addEventListener("click", () => {
                popup.style.display = "none";
            });
        }

        // Attach dynamic price update handler
        const sizeSelect = document.querySelector("#popupContent #size");
        const priceDisplay = document.querySelector("#popupContent #price-display");

        if (sizeSelect && priceDisplay) {
            sizeSelect.addEventListener("change", () => {
                const selected = sizeSelect.options[sizeSelect.selectedIndex];
                const newPrice = parseFloat(selected.getAttribute("data-price")).toFixed(2);
                priceDisplay.textContent = "₱" + newPrice;
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
                dropdown.style.maxHeight = dropdown.scrollHeight + "px"; // Set max-height to the full height of the dropdown
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

        // pro ani
        // products animation
        document.addEventListener("DOMContentLoaded", function() {
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
    </script>
</body>

</html>