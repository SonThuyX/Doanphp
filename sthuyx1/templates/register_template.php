<?php include '../templates/header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="register-section">
            <h2>Đăng ký</h2>
            <?php if (isset($_SESSION['message'])): ?>
                <p class="message-box error"><?php echo $_SESSION['message']; ?></p>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Tên đăng nhập:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Đăng ký</button>
            </form>
            <p style="text-align: center; margin-top: 10px;">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
        </div>
    </div>

    <?php include '../templates/footer.php'; ?>
</body>
</html>