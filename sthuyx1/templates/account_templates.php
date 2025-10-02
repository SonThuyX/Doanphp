<?php include '../templates/header.php'; ?>

<div class="container">
    <div class="order-history">
        <h2>Đơn đặt hàng</h2>
        <table class="order-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['id'] . '</td>';
                        echo '<td>' . number_format($row['total'], 0, ',', '.') . ' VNĐ</td>';
                        echo '<td>' . $row['status'] . '</td>';
                        echo '<td>' . $row['created_at'] . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">Bạn chưa có đơn hàng nào.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="account-edit">
        <h2>Sửa thông tin cá nhân</h2>
        <?php if (isset($message)) : ?>
            <div class="message-box <?php echo strpos($message, 'thành công') !== false ? '' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Phần hiển thị và đổi avatar -->
        <div class="avatar-section">
            <h3>Ảnh đại diện</h3>
            <div class="avatar-preview">
                <img src="<?php echo $user['avatar'] ? '../uploads/avatars/' . htmlspecialchars($user['avatar']) : '../images/default_avatar.png'; ?>" alt="Avatar" class="avatar-image">
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="avatar">Chọn ảnh mới</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*">
                </div>
                <button type="submit" name="update_avatar">Cập nhật Avatar</button>
            </form>
        </div>

        <!-- Form sửa thông tin cá nhân -->
        <h3>Thông tin cá nhân</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="full_name">Họ và tên</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="number" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <button type="submit" name="update_profile">Cập nhật</button>
        </form>
    </div>
</div>

<?php include '../templates/footer.php'; ?>