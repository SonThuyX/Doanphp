<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';

header('Content-Type: application/json');

// Bật chế độ debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ghi log để kiểm tra dữ liệu nhận được
error_log("Yêu cầu: " . print_r($_REQUEST, true));

$response = ['success' => false, 'message' => 'Phương thức không hợp lệ!'];

$product_id = null;
$quantity = 1;

// Kiểm tra dữ liệu từ cả GET và POST
if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
} elseif (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;
}

if ($product_id > 0) {
    error_log("Product ID: $product_id, Quantity: $quantity");

    try {
        if ($conn === null || $conn->connect_error) {
            throw new Exception('Kết nối cơ sở dữ liệu thất bại: ' . ($conn->connect_error ?? 'Không có kết nối'));
        }

        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Lỗi chuẩn bị câu lệnh SQL: ' . $conn->error);
        }

        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();

            // Kiểm tra tồn kho
            if ($product['stock'] < $quantity) {
                $response['message'] = 'Số lượng vượt quá tồn kho! Tồn kho hiện tại: ' . $product['stock'];
            } else {
                if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                $cart_item = [
                    'product_id' => $product_id,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'image' => $product['image']
                ];

                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][$product_id] = $cart_item;
                }

                $cart_count = array_sum(array_column($_SESSION['cart'], 'quantity'));
                $response = [
                    'success' => true,
                    'message' => 'Thêm vào giỏ hàng thành công!',
                    'cart_count' => $cart_count
                ];
            }
        } else {
            $response['message'] = 'Sản phẩm không tồn tại!';
        }

        $stmt->close();
    } catch (Exception $e) {
        $response['message'] = 'Lỗi server: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'ID sản phẩm không hợp lệ!';
}

echo json_encode($response);
exit();
?>