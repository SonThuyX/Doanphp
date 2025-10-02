<?php
// File kết nối cơ sở dữ liệu
include 'includes/db_connect.php';

// Bắt đầu session cho giỏ hàng hoặc xác thực người dùng
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền admin
$is_admin = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $is_admin = $user['is_admin'] == 1;
}

// Số sản phẩm mỗi trang
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Truy vấn để lấy tổng số sản phẩm
$total_sql = "SELECT COUNT(*) as total FROM products";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);

// Truy vấn để lấy sản phẩm theo lượt xem cao nhất với phân trang
$sql = "SELECT * FROM products ORDER BY views DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

if (!$result) {
    die("Lỗi truy vấn: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STHUYX Shop - PC & Phụ kiện Gaming</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/custom.js" defer></script>
    <script>
        // Cập nhật số lượng giỏ hàng
        function updateCartCount() {
            fetch('giohang/get_cart_count.php')
                .then(response => response.json())
                .then(data => {
                    const cartCount = document.querySelectorAll('.cart-count');
                    cartCount.forEach(element => {
                        element.textContent = data.cart_count || 0;
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        // Hiệu ứng fade-in cho sản phẩm
        function fadeInProducts() {
            const products = document.querySelectorAll('.product-item');
            products.forEach((product, index) => {
                setTimeout(() => {
                    product.classList.add('fade-in');
                }, index * 200); // Mỗi sản phẩm xuất hiện cách nhau 200ms
            });
        }

        // Hiệu ứng cho banner
        function animateBanners() {
            const banners = document.querySelectorAll('.banner-large, .banner-small');
            banners.forEach((banner, index) => {
                setTimeout(() => {
                    banner.classList.add('slide-in');
                }, index * 300); // Mỗi banner xuất hiện cách nhau 300ms
            });
        }

        window.onload = () => {
            updateCartCount();
            fadeInProducts();
            animateBanners();
        };
        setInterval(updateCartCount, 500);
    </script>
</head>
<body>
    <header>
        <!-- Thông báo thêm vào giỏ hàng -->
        <div id="cart-notification" style="display: none; position: fixed; top: 20px; right: 20px; padding: 10px 20px; color: white; background-color: #2ecc71; border-radius: 5px; z-index: 1000;">
            <span id="notification-text"></span>
        </div>
        <!-- Thanh thông tin trên cùng -->
        <div class="header-info">
            <div class="contact-info">
                <p>Hotline: 113 | Tư vấn Build PC: 13</p>
            </div>
        </div>
        <!-- Thanh chứa logo, tìm kiếm, và người dùng -->
        <div class="header-bottom">
            <div class="logo">
                <a href="index.php">
                    <img src="images/logo.png" alt="STHUYX Shop Logo" width="150">
                </a>
            </div>
            <div class="search">
                <form method="GET" action="sanpham/search.php">
                    <input type="text" name="query" placeholder="Tìm kiếm sản phẩm...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="user-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="nguoidung/account.php" class="user-button">Tài khoản</a>
                    <?php if ($is_admin): ?>
                        <a href="admin/index.php" class="user-button">Vào trang Admin</a>
                    <?php endif; ?>
                    <a href="nguoidung/logout.php" class="user-button">Đăng xuất</a>
                <?php else: ?>
                    <a href="nguoidung/login.php" class="user-button">Đăng nhập</a>
                    <a href="nguoidung/register.php" class="user-button">Đăng ký</a>
                <?php endif; ?>
                <a href="giohang/cart.php" class="cart">Giỏ hàng <span class="cart-count">0</span></a>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <!-- Sidebar: Danh mục sản phẩm -->
            <aside class="sidebar">
                <h3>DANH MỤC SẢN PHẨM</h3>
                <ul>
                    <li><a href="sanpham/products.php?category_id=1">CPU</a></li>
                    <li><a href="sanpham/products.php?category_id=2">VGA</a></li>
                    <li><a href="sanpham/products.php?category_id=3">SSD</a></li>
                    <li><a href="sanpham/products.php?category_id=4">HDD</a></li>
                    <li><a href="sanpham/products.php?category_id=5">Case</a></li>
                    <li><a href="sanpham/products.php?category_id=6">Màn hình</a></li>
                    <li><a href="sanpham/products.php?category_id=7">Ram</a></li>
                    <li><a href="sanpham/products.php?category_id=8">Mainboard</a></li>
                    <li class="more"><a href="#">+ Xem thêm</a></li>
                </ul>
            </aside>

            <!-- Nội dung chính -->
            <div class="main-content">
                <!-- Banners -->
                <section class="banners">
                    <div class="banner-large">
                        <img src="images/banner1.jpg" alt="Siêu Sale Tháng 3">
                    </div>
                    <div class="banner-small">
                        <img src="images/banner2.jpg" alt="PC AMD Gaming">
                    </div>
                    <div class="banner-small">
                        <img src="images/banner3.jpg" alt="Workstation 3D Render">
                    </div>
                </section>
                <!-- Sản phẩm nổi bật -->
                <section class="products">
                    <h2>Sản phẩm nổi bật</h2>
                    <div class="product-list">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='product-item'>";
                                echo "<a href='sanpham/product_detail.php?id=" . $row['id'] . "'>";
                                echo "<img src='images/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' style='width: 100%; height: auto; max-height: 200px; object-fit: cover;'>";
                                echo "</a>";
                                echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                                echo "<p class='product-price'>" . number_format($row['price'], 0, ',', '.') . " VNĐ</p>";
                                echo "<a href='giohang/add_to_cart.php?product_id=" . $row['id'] . "&quantity=1' class='add-to-cart' data-product-id='" . $row['id'] . "'>Thêm vào giỏ</a>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>Không có sản phẩm nào.</p>";
                        }
                        ?>
                    </div>
                    <div class="pagination-controls">
                        <button id="prev-btn" class="pagination-btn"><i class="fas fa-chevron-left"></i></button>
                        <button id="next-btn" class="pagination-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </section>
            </div>
        </div>
    </main>
    <!-- Đây là JS chuyển trang -->
    <script>
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const currentPage = <?php echo $page; ?>;
    const totalPages = <?php echo $total_pages; ?>;

    if (currentPage === 1) prevBtn.disabled = true;
    if (currentPage === totalPages) nextBtn.disabled = true;

    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            window.location.href = `index.php?page=${currentPage - 1}`;
        }
    });

    nextBtn.addEventListener('click', () => {
        if (currentPage < totalPages) {
            window.location.href = `index.php?page=${currentPage + 1}`;
        }
    });
    </script>
    <!-- Footer -->
    <?php include 'templates/footer.php'; ?>
</body>
</html>