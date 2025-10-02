// js/script.js
document.addEventListener('DOMContentLoaded', function () {
    // Xử lý nút tìm kiếm
    const searchButton = document.querySelector('.search button');
    searchButton.addEventListener('click', function () {
        const query = document.querySelector('.search input').value;
        alert(`Tìm kiếm: ${query}`);
    });

    // Xử lý nút "Thêm vào giỏ"
    const addToCartButtons = document.querySelectorAll('.product-item a');
    let cartCount = 0;
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.getAttribute('href').split('id=')[1];
            themVaoGio(productId);
            cartCount++;
            document.querySelector('.cart-count').textContent = cartCount;
        });
    });
});

function themVaoGio(productId) {
    alert(`Sản phẩm với ID ${productId} đã được thêm vào giỏ hàng!`);
}