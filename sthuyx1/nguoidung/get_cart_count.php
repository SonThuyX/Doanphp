<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart_count = array_sum(array_column($_SESSION['cart'], 'quantity'));
echo json_encode(['cart_count' => $cart_count]);
exit();
?>