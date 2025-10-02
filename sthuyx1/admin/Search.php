<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';

$page_title = "Kết quả tìm kiếm - Linh Kiện PC";

$query = isset($_GET['query']) ? mysqli_real_escape_string($conn, trim($_GET['query'])) : '';

if ($query) {
    $sql = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?";
    $search_query = "%$query%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $search_query, $search_query);
    $stmt->execute();
    $result = $stmt->get_result();
    include 'html/search_results.php';
} else {
    $message = "Vui lòng nhập từ khóa tìm kiếm.";
    include 'html/search_results.php';
}