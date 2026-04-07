<?php
session_start();
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'),true);
$id = $data['id'];
if(isset($_SESSION['CART'][$id])){
    unset($_SESSION['CART'][$id]);
}

$subtotal = 0;
$cartcount=0;
foreach ($_SESSION['CART'] as $item) {
    $subtotal += $item['qty'] * $item['price'];
    $cartcount += $item['qty'];
}
$eco_tax = 2;
$total = $subtotal + $eco_tax;

echo json_encode([
    'status' => true,
    'message' => 'San pham da duoc xoa khoi gio hang',
    'subtotal' => $subtotal,
    'total' => $total,
    'cartcount' => $cartcount,
]);


