<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'ID sản phẩm không hợp lệ!'];

$product_id = null;

// Kiểm tra dữ liệu từ cả GET và POST
if (isset($_POST['id'])) {
    $product_id = intval($_POST['id']);
} elseif (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
}

if ($product_id > 0) {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        $cart_count = array_sum(array_column($_SESSION['cart'], 'quantity'));
        $response = [
            'success' => true,
            'message' => 'Đã xóa!',
            'cart_count' => $cart_count
        ];
    } else {
        $response['message'] = 'Đã xóa!'; // thông báo sản phẩm "Đã xóa!"
    }
}

echo json_encode($response);
exit();
?>