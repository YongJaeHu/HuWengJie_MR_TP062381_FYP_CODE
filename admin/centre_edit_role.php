<?php
include '../connect.php';

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}   

if(isset($_POST['submit'])){
   $admin_id = $_GET['admin_id'];
   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
   $select_admin->execute([$admin_id]);
   $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
   $role = $_POST['role'];
   $role = filter_var($role, FILTER_SANITIZE_SPECIAL_CHARS);

      $update_role = $conn->prepare("UPDATE `admin` SET role = ? WHERE id = ?");
      $update_role->execute([$role, $admin_id]);
      $success_msg[] = 'Role Updated Successfullt!';
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
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../admin/admin_header.php'; ?>

<section class="norm_container">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>update Role</h3>
      <?php
         $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
         $select_admin->execute([$_GET['admin_id']]);
         $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
         ?>
      <?php if($fetch_admin['image'] != ''){ ?>
         <img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt="">
      <?php } ?>
      <p>Name: <?= $fetch_admin['name']; ?></p>
      <p>Email: <?= $fetch_admin['email']; ?></p>
      <p>Role: </p>
      <select name="role" class="box">
               <option value="" selected><?= $fetch_admin['role']; ?></option>
               <option value="0">Centre</option>
               <option value="1">Admin</option>
               </select>
      <input type="submit" name="submit" value="update now" class="btn">
      <a href="../admin/centre_view.php" class="exit-btn">Back</a>
   </form>

</section>
<script src="../js/admin_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>