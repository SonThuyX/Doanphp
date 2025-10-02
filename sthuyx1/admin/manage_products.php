<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';
include 'check_admin.php';
$page_title = "Quản lý Sản phẩm - Trang quản trị";
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

// Xử lý tìm kiếm
$query = isset($_GET['query']) ? mysqli_real_escape_string($conn, trim($_GET['query'])) : '';
$sql = "SELECT * FROM products";
if ($query) {
    $search_query = "%$query%";
    $sql .= " WHERE name LIKE '$search_query' OR description LIKE '$search_query'";
}
$sql = "SELECT id, name, price, image FROM products"; // Thêm cột `image`
if ($query) {
    $search_query = "%$query%";
    $sql .= " WHERE name LIKE '$search_query' OR description LIKE '$search_query'";
}
$sql .= " ORDER BY id DESC";

$result = $conn->query($sql);
$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Gán thông báo nếu có
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
$message_class = $message && strpos($message, 'thành công') !== false ? 'success-message' : 'error-message';
unset($_SESSION['message']);

// Bao gồm tệp giao diện HTML
include 'html/manage_products_view.php';
?>