<?php
include '../connect.php';

session_start();
$email = $_SESSION['email'];
// $id = $_SESSION['id'];

if(isset($_POST['submit'])){
$new_pass = $_POST['new_pass'];
$cpass = $_POST['cpass'];

if (!empty($new_pass) && !empty($cpass)) {
    if ($new_pass === $cpass) {
        $hashed_password = sha1($new_pass);
        $update_pass = $conn->prepare("UPDATE `user` SET password = ? WHERE email = ?");
            $update_pass->execute([$hashed_password, $email]);
            $success_msg[] = 'Reset succesfully!';
    } else {
        $error_msg[] = "Passwords do not match.";
    }
} else {
    $error_msg[] = "Please enter a new password and confirm password.";
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
<body style="padding-left: 0;">
<section class="norm_container">
   <form action="" method="post" enctype="multipart/form-data" class="login">
   <p>new password :</p>
   <input type="password" name="new_pass" placeholder="Enter your new password" maxlength="20" required class="box">
            <p>confirm password :</p>
            <input type="password" name="cpass" placeholder="Confirm your new password" maxlength="20" required class="box">
      <input type="submit" name="submit" value="reset now" class="btn">
      <p class="link">Reset Already? <a href="user_login.php">Go to Login</a></p>
   </form>
</section>
   <!-- <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>update profile</h3>
      <div class="flex">
         <div class="col">
            <p>new password :</p>
            <input type="password" name="new_pass" placeholder="enter your new password" maxlength="20"  class="box">
            <p>confirm password :</p>
            <input type="password" name="cpass" placeholder="confirm your new password" maxlength="20"  class="box">
         </div>
      </div>
      <input type="submit" name="submit" value="update now" class="btn">
   </form> -->

</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/script.js"></script>
<?php include '../message.php'; ?>
</body>
</html>