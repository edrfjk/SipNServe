<?php
session_start();
include '../includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

// Get top 5 best-selling products (simply most ordered)
$topProducts = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 4");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SipNServe | Home</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

  <!-- HEADER / NAVBAR -->
  <section id="header">
    <a href="#"><img src="../assets/images/siplogo.png" class="logo"></a>
    <div>
      <ul id="navbar">
        <li><a class="active" href="home.php">Home</a></li>
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




  <!-- HERO SECTION -->
  <section id="hero">
    <h4>Luxurious Coffee</h4>
    <h2>Savor Every Sip</h2>
    <h1>Freshly Brewed Daily</h1>
    <p>Get your caffeine fix — fast, flavorful, fantastic</p>
    <button onclick="window.location.href='menu.php';">Shop Now</button>
  </section>

  <!-- FEATURES -->
  <section id="feature1">
    <h2>Services</h2>
    <div class="border"></div>
    <h4>We offer quality coffee, fast delivery, and warm service — making every sip worth it.</h4>
  </section>
  <section id="feature" class="section-p1">
    <div class="fe-box">
      <img src="../assets/images/f1.png">
      <h6>Free Shipping</h6>
    </div>
    <div class="fe-box">
      <img src="../assets/images/f2.png">
      <h6>Online Order</h6>
    </div>
    <div class="fe-box">
      <img src="../assets/images/f3.png">
      <h6>Save Money</h6>
    </div>
    <div class="fe-box">
      <img src="../assets/images/f4.png">
      <h6>Promotions</h6>
    </div>
    <div class="fe-box">
      <img src="../assets/images/f5.png">
      <h6>Happy Sell</h6>
    </div>
    <div class="fe-box">
      <img src="../assets/images/f6.png">
      <h6>24/7 Support</h6>
    </div>
  </section>

  <!-- BEST SELLERS -->
  <section id="product1" class="section-p1">
    <h2>Best Sellers</h2>
    <div class="border"></div>
    <p>Top-picked by our coffee lovers</p>
    <div class="pro-container">
      <?php while ($p = $topProducts->fetch_assoc()): ?>
        <div class="pro" onclick="openPopup(<?= $p['id'] ?>)">
          <img src="../assets/images/products/<?= $p['image'] ?>" alt="<?= $p['name'] ?>">
          <div class="des">
            <span><?= $p['category'] ?></span>
            <h5><?= $p['name'] ?></h5>
            <div class="star">
              <i class="fas fa-star"></i> <i class="fas fa-star"></i>
              <i class="fas fa-star"></i> <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <h4>₱<?= number_format($p['price'], 2) ?></h4>
          </div>
          <a class="cart"><i class="fas fa-cart-plus"></i></a>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

  <!-- about -->
  <section id="about-head" class="section-p1">
    <div class="about-text">
      <h2>Who Are We?</h2>
      <p>
        Welcome to SipNServe — your cozy corner for great coffee and good vibes! <br><br>
        At SipNServe, we believe every cup tells a story. Established with a passion for quality and community, our coffee shop offers handcrafted beverages made from freshly roasted beans and premium ingredients. Whether you’re here for your morning boost, a mid-day treat, or a relaxing evening sip, we’re dedicated to serving you with warmth and care.
        <br><br>
        Our menu features a variety of classic and signature coffee drinks, refreshing teas, and delightful pastries — all crafted to satisfy every craving. More than just a café, SipNServe is a space where friends meet, ideas flow, and moments are shared.
        <br><br>
        Sip. Savor. Serve. That’s our promise to you. Join us at SipNServe and be part of our growing community of coffee lovers.
      </p>
    </div>

    <div class="about-logo">
      <img src="../assets/images/shopdes.jpg" alt="Shop">
    </div>

    <div class="credits-marquee">
      <div class="marquee-content">
        <span>Developed by Eidref Jake S. Manalansan • </span>
        <span>Developed by Eidref Jake S. Manalansan • </span>
      </div>
    </div>

  </section>



  <!-- FAQ Section -->
  <section id="faq">
    <div class="faq-container">
      <h2>Frequently Asked Questions</h2>
      <div class="border"></div>
      <div class="faq-item">
        <button class="faq-question">
          How can I place an order?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>You can place an order directly from our menu page. Just select your product, add to cart, and checkout.</p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          Do you offer free shipping?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>Yes! We offer free shipping for all orders over ₱500 within our service area.</p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">
          What payment methods do you accept?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>We accept credit/debit cards, GCash, and cash on delivery for eligible areas.</p>
        </div>
      </div>
    </div>
  </section>




  <!-- Testimonials -->
  <div class="testimonials">
    <div class="inner">
      <h1>What our Customers say...</h1>
      <div class="border"></div>

      <div class="row">
        <div class="col">
          <div class="testimonial">
            <img src="../assets/images/testimonials/p1.jpg" alt="">
            <div class="name">Yuri Bib Somera</div>
            <div class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>

            <h5>
              Sobrang yummy mga sis!!!!
            </h5>
          </div>
        </div>

        <div class="col">
          <div class="testimonial">
            <img src="../assets/images/testimonials/p2.jpg" alt="">
            <div class="name">Marjorie Morgate</div>
            <div class="stars">
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
              <i class="far fa-star"></i>
              <i class="far fa-star"></i>
              <i class="far fa-star"></i>
            </div>

            <h5>
              Agat takki!
            </h5>
          </div>
        </div>

        <div class="col">
          <div class="testimonial">
            <img src="../assets/images/testimonials/p3.jpg" alt="">
            <div class="name">Jacen Luke Cielos</div>
            <div class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>

            <h5>
              So yummy!!!!
            </h5>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Newsletter / Promo Banner -->
  <section id="newsletter">
    <div class="newsletter-overlay">
      <div class="newsletter-content">
        <h2>Stay Updated!</h2>
        <p>Subscribe to get the latest news, offers, and exclusive deals from SipNServe.</p>
        <form action="#" method="post" class="newsletter-form">
          <input type="email" name="email" placeholder="Enter your email" required>
          <button type="submit">Subscribe</button>
        </form>
      </div>
    </div>
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


  <!-- POPUP (loaded via JS) -->
  <div id="popup" class="popup" style="display:none;">
    <div id="popupContent"></div>
  </div>

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


    document.querySelectorAll('.faq-question').forEach(button => {
      button.addEventListener('click', () => {
        const faqItem = button.parentElement;
        faqItem.classList.toggle('active');
      });
    });
  </script>

</body>

</html>