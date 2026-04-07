<?php
session_start();
include "connect.php";
$id = $_GET['id'];
    $sql_delete = "DELETE FROM products WHERE id='$id'";
    $result = $con->query($sql_delete);
    if ($result) {
        echo "Product deleted successfully";
        header("Location: my-product.php");
    } else {
        echo "Error deleting product: " . $con->error;
    }
?>