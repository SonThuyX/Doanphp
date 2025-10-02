<?php include '../templates/header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Thêm thông báo hiệu ứng -->
    <div id="cart-notification" style="position: fixed; top: 60px; right: 20px; background-color: #2ecc71; color: white; padding: 10px 20px; border-radius: 5px; display: none; z-index: 1000; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);">
        <span id="notification-text"></span>
    </div>
    <main>
        <div class="container">
            <div class="login-section">
                <h2>Đăng nhập</h2>
                <?php if (isset($_SESSION['message'])): ?>
                    <p class="message-box error"><?php echo $_SESSION['message']; ?></p>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập hoặc Email:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit">Đăng nhập</button>
                </form>
                <p style="text-align: center; margin-top: 15px;">Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
            </div>
        </div>
    </main>
    <?php include '../templates/footer.php'; ?>
</body>
</html>