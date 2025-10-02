<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';

$page_title = "Đăng nhập - STHUYX Shop";
include '../templates/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, trim($_POST['username'])) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($username) || empty($password)) {
        $message = "Vui lòng nhập đầy đủ thông tin!";
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: ../trangchu/index.php");
            exit();
        } else {
            $message = "Tên đăng nhập hoặc mật khẩu không đúng!";
        }
    }
}
?>

<div class="login-section">
    <h2>ĐĂNG NHẬP</h2>
    <?php if ($message): ?>
        <div class="message-box error"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Đăng nhập</button>
        <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
    </form>
</div>

<?php include '../templates/footer.php'; ?>