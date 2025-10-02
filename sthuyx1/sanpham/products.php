<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';

$page_title = "Sản phẩm - Linh Kiện PC";
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
include '../templates/header.php';
include '../templates/products.php';