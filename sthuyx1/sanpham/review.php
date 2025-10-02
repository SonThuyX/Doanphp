<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sửa đường dẫn nếu cần
include '../includes/db_connect.php';

// Kiểm tra kết nối cơ sở dữ liệu
if (!isset($conn) || $conn->connect_error) {
    die("Không thể kết nối tới cơ sở dữ liệu: " . ($conn ? $conn->connect_error : "Biến \$conn không được định nghĩa"));
}

$page_title = "Đánh giá sản phẩm - Linh Kiện PC";

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Vui lòng đăng nhập để đánh giá!";
    header("Location: login.php");
    exit();
}

// Lấy thông tin sản phẩm
$product = null;
if ($product_id > 0) {
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Lỗi prepare statement: " . $conn->error);
    }
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

// Xử lý form đánh giá
if ($_SERVER["REQUEST_METHOD"] == "POST" && $product) {
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);

    if ($rating >= 1 && $rating <= 5) {
        $user_id = $_SESSION['user_id'];
        $sql = "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Lỗi prepare statement: " . $conn->error);
        }
        $stmt->bind_param("iiis", $product_id, $user_id, $rating, $comment);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Cảm ơn bạn đã đánh giá sản phẩm!";
            $stmt->close();
            header("Location: product_detail.php?id=" . $product_id);
            exit();
        } else {
            $_SESSION['message'] = "Đã xảy ra lỗi khi lưu đánh giá!";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Đánh giá phải từ 1 đến 5 sao!";
    }
}

// Bao gồm template HTML (điều chỉnh đường dẫn)
include '../templates/review_template.php';
?>