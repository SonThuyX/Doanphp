<?php include 'header_footer/header.php';?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="../admin/css/admin.css">
    <link rel="stylesheet" href="../admin/css/style.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar">
        <div class="logo">
                <a href="index.php">
                    <img src="../images/logo.png" alt="STHUYX Shop Logo" width="150">
                </a>
            </div>
            <ul class="sidebar-menu">
            <div class="user-profile">
                <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" class="user-avatar">
                <span class="username"><?php echo htmlspecialchars($username); ?></span>
            </div>
            <li><a href="manage_products.php"><i class="fas fa-box"></i> Quản lý Sản phẩm</a></li>
            <li><a href="add_product.php"><i class="fas fa-plus-circle"></i> Thêm Sản phẩm</a></li>
            <li><a href="manage_categories.php"><i class="fas fa-list"></i> Quản lý Danh mục</a></li>
            <li><a href="manage_orders.php"><i class="fas fa-shopping-cart"></i> Quản lý Đơn hàng</a></li>
            <li><a href="manage_promotions.php"><i class="fas fa-tags"></i> Quản lý Khuyến mãi</a></li>
            <li><a href="report_sales.php" class="active"><i class="fas fa-chart-bar"></i> Báo cáo Doanh thu</a></li>
            <li><a href="manage_reviews.php"><i class="fas fa-star"></i> Quản lý Đánh giá</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="admin-content">
            <div class="content-header">
                <h2>BÁO CÁO DOANH THU</h2>
            </div>
            <div class="admin-section">
                <?php if ($message): ?>
                    <div class="<?php echo $message_class; ?>">
                        <p><?php echo htmlspecialchars($message); ?></p>
                    </div>
                <?php endif; ?>

                <div class="table-container">
                    <h3>Báo cáo Doanh thu (Dự kiến)</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Tổng doanh thu</th>
                                <th>Số đơn hàng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($sales) > 0): ?>
                                <?php foreach ($sales as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                                        <td><?php echo number_format($row['total_revenue'], 0, ',', '.') . " VNĐ"; ?></td>
                                        <td><?php echo htmlspecialchars($row['order_count']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3">Không có dữ liệu doanh thu.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include '../templates/footer.php'; ?>
</body>
</html>