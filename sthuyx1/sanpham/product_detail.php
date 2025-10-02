<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';

// Lấy ID sản phẩm từ query string
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;
$reviews = [];
$config_options = [];
$additional_images = [];
$original_price = 0;

// Xử lý dữ liệu nếu ID sản phẩm hợp lệ
if ($product_id > 0) {
    // Lấy thông tin sản phẩm từ cơ sở dữ liệu
    $sql = "SELECT id, name, price, image, description, stock FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    // Tăng lượt xem sản phẩm
    $sql = "UPDATE products SET views = views + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();

    // Lấy danh sách đánh giá từ cơ sở dữ liệu
    $sql = "SELECT r.rating, r.comment, u.username, r.created_at 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = ? 
            ORDER BY r.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    $stmt->close();

    // Cấu hình tùy chọn sản phẩm (Dung lượng)
    $config_options = [
        '' => [
        ],
    ];

    // Tính giá gốc (giảm 29%)
    $original_price = $product ? $product['price'] * 1.29 : 0;

    // Danh sách hình ảnh bổ sung
    $additional_images = [
        $product['image'] ?? 'default_hdd_image.jpg',
        'hdd_image_2.jpg',
        'hdd_image_3.jpg',
    ];
}

// Đặt tiêu đề trang
$page_title = "Chi tiết sản phẩm - Linh Kiện PC";

// Bao gồm file header và template giao diện
include '../templates/header.php';
include '../templates/product_detail.php';
include '../templates/footer.php';