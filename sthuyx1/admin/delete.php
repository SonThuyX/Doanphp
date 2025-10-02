<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';
include 'check_admin.php';
$page_title = "Xóa Sản phẩm - Trang quản trị";
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
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Xóa sản phẩm thành công!";
    } else {
        $_SESSION['message'] = "Xóa sản phẩm thất bại: " . $conn->error;
    }
    $stmt->close();
} else {
    $_SESSION['message'] = "ID sản phẩm không hợp lệ!";
}

header("Location: manage_products.php"); // Chuyển hướng về trang quản lý sản phẩm
exit();
?>