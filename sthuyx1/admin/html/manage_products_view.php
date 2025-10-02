<?php include 'header_footer/header.php';?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="../admin/css/admin.css">
    <link rel="stylesheet" href="../admin/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            <li><a href="manage_products.php" class="active"><i class="fas fa-box"></i> Quản lý Sản phẩm</a></li>
            <li><a href="add_product.php"><i class="fas fa-plus-circle"></i> Thêm Sản phẩm</a></li>
            <li><a href="manage_categories.php"><i class="fas fa-list"></i> Quản lý Danh mục</a></li>
            <li><a href="manage_orders.php"><i class="fas fa-shopping-cart"></i> Quản lý Đơn hàng</a></li>
            <li><a href="manage_promotions.php"><i class="fas fa-tags"></i> Quản lý Khuyến mãi</a></li>
            <li><a href="report_sales.php"><i class="fas fa-chart-bar"></i> Báo cáo Doanh thu</a></li>
            <li><a href="manage_reviews.php"><i class="fas fa-star"></i> Quản lý đánh giá</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="admin-content">
            <div class="content-header">
                <h2>QUẢN LÝ SẢN PHẨM</h2>
                <div class="search">
                <form method="GET" action="manage_products.php">
                    <input type="text" name="query" placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($query); ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>  
            </div>
            <div class="admin-section">
                <?php if ($message): ?>
                    <div class="<?php echo $message_class; ?>">
                        <p><?php echo htmlspecialchars($message); ?></p>
                    </div>
                <?php endif; ?>

                <div class="table-container">
                    <h3>Danh sách Sản phẩm <?php echo $query ? ' - Kết quả tìm kiếm cho: "' . htmlspecialchars($query) . '"' : ''; ?></h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php if (count($products) > 0): ?>
        <?php foreach ($products as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td>
                    <?php 
                    $imagePath = "../images/" . htmlspecialchars($row['image']);
                    if ($row['image']) {
                        if (file_exists($imagePath)) {
                            echo "<img src='$imagePath' alt='" . htmlspecialchars($row['name']) . "' width='100'>";
                        } else {
                            echo "Hình ảnh không tồn tại: " . $imagePath . "<br>";
                            echo "<img src='../images/default/no-image.jpg' alt='No Image' width='100'>";
                        }
                    } else {
                        echo "Không có hình ảnh<br>";
                        echo "<img src='../images/default/no-image.jpg' alt='No Image' width='100'>";
                    }
                    ?>
                </td> <!-- Hiển thị hình ảnh -->
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo number_format($row['price'], 0, ',', '.') . " VNĐ"; ?></td>
                <td class="action-links">
                    <a href="edit_product.php?id=<?php echo $row['id']; ?>">Sửa</a> | 
                    <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc chắn?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5">Không tìm thấy sản phẩm nào.</td></tr> <!-- Cập nhật colspan thành 5 -->
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