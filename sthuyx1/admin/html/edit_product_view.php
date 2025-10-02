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
                <h2>SỬA SẢN PHẨM</h2>
            </div>
            <div class="admin-section">
                <?php if ($message): ?>
                    <div class="<?php echo $message_class; ?>">
                        <p><?php echo htmlspecialchars($message); ?></p>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                <div class="form-group">
                        <label for="id">ID sản phẩm:</label>
                        <input type="number" id="id" name="id" value="<?php echo htmlspecialchars($product['id']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Tên sản phẩm:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Giá:</label>
                        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Đường dẫn hình ảnh:</label>
                        <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($product['image']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Danh mục:</label>
                        <select id="category_id" name="category_id" required>
                            <?php while ($category = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo $product['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit">Cập nhật sản phẩm</button>
                </form>
            </div>
        </div>
    </div>

    <?php include '../templates/footer.php'; ?>
</body>
</html>