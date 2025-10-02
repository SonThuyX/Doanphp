<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';
include 'check_admin.php';
// Kiểm tra xem người dùng đã đăng nhập và có quyền admin không
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Lấy thông tin người dùng để kiểm tra quyền admin
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT is_admin, username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || $user['is_admin'] != 1) {
    header("Location: ../index.php");
    exit();
}
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

// Xử lý xóa review
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
     
    if ($stmt->execute()) {
        $message = "Xóa review thành công!";
    } else {
        $message = "Có lỗi xảy ra khi xóa review.";
    }
    $stmt->close();
}

// Lấy danh sách reviews
$stmt = $conn->prepare("
    SELECT r.*, p.name AS product_name, u.username AS user_name 
    FROM reviews r
    JOIN products p ON r.product_id = p.id
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
");
$stmt->execute();
$reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$page_title = "Quản lý Đánh giá - Linh Kiện PC";

// Chuyển hướng sang file giao diện
include 'html/manage_reviews_view.php';
?>