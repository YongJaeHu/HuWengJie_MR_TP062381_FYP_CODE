<?php
include "../connect.php";

session_start();
$id = '';

if(isset($_POST["submit"])){
   $id = uni_id();
   $name = $_POST["name"];
   $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
   $email = $_POST["email"];
   $email= filter_var($email, FILTER_SANITIZE_SPECIAL_CHARS);
   $phone = $_POST["phone"];
   $phone = filter_var($phone, FILTER_SANITIZE_SPECIAL_CHARS);
   $password = sha1($_POST["password"]);
   $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
   $cpass = sha1($_POST["cpass"]);
   $cpass = filter_var($cpass, FILTER_SANITIZE_SPECIAL_CHARS);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
   $image_ext = pathinfo($image, PATHINFO_EXTENSION);
   $re_image = uni_id().'.'.$image_ext;
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = "../images/" . $re_image;

   $admin = $conn->prepare("SELECT * FROM `user` WHERE email = ?");
   $admin->execute([$email]);
   if($admin->rowCount() > 0){
      $error_msg[] = 'This email is already registered.';
   }else{
      if($password != $cpass){
         $error_msg[] = 'Passowrd not matched.';
      }else{
         $insert_admin = $conn->prepare("INSERT INTO `user`( id, name, email, phone, password, image) VALUES(?,?,?,?,?,?)");
         $insert_admin->execute([ $id, $name, $email, $phone, $password, $re_image]);
         move_uploaded_file($image_tmp_name, $image_folder);
         $success_msg[] = 'new user registered!';
      }
   }      
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">

</head>
<?php
include "../user/user_header.php"; 
?>
<body>
<section class="norm_container">
   <form class="register" action="" method="post" enctype="multipart/form-data">
   <h1 class="heading">Registration Form For User</h1>
      <p>Name <span>*</span></p>
      <input type="hidden" name="role" value="1">
      <input type="text" name="name" placeholder="Enter name" maxlength="255" required class="box">
      <p>Email <span>*</span></p>
            <input type="email" name="email" placeholder="Enter email" maxlength="255" required class="box">
            <p>Phone <span>*</span></p>
            <input type="number" name="phone" placeholder="Enter Phone number" maxlength="255" required class="box">
            <p>Password <span>*</span></p>
            <input type="password" name="password" placeholder="Enter password" maxlength="255" required class="box">
            <p>Confirm password <span>*</span></p>
            <input type="password" name="cpass" placeholder="Confirm your password" maxlength="255" required class="box">
   <p>Profile Image / Logo<span>*</span></p>
   <input type="file" name="image" accept="image/*" required class="box">
   <input type="submit" name="submit" value="register" class="btn">
   <p class="link">Registered? <a href="user_login.php">Go to Login</a></p>

</form>
</section>
<?php include 'footer.php'; ?>
<script src="../js/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>  
