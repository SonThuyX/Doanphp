<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';
include 'check_admin.php';
$page_title = "Quản lý Đơn hàng - Trang quản trị";
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
$sql = "SELECT o.id, u.username, o.total, o.status FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC";
$result = $conn->query($sql);
$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
$message_class = $message && strpos($message, 'thành công') !== false ? 'success-message' : 'error-message';
unset($_SESSION['message']);

include 'html/manage_orders_view.php';
?>