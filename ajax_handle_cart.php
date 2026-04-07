<?php
session_start(); //khởi động session
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'),true); //lấy dữ liệu từ request body và giải mã JSON thành mảng PHP
$id = $data['id'];

include "connect.php";
$sql_select = "SELECT * FROM products WHERE id = $id ";
$result = $con->query($sql_select);
if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $product = [
        'id' => $row['id'],
        'name' => $row['name'],
        'price' => $row['price'],
        'image' => $row['image'],
        'qty' => 1
    ];
    if(isset($_SESSION['CART'][$id])){
        $_SESSION['CART'][$id]['qty'] += 1; //tăng số lượng nếu sản phẩm đã tồn tại trong giỏ hàng
    } else {
        $_SESSION['CART'][$id] = $product; //thêm sản phẩm mới vào giỏ hàng
    }
    
}
$cartcount = 0;
if(isset($_SESSION['CART'])){
    foreach($_SESSION['CART'] as $item){
        $cartcount += $item['qty'];
    }
}
echo json_encode([
    'status' => true,
    'message' => 'San pham da duoc them vao gio hang',
    'cartcount' => $cartcount
]);
?>