<?php
session_start();
include '../includes/db.php';

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();

if (!$p) {
    echo "Product not found!";
    exit();
}
?>

<div class="product-popup">
    <div class="popup-content">
        <span class="popup-close-btn">&times;</span>
        <div class="popup-image">
            <img src="../assets/images/products/<?= $p['image'] ?>" alt="<?= htmlspecialchars($p['name']) ?>">
            <?php if ($p['status'] === 'sold_out'): ?>
                <div class="sold-out-overlay">SOLD OUT</div>
            <?php endif; ?>
        </div>
        <div class="popup-details">
            <h6><?= htmlspecialchars($p['category']) ?></h6>
            <h2><?= htmlspecialchars($p['name']) ?></h2>
            <h3>₱<?= number_format($p['price'], 2) ?></h3>

            <?php if ($p['status'] === 'available'): ?>
                <form action="add_to_cart.php" method="post" class="popup-cart-form">
                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                    <div class="quantity-box">
                        <button type="button" class="qty-btn" onclick="this.nextElementSibling.stepDown()">-</button>
                        <input type="number" name="quantity" value="1" min="1">
                        <button type="button" class="qty-btn" onclick="this.previousElementSibling.stepUp()">+</button>
                    </div>
                    <button type="submit" class="add-cart-btn">Add To Cart</button>
                </form>
            <?php else: ?>
                <button class="add-cart-btn sold-out-btn" disabled>Sold Out</button>
            <?php endif; ?>

            <h4>Product Details</h4>
            <p><?= nl2br(htmlspecialchars($p['description'])) ?></p>
        </div>
    </div>
</div>

<style>
/* Popup Overlay */
.product-popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.65);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease;
}

/* Popup Content Box */
.popup-content {
    background: #faf3e0; /* Cream */
    border-radius: 15px;
    display: flex;
    flex-wrap: wrap;
    width: 90%;
    max-width: 800px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    animation: slideUp 0.3s ease;
}

/* Close Button */
/* Keep the button inside the popup box */
.popup-close-btn {
    position: absolute; /* inside the .single-pro-wrapper */
    top: 10px;
    right: 10px;
    font-size: 28px;
    cursor: pointer;
    font-weight: bold;
    color: #555; /* matches popup design */
    background: #fff; /* small background for visibility */
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    z-index: 10;
    transition: background 0.2s ease;
}

.popup-close-btn:hover {
    background: #f0f0f0;
}

/* On small screens, slightly bigger tap area */
@media (max-width: 480px) {
    .popup-close-btn {
        font-size: 24px;
        width: 40px;
        height: 40px;
        top: 8px;
        right: 8px;
    }
}


/* Image Section */
.popup-image {
    flex: 1 1 40%;
    position: relative;
}
.popup-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.sold-out-overlay {
    position: absolute;
    top: 0;
    left: 0;
    background: rgba(60, 42, 33, 0.7);
    width: 100%;
    height: 100%;
    color: white;
    font-size: 2rem;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
}

/* Details Section */
.popup-details {
    flex: 1 1 60%;
    padding: 25px;
    color: #3e2723;
}
.popup-details h6 {
    font-size: 14px;
    color: #8d6e63;
    margin-bottom: 8px;
}
.popup-details h2 {
    font-size: 24px;
    margin-bottom: 8px;
}
.popup-details h3 {
    font-size: 20px;
    color: #7b5e57;
    margin-bottom: 15px;
}

.popup-details h4 {
    font-size: 20px;
    color: #7b5e57;
    margin-bottom: 15px;
}
.popup-details p {
    font-size: 15px;
    line-height: 1.6;
    color: black;
}

/* Quantity + Button */
.popup-cart-form {
    display: flex;
    gap: 12px;
    align-items: center;
    margin-bottom: 15px;
}

.quantity-box {
    display: flex;
    border: 1px solid #cbb6a8;
    border-radius: 30px;
    overflow: hidden;
    background: white;
}

.quantity-box input[type="number"] {
    width: 50px;
    text-align: center;
    border: none;
    font-size: 14px;
    color: #3e2723;
    background: transparent;
}

.qty-btn {
    background: #f0e5da;
    border: none;
    width: 32px;
    height: 32px;
    font-size: 18px;
    cursor: pointer;
    color: #5d4037;
    transition: background 0.2s;
}
.qty-btn:hover {
    background: #e0d0c0;
}

/* Add to Cart Button */
.add-cart-btn {
    background: #8d6e63;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 30px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s, transform 0.1s;
    box-shadow: 0 4px 12px rgba(141, 110, 99, 0.18);
}
.add-cart-btn:hover {
    background: #6f4f43;
    transform: translateY(-1px);
}

/* Sold Out Button */
.sold-out-btn {
    background: #ccc;
    color: #555;
    cursor: not-allowed;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; } to { opacity: 1; }
}
@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Responsive */
@media (max-width: 768px) {
    .popup-content {
        flex-direction: column;
    }
    .popup-image, .popup-details {
        flex: 1 1 100%;
    }
}
</style>
