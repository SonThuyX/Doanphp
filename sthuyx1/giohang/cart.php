<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connect.php';

$page_title = "Giỏ hàng - Linh Kiện PC";
include '../templates/header.php';
include '../templates/cart.php';