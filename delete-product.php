<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$statusMessage = "";
if (isset($_GET['status'])) {
    if ($_GET['status'] === "deleted") {
        $statusMessage = "Xoa san pham thanh cong.";
    } 
}
include "connect.php";

$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : (int) $_SESSION['user']['id'];
$productId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($productId <= 0) {
    header("Location: my-product.php");
    exit();
}

$stmt = $con->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $productId, $userId);
$stmt->execute();
$stmt->close();

header("Location: my-product.php?status=deleted");
exit();
?>