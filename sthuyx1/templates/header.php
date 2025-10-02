<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'STHUYX Shop - PC & Phụ kiện Gaming'; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../js/custom.js" defer></script>
    <script>
        // Cập nhật số lượng giỏ hàng
        function updateCartCount() {
            fetch('../giohang/get_cart_count.php')
                .then(response => response.json())
                .then(data => {
                    const cartCount = document.querySelectorAll('.cart-count');
                    cartCount.forEach(element => {
                        element.textContent = data.cart_count || 0;
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        window.onload = updateCartCount;
        setInterval(updateCartCount, 5000);
    </script>
</head>
<body>
    <div id="cart-notification" style="position: fixed; top: 60px; right: 20px; background-color: #2ecc71; color: white; padding: 10px 20px; border-radius: 5px; display: none; z-index: 1000; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);">
        <span id="notification-text"></span>
    </div>

    <header>
        <div class="header-info">
            <div class="contact-info">
                <p>Hotline: 113 | Tư vấn Build PC: 114</p>
            </div>
        </div>
        <div class="header-bottom">
            <div class="logo">
                <a href="../index.php">
                    <img src="../images/logo.png" alt="STHUYX Shop Logo" width="100">
                </a>
            </div>
            <div class="search">
                <form method="GET" action="../sanpham/search.php">
                    <input type="text" name="query" placeholder="Tìm kiếm sản phẩm...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="user-actions">
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="../nguoidung/account.php" class="user-button">Tài khoản</a>
        <a href="../nguoidung/logout.php" class="user-button">Đăng xuất</a>
    <?php else: ?>
        <a href="../nguoidung/login.php" class="user-button">Đăng nhập</a>
        <a href="../nguoidung/register.php" class="user-button">Đăng ký</a>
    <?php endif; ?>
    <a href="../giohang/cart.php" class="cart">Giỏ hàng <span class="cart-count">0</span></a>
</div>
        </div>
    </header>

    <main>