<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
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

// Kiểm tra quyền admin
if (!$user || $user['is_admin'] != 1) {
    header("Location: ../index.php");
    exit();
}
?>