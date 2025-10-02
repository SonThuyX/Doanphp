<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM orders WHERE user_id = $user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của tôi</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Đơn hàng của tôi</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Ngày đặt</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . number_format($row['total'], 0, ',', '.') . ' VNĐ</td>';
                echo '<td>' . $row['status'] . '</td>';
                echo '<td>' . $row['created_at'] . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="4">Bạn chưa có đơn hàng nào.</td></tr>';
        }
        ?>
    </table>
</body>
</html>