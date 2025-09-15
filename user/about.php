<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>About - SipNServe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<section id="header">
    <a href="#"><img src="../assets/images/siplogo.png" class="logo"></a>
    <div>
        <ul id="navbar">
            <li><a href="home.php">Home</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a class="active" href="about.php">About</a></li>
            <li><a href="cart.php"><i class="fa fa-shopping-cart"></i></a></li>
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


<section class="about-header">
    <h2>About SipNServe</h2>
    <p>Discover our passion for coffee and people</p>
</section>

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
        <span>Developed by Eidref Jake S. Manalansan •  </span>
        <span>Developed by Eidref Jake S. Manalansan • </span>
    </div>
</div>

</section>

<section id="faq">
  <div class="faq-container">
    <h2>Frequently Asked Questions</h2>
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
        <h1>Reviews</h1>
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
        document.getElementById("popup").style.display = "block";
        document.getElementById("popupContent").innerHTML = res.data;
    });
}

function closePopup() {
    document.getElementById("prodetails").style.display = "none";
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

document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {
      const faqItem = button.parentElement;
      faqItem.classList.toggle('active');
    });
  });



</script>

</body>
</html>
