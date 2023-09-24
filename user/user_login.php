<?php
include "../connect.php";
session_start();
if(isset($_SESSION['id'])){
  $id = $_SESSION['id'];
}else{
  $id = '';
};

if (isset($_POST['login'])) {
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_SPECIAL_CHARS);
   $password = sha1($_POST['password']);
   $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);

   $admin = $conn->prepare("SELECT * FROM `user` WHERE email = ? AND password = ?");
   $admin->execute([$email, $password]);
   $row = $admin->fetch(PDO::FETCH_ASSOC);

   if ($admin->rowCount() > 0) {
      $_SESSION['id'] = $row['id'];
      $_SESSION['email'] = $row['email'];
      header('location:user_page.php');
   } else {
      $error_msg[] = 'Invalid Login. Please Try again!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">

</head>
<?php
include "../user/user_header.php"; 
?>
<section class="norm_container">
   <form action="" method="post" enctype="multipart/form-data" class="login">
      <h3>Login Form for User</h3>
      <p>Email <span>*</span></p>
      <input type="email" name="email" placeholder="Enter email" maxlength="255" required class="box">
      <p>Password <span>*</span></p>
      <input type="password" name="password" placeholder="Enter password" maxlength="255" required class="box">
      <p class="link2">Forget Password? <a href="user_repass.php">Click here.</a></p>
      <input type="submit" name="login" value="login now" class="btn">
      <p class="link">Not Registered? <a href="user_register.php">Go to Register</a></p>
   </form>
</section>
</main>
<?php include 'footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/script.js"></script>
<?php include '../message.php'; ?>
</body>
</html>