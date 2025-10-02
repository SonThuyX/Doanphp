<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';
include 'check_admin.php';
$page_title = "Báo cáo Doanh thu - Trang quản trị";
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

// Truy vấn báo cáo doanh thu
$sql = "SELECT DATE(created_at) as date, SUM(total) as total_revenue, COUNT(*) as order_count FROM orders GROUP BY DATE(created_at) ORDER BY date DESC LIMIT 5";
$result = $conn->query($sql);
$sales = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sales[] = $row;
    }
}

// Gán thông báo nếu có
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
$message_class = $message && strpos($message, 'thành công') !== false ? 'success-message' : 'error-message';
unset($_SESSION['message']);

// Bao gồm tệp giao diện HTML
include 'html/report_sales_view.php';
?>