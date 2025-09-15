function showPopup(productId) {
    const popup = document.getElementById("popup");
    fetch(`../includes/fetch_product.php?id=${productId}`)
        .then(res => res.text())
        .then(data => {
            document.getElementById("popupContent").innerHTML = data;
            popup.style.display = "block";
        });
}

document.addEventListener("DOMContentLoaded", () => {
    let slides = document.querySelectorAll(".slide");
    let index = 0;
    setInterval(() => {
        slides[index].classList.remove("active");
        index = (index + 1) % slides.length;
        slides[index].classList.add("active");
    }, 3000);
});

