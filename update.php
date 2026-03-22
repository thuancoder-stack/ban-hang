<?php
session_start();
include "connect.php";

if(isset($_POST['submit'])){
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['id'])){
        echo "<script>alert('Please fill in all fields');</script>";
    }else{
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];

        if(!empty($_POST['password'])){
            $password = md5($_POST['password']);
            $sql = "UPDATE user SET name='$name', email='$email', password='$password' WHERE id='$id'";
        } else {
            $sql = "UPDATE user SET name='$name', email='$email' WHERE id='$id'";
        }

        if(mysqli_query($con, $sql)){
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;
            if(!empty($_POST['password'])){
                $_SESSION['user']['password'] = $password;
            }
            echo "<script>
                    alert('Update thành công!');
                    window.location='account.php';
                  </script>";
            
        } else {
            echo "Lỗi: " . mysqli_error($con);
        }
    }
}
?>