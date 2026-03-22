<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$statusMessage = "";
if (isset($_GET['status'])) {
    if ($_GET['status'] === "updated") {
        $statusMessage = "Update success.";
    } 
}
include "connect.php";

$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : (int) $_SESSION['user']['id'];

$productId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$price = isset($_POST['price']) ? (float) $_POST['price'] : 0;

if ($productId <= 0) {
    header("Location: my-product.php?status=invalid_id");
    exit();
}

if ($name === '') {
    header("Location: edit.php?id=" . $productId . "&status=empty_name");
    exit();
}

$currentProduct = null;
$checkStmt = $con->prepare("SELECT id, image FROM products WHERE id = ? AND user_id = ?");
if ($checkStmt) {
    $checkStmt->bind_param("ii", $productId, $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    if ($checkResult) {
        $currentProduct = $checkResult->fetch_assoc();
    }
    $checkStmt->close();
}

if (!$currentProduct) {
    header("Location: my-product.php?status=not_found");
    exit();
}

$imagePath = $currentProduct['image'];

if (!empty($_FILES['image']['name'])) {
    $uploadDir = "uploads/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES['image']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $imagePath = $targetFile;
    } else {
        header("Location: edit.php?id=" . $productId . "&status=upload_failed");
        exit();
    }
}

$updateStmt = $con->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ? AND user_id = ?");
if ($updateStmt) {
    $updateStmt->bind_param("sdsii", $name, $price, $imagePath, $productId, $userId);

    if ($updateStmt->execute()) {
        $updateStmt->close();
        header("Location: my-product.php?status=updated");
        exit();
    }

    $updateStmt->close();
}

header("Location: my-product.php?status=update_failed");
exit();