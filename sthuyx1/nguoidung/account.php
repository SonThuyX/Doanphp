<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';

// Kiểm tra kết nối cơ sở dữ liệu
if (!$conn) {
    die("Kết nối cơ sở dữ liệu thất bại: " . mysqli_connect_error());
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin người dùng
$sql_user = "SELECT username, email, full_name, address, phone, avatar FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);

if ($stmt_user === false) {
    die("Lỗi prepare: " . $conn->error);
}

$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_result = $stmt_user->get_result();

if ($user_result->num_rows == 0) {
    die("Không tìm thấy người dùng với ID: " . $user_id);
}

$user = $user_result->fetch_assoc();

// Xử lý cập nhật thông tin cá nhân
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $sql_update = "UPDATE users SET full_name = ?, address = ?, phone = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);

    if ($stmt_update === false) {
        die("Lỗi prepare update: " . $conn->error);
    }

    $stmt_update->bind_param("sssi", $full_name, $address, $phone, $user_id);
    
    if ($stmt_update->execute()) {
        $message = "Cập nhật thông tin thành công!";
    } else {
        $message = "Có lỗi xảy ra, vui lòng thử lại.";
    }
    // Cập nhật lại thông tin người dùng sau khi thay đổi
    $stmt_user->execute();
    $user_result = $stmt_user->get_result();
    $user = $user_result->fetch_assoc();
}

// Xử lý đổi avatar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_avatar'])) {
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        $file_type = $_FILES['avatar']['type'];
        $file_size = $_FILES['avatar']['size'];
        $file_tmp = $_FILES['avatar']['tmp_name'];
        $file_name = $_FILES['avatar']['name'];

        // Kiểm tra loại file và kích thước
        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            // Tạo tên file duy nhất để tránh trùng lặp
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_file_name = $user_id . '_' . time() . '.' . $file_ext;
            $upload_dir = '../uploads/avatars/';
            $upload_path = $upload_dir . $new_file_name;

            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Xóa avatar cũ nếu có
            if ($user['avatar'] && file_exists($upload_dir . $user['avatar'])) {
                unlink($upload_dir . $user['avatar']);
            }

            // Di chuyển file vào thư mục uploads
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Cập nhật đường dẫn avatar vào cơ sở dữ liệu
                $sql_update_avatar = "UPDATE users SET avatar = ? WHERE id = ?";
                $stmt_update_avatar = $conn->prepare($sql_update_avatar);
                $stmt_update_avatar->bind_param("si", $new_file_name, $user_id);

                if ($stmt_update_avatar->execute()) {
                    $message = "Cập nhật avatar thành công!";
                } else {
                    $message = "Có lỗi xảy ra khi cập nhật avatar.";
                }
                $stmt_update_avatar->close();

                // Cập nhật lại thông tin người dùng
                $stmt_user->execute();
                $user_result = $stmt_user->get_result();
                $user = $user_result->fetch_assoc();
            } else {
                $message = "Không thể tải lên file.";
            }
        } else {
            $message = "File không hợp lệ. Chỉ chấp nhận JPEG, PNG, GIF và kích thước tối đa 5MB.";
        }
    } else {
        $message = "Vui lòng chọn một file để tải lên.";
    }
}

// Lấy danh sách đơn hàng
$sql = "SELECT * FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Lỗi prepare orders: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$page_title = "Đơn đặt hàng";

// Chuyển hướng sang file giao diện
include '../templates/account_templates.php';
?>