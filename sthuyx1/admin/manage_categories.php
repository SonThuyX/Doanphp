<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';
include 'check_admin.php';
$page_title = "Quản lý Danh mục - Trang quản trị";
// Lấy thông tin người dùng từ cơ sở dữ liệu
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, avatar FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Nếu không có avatar, sử dụng ảnh mặc định
$avatar = $user['avatar'] ? "../uploads/avatars/{$user['avatar']}" : "../images/default_avatar.png";
$username = $user['username'] ?? 'Người dùng';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $sql = "INSERT INTO categories (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Thêm danh mục thành công!";
    } else {
        $_SESSION['message'] = "Thêm danh mục thất bại: " . $conn->error;
    }
    $stmt->close();
    header("Location: manage_categories.php");
    exit();
}

$sql = "SELECT * FROM categories ORDER BY id DESC";
$result = $conn->query($sql);
$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
$message_class = $message && strpos($message, 'thành công') !== false ? 'success-message' : 'error-message';
unset($_SESSION['message']);

include 'html/manage_categories_view.php';
?>