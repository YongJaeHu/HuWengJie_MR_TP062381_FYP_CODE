<?php
include '../connect.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if(isset($_SESSION['id'])){
   $id = $_SESSION['id'];
 }else{
   $id = '';
 };

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST['reset_password'])) {
   $resetEmail = $_POST['reset_email'];

   $user = $conn->prepare("SELECT * FROM `user` WHERE email = ?");
   $user->execute([$resetEmail]);
   $row = $user->fetch(PDO::FETCH_ASSOC);

   if ($user->rowCount() > 0) {
      $email = $_POST['reset_email'];

      // Generate a random password reset token
      $token = bin2hex(random_bytes(16));

      // Store the token and email in your database or session for later use

      // Send the email with the password reset link
      require_once "../PHPMailer/PHPMailer.php";
      require_once "../PHPMailer/SMTP.php";
      require_once "../PHPMailer/Exception.php";

      $mail = new PHPMailer();

      // SMTP settings
      $mail->isSMTP();
      $mail->Host = "smtp.gmail.com";
      $mail->SMTPAuth = true;
      $mail->Username = "petadoption.rescuecenter@gmail.com";
      $mail->Password = 'ougwokywkudvvahe';
      $mail->Port = 465;
      $mail->SMTPSecure = "ssl";

      // Email settings
      $mail->isHTML(true);
      $mail->setFrom("wengjie2000@gmail.com");
      $mail->addAddress($email);
      $mail->Subject = "Password Reset Request";
      $mail->Body = "Dear user,<br><br>Please click the following link to reset your password:<br>http://localhost/HuWengJie_MR_TP062381_FYP_CODE/user/user_newpass.php?email=" . urlencode($email) . "&token=" . urlencode($token) . "<br><br>If you didn't request a password reset, please ignore this email.<br><br>Best regards,<br>The Support Team";

      if($mail->send()){
         $_SESSION['email'] = $row['email'];
         $success_msg[] = 'Email sent.';
      }
      else
      {
         $error_msg[] = 'Something went wrong. Please try again later.';
      }
   } else {
      $error_msg[] = 'Incorrect email!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reset Password</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">
</head>
   <?php
   include "../user/user_header.php";
   ?>
<body style="padding-left: 0;">
<section class="norm_container">
   <form action="" method="post" enctype="multipart/form-data" class="login">
      <h3>Reset Your Password</h3>
      <p>Your email <span>*</span></p>
      <input type="email" name="reset_email" placeholder="enter your email" maxlength="100" required class="box">
      <input type="submit" name="reset_password" value="Reset Password" class="btn">
      <a href="../user/user_login.php" class="exit-btn">Go Back</a>
   </form>
</section>
<?php include 'footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/script.js"></script>
<?php include '../message.php'; ?>
</body>
</html>