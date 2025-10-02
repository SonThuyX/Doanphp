<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';

$page_title = "Đăng ký - STHUYX Shop";
include '../templates/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, trim($_POST['username'])) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, trim($_POST['email'])) : '';

    if (empty($username) || empty($password) || empty($email)) {
        $message = "Vui lòng nhập đầy đủ thông tin!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email không hợp lệ!";
    } else {
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $message = "Tên đăng nhập hoặc email đã tồn tại!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $hashed_password, $email);
            if ($stmt->execute()) {
                $message = "Đăng ký thành công! Vui lòng đăng nhập.";
                header("Location: login.php");
                exit();
            } else {
                $message = "Đăng ký thất bại: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
?>

<div class="register-section">
    <h2>ĐĂNG KÝ</h2>
    <?php if ($message): ?>
        <div class="message-box error"><?php echo $message; ?></div>
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
        <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
    </form>
</div>

<?php include '../templates/footer.php'; ?>