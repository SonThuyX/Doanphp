<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'check_admin.php';
// Lấy thông tin người dùng từ cơ sở dữ liệu
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, avatar FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Nếu không có avatar, sử dụng ảnh mặc định
$avatar = $user['avatar'] ? "../uploads/avatars/{$user['avatar']}" : "../images/default_avatar.png";
$username = $user['username'] ?? 'Người dùng';

// Truy vấn tổng số sản phẩm
$stmt = $conn->prepare("SELECT COUNT(*) as total_products FROM products");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_products = $row['total_products'];
$stmt->close();

// Truy vấn số đơn hàng mới (trạng thái 'pending')
$stmt = $conn->prepare("SELECT COUNT(*) as new_orders FROM orders WHERE status = 'pending'");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$new_orders = $row['new_orders'];
$stmt->close();

// Truy vấn doanh thu hôm nay (trạng thái 'completed')
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT SUM(total) as daily_revenue FROM orders WHERE status = 'completed' AND DATE(created_at) = ?");
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$daily_revenue = $row['daily_revenue'] ?? 0;
$daily_revenue_formatted = number_format($daily_revenue, 0, ',', '.') . ' VNĐ';
$stmt->close();

$page_title = "Trang quản trị - Linh Kiện PC";
include 'header_footer/header.php';
?>

<link rel="stylesheet" href="css/admin.css">
<link rel="stylesheet" href="css/style.css">

<div class="admin-wrapper">
    <!-- Loader -->
    <div class="loader" id="loader">
        <div class="spinner"></div>
    </div>

    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="logo">
            <a href="index.php">
                <img src="../images/logo.png" alt="STHUYX Shop Logo" width="150">
            </a>
        </div>
        <div class="sidebar-menu">
            <!-- Phần hiển thị avatar và tên đăng nhập -->
            <div class="user-profile">
                <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" class="user-avatar">
                <span class="username"><?php echo htmlspecialchars($username); ?></span>
            </div>
            <ul>
                <li><a href="manage_products.php"><i class="fas fa-box"></i> Quản lý Sản phẩm</a></li>
                <li><a href="add_product.php"><i class="fas fa-plus-circle"></i> Thêm Sản phẩm</a></li>
                <li><a href="manage_categories.php"><i class="fas fa-list"></i> Quản lý Danh mục</a></li>
                <li><a href="manage_orders.php"><i class="fas fa-shopping-cart"></i> Quản lý Đơn hàng</a></li>
                <li><a href="manage_promotions.php"><i class="fas fa-tags"></i> Quản lý Khuyến mãi</a></li>
                <li><a href="report_sales.php"><i class="fas fa-chart-bar"></i> Báo cáo Doanh thu</a></li>
                <li><a href="manage_reviews.php"><i class="fas fa-star"></i> Quản lý Đánh giá</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="admin-content">
        <div class="content-header">
            <h2>Chào mừng đến với Trang Quản trị</h2>
        </div>
        <div class="table-container">
            <h3>Tổng quan</h3>
            <p>Đây là trang quản trị của cửa hàng Linh Kiện PC. Bạn có thể quản lý sản phẩm, danh mục, đơn hàng, khuyến mãi, và xem báo cáo doanh thu từ menu bên trái.</p>
            <div class="dashboard-stats">
                <div class="stat-box">
                    <h4>Tổng Sản phẩm</h4>
                    <p><?php echo htmlspecialchars($total_products); ?></p>
                </div>
                <div class="stat-box">
                    <h4>Đơn hàng Mới</h4>
                    <p><?php echo htmlspecialchars($new_orders); ?></p>
                </div>
                <div class="stat-box">
                    <h4>Doanh thu Hôm nay</h4>
                    <p><?php echo htmlspecialchars($daily_revenue_formatted); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        const loader = document.getElementById('loader');
        loader.style.display = 'none';
    });
</script>

<?php include '../templates/footer.php'; ?>