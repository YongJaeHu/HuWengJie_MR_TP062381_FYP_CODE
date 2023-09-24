<?php
include "../connect.php";
session_start(); 

if (isset($_POST['login'])) {
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_SPECIAL_CHARS);
   $password = sha1($_POST['password']);
   $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);

   $admin = $conn->prepare("SELECT * FROM `admin` WHERE email = ? AND password = ?");
   $admin->execute([$email, $password]);
   $row = $admin->fetch(PDO::FETCH_ASSOC);

   if ($admin->rowCount() > 0 AND $row['approval'] == '1') {
      $_SESSION['id'] = $row['id'];
      $_SESSION['email'] = $row['email'];
      header('location:admin_page.php');
   } else {
      $error_msg[] = 'Invalid Login.!';
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
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body style="padding-left: 0;">
<main>
<section class="inner_box">
<section class="form_container">
   <form action="" method="post" enctype="multipart/form-data" class="login">
      <h3>Login Form for Pet Shelter & Admin</h3>
      <p>Email <span>*</span></p>
      <input type="email" name="email" placeholder="Enter email" maxlength="255" required class="box">
      <p>Password <span>*</span></p>
      <input type="password" name="password" placeholder="Enter password" maxlength="255" required class="box">
      <p class="link2">Forget Password? <a href="admin_repass.php">Click here.</a></p>
      <input type="submit" name="login" value="login now" class="btn">
      <p class="link">Not Registered? <a href="centre_register.php">Go to Register</a></p>
   </form>
</section>
<section class="explain_container">
   <form action="" method="post" enctype="multipart/form-data" class="login">
      <h2>Reminder</h2>
      <h3>-Please remind that this login form is only for Pet Shelter and Admin.</h3>
      <h3>-Normal user<span class="imp"> PLEASE </span>leave.</h3>
      <h3>-For New Pet Shelter, after register, you <span class="imp"> ONLY </span> can login <span class="imp"> AFTER </span> getting approval from admin.</h3>
      <a href="approval_check.php" class="option-btn">Check Your Approval</a>
      <a href="../user/user_page.php" class="exit-btn">Go back</a>
   </form>
</section>
</section>
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../message.php'; ?>
</body>
</html>