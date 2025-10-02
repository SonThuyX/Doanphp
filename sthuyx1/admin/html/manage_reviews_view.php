<?php include 'header_footer/header.php'; ?>

<link rel="stylesheet" href="css/admin.css">
<link rel="stylesheet" href="css/style.css">

<div class="admin-wrapper">
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
                <li><a href="manage_reviews.php" class="active"><i class="fas fa-star"></i> Quản lý Đánh giá</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="admin-content">
        <div class="content-header">
            <h2>Quản lý Đánh giá</h2>
        </div>
        <div class="table-container">
            <?php if (isset($message)): ?>
                <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-danger'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sản phẩm</th>
                        <th>Người dùng</th>
                        <th>Điểm đánh giá</th>
                        <th>Bình luận</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $review): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($review['id']); ?></td>
                            <td><?php echo htmlspecialchars($review['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($review['rating']); ?>/5</td>
                            <td><?php echo htmlspecialchars($review['comment'] ?? 'Không có bình luận'); ?></td>
                            <td><?php echo htmlspecialchars($review['created_at']); ?></td>
                            <td>
                                <a href="manage_reviews.php?delete_id=<?php echo $review['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa review này?');">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($reviews)): ?>
                        <tr>
                            <td colspan="7">Không có đánh giá nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>