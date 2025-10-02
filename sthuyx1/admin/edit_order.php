<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';
include 'check_admin.php';
// Kiểm tra xem có tham số id được truyền qua URL không
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_orders.php");
    exit();
}

$order_id = (int)$_GET['id'];

// Lấy thông tin đơn hàng
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

// Kiểm tra xem đơn hàng có tồn tại không
if (!$order) {
    header("Location: manage_orders.php");
    exit();
}

// Lấy chi tiết đơn hàng
$stmt = $conn->prepare("
    SELECT od.*, p.name as product_name 
    FROM order_details od 
    JOIN products p ON od.product_id = p.id 
    WHERE od.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_details = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Xử lý cập nhật đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $payment_method = $_POST['payment_method'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE orders SET status = ?, payment_method = ?, address = ? WHERE id = ?");
    $stmt->bind_param("sssi", $status, $payment_method, $address, $order_id);
    
    if ($stmt->execute()) {
        // Chuyển hướng về trang quản lý đơn hàng với thông báo thành công
        header("Location: manage_orders.php?success=Đơn hàng đã được cập nhật thành công");
    } else {
        $error = "Có lỗi xảy ra khi cập nhật đơn hàng: " . $stmt->error;
    }
    $stmt->close();
}

// Đặt tiêu đề trang
$page_title = "Chỉnh sửa Đơn hàng - Linh Kiện PC";

// Chuyển dữ liệu sang file giao diện
include 'html/edit_order_view.php';
?>