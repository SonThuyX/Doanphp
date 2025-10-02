<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="../css/review.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Thông báo -->
    <div id="cart-notification" style="position: fixed; top: 60px; right: 20px; background-color: #2ecc71; color: white; padding: 10px 20px; border-radius: 5px; display: none; z-index: 1000; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);">
        <span id="notification-text"></span>
    </div>

    <div class="container">
        <div class="review-section">
            <h2>Đánh giá sản phẩm</h2>
            <?php if (isset($_SESSION['message'])): ?>
                <p class="message-box error"><?php echo htmlspecialchars($_SESSION['message']); ?></p>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if ($product): ?>
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($product['name'] ?? ''); ?></h3>
                    <?php if (isset($product['image']) && !empty($product['image'])): ?>
                        <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-width: 200px; height: auto;">
                    <?php else: ?>
                        <p>Không có hình ảnh sản phẩm.</p>
                    <?php endif; ?>
                </div>
                <form method="POST" action="">
                    <div class="rating">
                        <div class="rating-group">
                            <input type="radio" id="star1" name="rating" value="1" required>
                            <label for="star1"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star2" name="rating" value="2">
                            <label for="star2"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star3" name="rating" value="3">
                            <label for="star3"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star4" name="rating" value="4">
                            <label for="star4"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star5" name="rating" value="5">
                            <label for="star5"><i class="fas fa-star"></i></label>
                        </div>
                    </div>
                    <textarea name="comment" placeholder="Nhập đánh giá của bạn..." rows="4" required></textarea>
                    <button type="submit">Gửi đánh giá</button>
                </form>
                <a href="<?php echo isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : 'index.php'; ?>" class="back-button">Quay lại</a>
            <?php else: ?>
                <p>Sản phẩm không tồn tại hoặc không tìm thấy!</p>
                <a href="index.php" class="back-button">Quay lại</a>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="js/custom.js"></script>
</body>
</html>