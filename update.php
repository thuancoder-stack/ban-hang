<?php
session_start();
include "connect.php";
//update
   	
    if(isset($_POST['submit'])){
        if (isset($_POST['submit'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($password != "") {
                $password = md5($password);
                $sql = "UPDATE user SET name='$name', email='$email', password='$password' WHERE id='$id'";
            } else {
                $sql = "UPDATE user SET name='$name', email='$email' WHERE id='$id'";
            }

    if ($con->query($sql) == TRUE) {
        echo "Update thanh cong";
        header("Location: account.php");
    } else {
        echo "Update that bai";
    }
}
    }
?>