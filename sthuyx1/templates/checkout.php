<link rel="stylesheet" href="/sthuyx1/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="container">
    <div class="checkout-section">
        <h2>THANH TOÁN ĐƠN HÀNG</h2>
        <?php if (isset($error) && !empty($error)): ?>
            <div class="message-box error">
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>
        <div class="checkout-summary">
            <h3>Tóm tắt đơn hàng</h3>
            <table class="checkout-table">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo number_format($item['price'], 0, ',', '.') . ' VNĐ'; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($item['subtotal'], 0, ',', '.') . ' VNĐ'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3"><strong>Tổng cộng:</strong></td>
                        <td><strong><?php echo number_format($total, 0, ',', '.') . ' VNĐ'; ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="checkout-form">
            <h3>Thông tin giao hàng và thanh toán</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="address">Địa chỉ giao hàng:</label>
                    <textarea id="address" name="address" required placeholder="Nhập địa chỉ giao hàng của bạn"></textarea>
                </div>
                <div class="form-group">
                    <label for="payment_method">Phương thức thanh toán:</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="" disabled selected>Chọn phương thức thanh toán</option>
                        <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                        <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                        <option value="momo">Ví MoMo</option>
                    </select>
                </div>
                <div class="form-actions">
                    <a href="/sthuyx1/giohang/cart.php" class="back-to-cart"><i class="fas fa-arrow-left"></i> Quay lại giỏ hàng</a>
                    <button type="submit" class="confirm-order"><i class="fas fa-check"></i> Xác nhận đặt hàng</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
<script src="/sthuyx1/js/custom.js"></script>