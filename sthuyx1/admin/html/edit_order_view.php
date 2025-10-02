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
        <ul class="sidebar-menu">
        <div class="user-profile">
                <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" class="user-avatar">
                <span class="username"><?php echo htmlspecialchars($username); ?></span>
            </div>
            <li><a href="manage_products.php"><i class="fas fa-box"></i> Quản lý Sản phẩm</a></li>
            <li><a href="add_product.php"><i class="fas fa-plus-circle"></i> Thêm Sản phẩm</a></li>
            <li><a href="manage_categories.php"><i class="fas fa-list"></i> Quản lý Danh mục</a></li>
            <li><a href="manage_orders.php" class="active"><i class="fas fa-shopping-cart"></i> Quản lý Đơn hàng</a></li>
            <li><a href="manage_promotions.php"><i class="fas fa-tags"></i> Quản lý Khuyến mãi</a></li>
            <li><a href="report_sales.php"><i class="fas fa-chart-bar"></i> Báo cáo Doanh thu</a></li>
            <li><a href="manage_reviews.php"><i class="fas fa-star"></i> Quản lý đánh giá</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="admin-content">
        <div class="content-header">
            <h2>Chỉnh sửa Đơn hàng #<?php echo htmlspecialchars($order_id); ?></h2>
        </div>
        <div class="table-container">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <!-- Thông tin đơn hàng -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="user_id">Người đặt hàng (ID):</label>
                    <input type="text" id="user_id" value="<?php echo htmlspecialchars($order['user_id']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="total">Tổng tiền:</label>
                    <input type="text" id="total" value="<?php echo number_format($order['total'], 0, ',', '.') . ' VNĐ'; ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="address">Địa chỉ:</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($order['address']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="payment_method">Phương thức thanh toán:</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="cod" <?php echo $order['payment_method'] === 'cod' ? 'selected' : ''; ?>>Thanh toán khi nhận hàng (COD)</option>
                        <option value="bank_transfer" <?php echo $order['payment_method'] === 'bank_transfer' ? 'selected' : ''; ?>>Chuyển khoản ngân hàng</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Trạng thái:</label>
                    <select id="status" name="status" required>
                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Đang chờ xử lý</option>
                        <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="created_at">Ngày tạo:</label>
                    <input type="text" id="created_at" value="<?php echo htmlspecialchars($order['created_at']); ?>" disabled>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật Đơn hàng</button>
            </form>

            <!-- Chi tiết đơn hàng -->
            <h3>Chi tiết Đơn hàng</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_details as $detail): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($detail['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($detail['quantity']); ?></td>
                            <td><?php echo number_format($detail['price'], 0, ',', '.') . ' VNĐ'; ?></td>
                            <td><?php echo number_format($detail['price'] * $detail['quantity'], 0, ',', '.') . ' VNĐ'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($order_details)): ?>
                        <tr>
                            <td colspan="4">Không có chi tiết đơn hàng.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>