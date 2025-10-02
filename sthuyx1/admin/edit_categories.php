<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';
include 'check_admin.php';
$page_title = "Sửa Danh mục - Trang quản trị";
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
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Chuyển đổi id thành số nguyên để tránh SQL Injection
    $sql = "SELECT * FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    $stmt->close();

    if (!$category) {
        $_SESSION['message'] = "Danh mục không tồn tại!";
        header("Location: manage_categories.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $sql = "UPDATE categories SET name = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $name, $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Cập nhật danh mục thành công!";
        } else {
            $_SESSION['message'] = "Cập nhật danh mục thất bại: " . $conn->error;
        }
        $stmt->close();
        header("Location: manage_categories.php");
        exit();
    }
} else {
    $_SESSION['message'] = "ID danh mục không hợp lệ!";
    header("Location: manage_categories.php");
    exit();
}

$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
$message_class = $message && strpos($message, 'thành công') !== false ? 'success-message' : 'error-message';
unset($_SESSION['message']);

include 'html/edit_categories_view.php';
?>