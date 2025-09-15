<nav>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="cart.php">Cart</a></li>
    </ul>
    <div class="profile-dropdown">
        <img src="../assets/images/profiles/<?=$_SESSION['profile_img']?>" width="40" height="40">
        <div class="dropdown">
            <a href="orders.php">Orders</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
</nav>
