<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';

$page_title = "Xóa Sản phẩm - Trang quản trị";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Xóa sản phẩm thành công!";
    } else {
        $_SESSION['message'] = "Xóa sản phẩm thất bại: " . $conn->error;
    }
    $stmt->close();
    header("Location: manage_products.php");
    exit();
}

$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
$message_class = $message && strpos($message, 'thành công') !== false ? 'success-message' : 'error-message';
unset($_SESSION['message']);

include 'html/delete_product_view.php';
?>