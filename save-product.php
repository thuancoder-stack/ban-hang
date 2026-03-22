<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include "connect.php";

$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : (int) $_SESSION['user']['id'];

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$price = isset($_POST['price']) ? (float) $_POST['price'] : 0;
$imagePath = null;

if ($name === '') {
    header("Location: add-product.php?status=empty_name");
    exit();
}

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
        header("Location: add-product.php?status=upload_failed");
        exit();
    }
}

$stmt = $con->prepare("INSERT INTO products (user_id, name, price, image) VALUES (?, ?, ?, ?)");
if ($stmt) {
    $stmt->bind_param("isds", $userId, $name, $price, $imagePath);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: my-product.php?status=added");
        exit();
    }

    $stmt->close();
}

header("Location: add-product.php?status=add_failed");
exit();