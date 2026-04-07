<?php
session_start();
header('Content-Type: application/json');
include('connect.php');
$data = json_decode(file_get_contents('php://input'),true);
$id = $data['id'];
if (isset($_SESSION['CART'][$id])) {
    $_SESSION['CART'][$id]['qty']--;
    if($_SESSION['CART'][$id]['qty'] == 0){
        unset($_SESSION['CART'][$id]);
    }
}
$cartcount=0;
$subtotal = 0;
foreach ($_SESSION['CART'] as $item) {
    $subtotal += $item['qty'] * $item['price'];
        $cartcount += $item['qty'];

}
$eco_tax = 2;
$total = $subtotal + $eco_tax;

echo json_encode([
    'status' => true,
    'message' => 'San pham da duoc giam so luong',
    'qty' => isset($_SESSION['CART'][$id]) ? $_SESSION['CART'][$id]['qty'] : 0,
    'lineTotal' => isset($_SESSION['CART'][$id]) ? $_SESSION['CART'][$id]['qty'] * $_SESSION['CART'][$id]['price'] : 0,
    'subtotal' => $subtotal,
    'total' => $total,
    'cartcount' => $cartcount,
]);

?>
