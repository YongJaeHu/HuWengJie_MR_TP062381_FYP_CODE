<?php
include '../connect.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST['check_approval'])) {
   $Email = $_POST['check_email'];

   $admin = $conn->prepare("SELECT * FROM `admin` WHERE email = ?");
   $admin->execute([$Email]);
   $row = $admin->fetch(PDO::FETCH_ASSOC);

   if ($admin->rowCount() > 0) {
      header('Location: approval_check_view.php?email=' . urlencode($Email));
      exit();
   } else {
      $error_msg[] = "Invalid Email";
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
<section class="norm_container">
   <form action="" method="post" enctype="multipart/form-data" class="login">
      <h3>Check Approval</h3>
      <p>Your email <span>*</span></p>
      <input type="email" name="check_email" placeholder="enter your email" maxlength="100" required class="box">
      <input type="submit" name="check_approval" value="Check Approval" class="btn">
      <a href="../admin/admin_login.php" class="exit-btn">Go Back</a>
   </form>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../message.php'; ?>
</body>
</html>