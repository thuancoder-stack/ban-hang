<?php
    include "connect.php";
    if(isset($_POST['submit'])){
        if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_FILES['avatar']['name'])){
            echo "<script>alert('Please fill in all fields');</script>";
        }else{
            if (!empty($_FILES['avatar']['name'])) {
            // Lấy thông tin file
            $name     = $_FILES['avatar']['name'];       // tên file
            $type     = $_FILES['avatar']['type'];       // kiểu file (image/png,...)
            $tmp_name = $_FILES['avatar']['tmp_name'];   // file tạm
            $error    = $_FILES['avatar']['error'];      // 0 = không lỗi
            $size     = $_FILES['avatar']['size'];       // byte
            // Kiểm tra lỗi
            if($_FILES['avatar']['size'] > 1024 * 1024){
                echo "<script>alert('File size must be less than 1MB');</script>";
            }
            if ($error > 0) {
                echo "File Upload Bị Lỗi!";
            } else {
                // Di chuyển file từ tạm -> folder upload
                move_uploaded_file($tmp_name, './upload/' . $name);
                echo "File Uploaded!";}
            } 
            $name = $_POST['name'];
            $email = $_POST['email'];
            //pass mã hoá md5
            $password = md5($_POST['password']);
            $avatar = $_FILES['avatar']['name'];
          
            $sql = "INSERT INTO user (name, email, password, avatar) VALUES ('$name', '$email', '$password', '$avatar')";
            $result = $con->query($sql);
            if($result){
                echo "<script>alert('Register successful'); window.location='login.php';</script>";
            }else{
                echo "<script>alert('Register failed');</script>";
            }   

        }
    }
    ?>
    <form method="post" enctype="multipart/form-data">
		<input type="text" name="name" placeholder="Name"/>
		<input type="email" name="email" placeholder="Email Address"/>
		<input type="password" name="password" placeholder="Password"/>
		<input type="file" name="avatar" placeholder="Avatar"/>
		<button type="submit" name="submit" class="btn btn-default">Signup</button>
	</form>
    
