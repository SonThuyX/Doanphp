<?php
// Khởi động session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Debug
error_log("get_cart_count.php accessed");

// Tính tổng số lượng sản phẩm trong giỏ hàng
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cart_count = array_sum(array_column($_SESSION['cart'], 'quantity'));
}

$response = [
    'cart_count' => $cart_count
];

error_log("Cart count response: " . print_r($response, true));

echo json_encode($response);
exit();
?>