<?php
include '../connect.php';

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: user_login.php');
   exit();
}   

if(isset($_POST['submit'])){

   $select_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
   $select_user->execute([$id]);
   $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

   $prev_image = $fetch_user['image'];

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
   $phone = $_POST['phone'];
   $phone = filter_var($phone, FILTER_SANITIZE_SPECIAL_CHARS);

   if (!empty($name) && $name !== $fetch_user['name']) {
      $update_name = $conn->prepare("UPDATE `user` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $id]);
      $success_msg[] = 'New name Updated!';
   }

   if (!empty($phone) && $phone !== $fetch_user['phone']) {
      $update_phone = $conn->prepare("UPDATE `user` SET phone = ? WHERE id = ?");
      $update_phone->execute([$phone, $id]);
      $success_msg[] = 'Phone Number Updated!';
   }

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
   $image_ext = pathinfo($image, PATHINFO_EXTENSION);
   $re_image = uni_id().'.'.$image_ext;
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = "../images/" . $re_image;
   
   if (!empty($image)&& $image !== $fetch_user['image']) {
       $update_image = $conn->prepare("UPDATE `user` SET `image` = ? WHERE id = ?");
       $update_image->execute([$re_image, $fetch_user['id']]);
       move_uploaded_file($image_tmp_name, $image_folder);
       $success_msg[] = 'Image updated successfully!';
   }

}

if(isset($_POST['delete_image'])){
   $empty_image = '';
   $id = $_SESSION['id'];
   $delete_image = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
   $delete_image->execute([$id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image'] != ''){
      unlink('../images/'.$fetch_delete_image['image']);
   }
   $unset_image = $conn->prepare("UPDATE `user` SET image = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $id]);
   $success_msg[] = 'image deleted!';
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
<body>

<?php include '../user/user_header.php'; ?>

<section class="norm_container">
   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>update profile</h3>
            <p>Name </p>
            <input type="text" name="name"placeholder="<?= $fetch_profile['name']; ?>" maxlength="255"  class="box">
         </div>
         <div class="flex">
         <div class="col">
         <p>Phone number </p>
            <input type="text" name="phone"placeholder="<?= $fetch_profile['phone']; ?>" maxlength="255"  class="box">
            <?php if($fetch_profile['image'] != ''){ ?>
         <img src="../images/<?= $fetch_profile['image']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image" style="float: right;">
      <?php } ?>
      <p>Update profile image</p>
      <input type="file" name="image" accept="image/*"  class="box">
      <input type="submit" name="submit" value="update now" class="btn">
      <a href="../user/user_detail.php" class="exit-btn">Back</a>
   </form>

</section>
<?php include 'footer.php'; ?>
<script src="../js/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>