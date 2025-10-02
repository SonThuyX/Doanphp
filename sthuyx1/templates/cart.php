<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <!-- Thông báo thêm vào giỏ hàng -->
        <div id="cart-notification" style="display: none; position: fixed; top: 20px; right: 20px; padding: 10px 20px; color: white; border-radius: 5px; z-index: 1000;">
            <span id="notification-text"></span>
        </div>
<div class="container">
    <div class="cart-section">
        <h2>Giỏ hàng của bạn</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <p class="message-box <?php echo strpos($_SESSION['message'], 'thành công') !== false ? '' : 'error'; ?>"><?php echo $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $session_product_id => $item) {
                        if (isset($item['product_id'])) {
                            $product_id = (int)$item['product_id'];
                            $sql = "SELECT * FROM products WHERE id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $product_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $product = $result->fetch_assoc();
                            $stmt->close();

                            if ($product) {
                                $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
                                $subtotal = $product['price'] * $quantity;
                                $total += $subtotal;
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($product['name']) . "</td>";
                                echo "<td>" . number_format($product['price'], 0, ',', '.') . " VNĐ</td>";
                                echo "<td>" . htmlspecialchars($quantity) . "</td>";
                                echo "<td>" . number_format($subtotal, 0, ',', '.') . " VNĐ</td>";
                                echo "<td><a href='#' class='remove-item' data-product-id='" . htmlspecialchars((string)$session_product_id) . "'><i class='fas fa-trash'></i></a></td>";
                                echo "</tr>";
                            }
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Tổng cộng:</strong></td>
                        <td><strong><?php echo number_format($total, 0, ',', '.') . " VNĐ"; ?></strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="cart-actions">
                <a href="../index.php" class="cart-button"><i class="fas fa-arrow-left"></i> Tiếp tục mua sắm</a>
                <a href="checkout.php" class="cart-button checkout"><i class="fas fa-check"></i> Thanh toán</a>
            </div>
        <?php else: ?>
            <p>Giỏ hàng của bạn trống.</p>
            <a href="../index.php" class="cart-button"><i class="fas fa-arrow-left"></i> Tiếp tục mua sắm</a>
        <?php endif; ?>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
<script src="../js/custom.js"></script>