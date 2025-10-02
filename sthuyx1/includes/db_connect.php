<?php
$host = 'localhost';
$dbname = 'sthuyx1';
$username = 'root';
$password = '';

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Kết nối thất bại: " . $conn->connect_error);
    }
} catch (Exception $e) {
    error_log("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
    die("Kết nối cơ sở dữ liệu thất bại!");
}
?>