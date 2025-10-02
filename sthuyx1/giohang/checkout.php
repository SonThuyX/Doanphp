<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';

$page_title = "Thanh toán - Linh Kiện PC";
include '../templates/header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['message'] = "Vui lòng đăng nhập và thêm sản phẩm vào giỏ hàng trước khi thanh toán!";
    header("Location: /sthuyx1/giohang/cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total = 0;
$cart_items = [];

foreach ($_SESSION['cart'] as $item) {
    if (isset($item['product_id'])) {
        $product_id = (int)$item['product_id'];
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();

        if ($product) {
            $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
            $subtotal = $product['price'] * $quantity;
            $total += $subtotal;
            $cart_items[] = [
                'product_id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = isset($_POST['address']) ? mysqli_real_escape_string($conn, trim($_POST['address'])) : '';
    $payment_method = isset($_POST['payment_method']) ? mysqli_real_escape_string($conn, trim($_POST['payment_method'])) : '';

    if (empty($address)) {
        $error = "Vui lòng nhập địa chỉ giao hàng!";
    } elseif (empty($payment_method)) {
        $error = "Vui lòng chọn phương thức thanh toán!";
    } elseif (empty($cart_items)) {
        $error = "Giỏ hàng trống hoặc sản phẩm không hợp lệ!";
    } else {
        // Bắt đầu giao dịch để đảm bảo tính toàn vẹn dữ liệu
        $conn->begin_transaction();

        try {
            // Lưu đơn hàng vào bảng orders
            $sql = "INSERT INTO orders (user_id, total, address, payment_method, status) VALUES (?, ?, ?, ?, 'pending')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiss", $user_id, $total, $address, $payment_method);
            if (!$stmt->execute()) {
                throw new Exception("Lỗi chèn order: " . $stmt->error);
            }
            $order_id = $conn->insert_id;
            $stmt->close();

            // Lưu chi tiết đơn hàng vào bảng order_details
            $sql = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            foreach ($cart_items as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
                $stmt->execute();
            }
            $stmt->close();

            // Cập nhật lượt mua (purchases) cho từng sản phẩm
            $sql = "UPDATE products SET purchases = purchases + ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            foreach ($cart_items as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $stmt->bind_param("ii", $quantity, $product_id);
                if (!$stmt->execute()) {
                    throw new Exception("Lỗi cập nhật purchases cho sản phẩm ID $product_id: " . $stmt->error);
                }
            }
            $stmt->close();

            // Xác nhận giao dịch
            $conn->commit();

            // Xóa giỏ hàng sau khi đặt hàng thành công
            unset($_SESSION['cart']);
            $_SESSION['message'] = "Đặt hàng thành công! Mã đơn hàng của bạn là #$order_id.";
            header("Location: ../sanpham/review.php?product_id={$cart_items[0]['product_id']}&order_id=$order_id");
            exit();
        } catch (Exception $e) {
            // Nếu có lỗi, rollback giao dịch
            $conn->rollback();
            $error = $e->getMessage();
        }
    }
}
include '../templates/checkout.php';
?>