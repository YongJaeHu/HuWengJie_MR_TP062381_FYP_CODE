<?php
include '../connect.php';

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}   

if(isset($_POST['submit'])){

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
   $select_admin->execute([$id]);
   $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

   $prev_pass = $fetch_admin['password'];
   $prev_image = $fetch_admin['image'];

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
   $location = $_POST['location'];
   $location = filter_var($location, FILTER_SANITIZE_SPECIAL_CHARS);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_SPECIAL_CHARS);
   $bank_acc = $_POST['bank_acc'];
   $bank_acc = filter_var($bank_acc, FILTER_SANITIZE_SPECIAL_CHARS);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
   $phone = $_POST['phone'];
   $phone = filter_var($phone, FILTER_SANITIZE_SPECIAL_CHARS);

   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $id]);
      $success_msg[] = 'New Centre name Updated!';
   }

   if(!empty($location)){
      $update_location = $conn->prepare("UPDATE `admin` SET location = ? WHERE id = ?");
      $update_location->execute([$location, $id]);
      $success_msg[] = 'Updated!';
   }

   if(!empty($address)){
      $update_address = $conn->prepare("UPDATE `admin` SET address = ? WHERE id = ?");
      $update_address->execute([$address, $id]);
      $success_msg[] = 'Updated!';
   }

   if(!empty($bank_acc)){
      $update_bank = $conn->prepare("UPDATE `admin` SET bank_acc = ? WHERE id = ?");
      $update_bank->execute([$bank_acc, $id]);
      $success_msg[] = 'Updated!';
   }

   if(!empty($description)){
      $update_description = $conn->prepare("UPDATE `admin` SET description = ? WHERE id = ?");
      $update_description->execute([$description, $id]);
      $success_msg[] = 'Updated!';
   }

   if(!empty($phone)){
      $update_phone = $conn->prepare("UPDATE `admin` SET phone = ? WHERE id = ?");
      $update_phone->execute([$phone, $id]);
      $success_msg[] = 'Updated!';
   }

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
   $image_ext = pathinfo($image, PATHINFO_EXTENSION);
   $re_image = uni_id().'.'.$image_ext;
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = "../images/" . $re_image;
   
   if (!empty($image)) {
       $update_image = $conn->prepare("UPDATE `admin` SET `image` = ? WHERE id = ?");
       $update_image->execute([$re_image, $fetch_admin['id']]);
       move_uploaded_file($image_tmp_name, $image_folder);
       $success_msg[] = 'Image updated successfully!';
   }
   
   $image2 = $_FILES['image2']['name'];
   $image2 = filter_var($image2, FILTER_SANITIZE_SPECIAL_CHARS);
   $image2_ext = pathinfo($image2, PATHINFO_EXTENSION);
   $re_image2 = uni_id().'.'.$image2_ext;
   $image2_tmp_name = $_FILES['image2']['tmp_name'];
   $image2_folder = '../images/'.$re_image2;
   
if(!empty($image2)){
      $update_image2 = $conn->prepare("UPDATE `admin` SET `image2` = ? WHERE id = ?");
      $update_image2->execute([$re_image2,  $fetch_admin['id']]);
      move_uploaded_file($image2_tmp_name, $image2_folder);
      $success_msg[] = 'Updated successfully!';
   }
      
   $image3 = $_FILES['image3']['name'];
   $image3 = filter_var($image3, FILTER_SANITIZE_SPECIAL_CHARS);
   $image3_ext = pathinfo($image3, PATHINFO_EXTENSION);
   $re_image3 = uni_id().'.'.$image3_ext;
   $image3_tmp_name = $_FILES['image3']['tmp_name'];
   $image3_folder = "../images/" . $re_image3;
   
   if (!empty($image3)) {
       $update_image3 = $conn->prepare("UPDATE `admin` SET `image3` = ? WHERE id = ?");
       $update_image3->execute([$re_image3, $fetch_admin['id']]);
       move_uploaded_file($image3_tmp_name, $image3_folder);
       $success_msg[] = 'Updated successfully!';
   }
      
   $image4 = $_FILES['image4']['name'];
   $image4 = filter_var($image4, FILTER_SANITIZE_SPECIAL_CHARS);
   $image4_ext = pathinfo($image4, PATHINFO_EXTENSION);
   $re_image4 = uni_id().'.'.$image4_ext;
   $image4_tmp_name = $_FILES['image4']['tmp_name'];
   $image4_folder = "../images/" . $re_image4;
   
   if (!empty($image4)) {
       $update_image4 = $conn->prepare("UPDATE `admin` SET `image4` = ? WHERE id = ?");
       $update_image4->execute([$re_image4, $fetch_admin['id']]);
       move_uploaded_file($image4_tmp_name, $image4_folder);
       $success_msg[] = 'Updated successfully!';
   }

}

if(isset($_POST['delete_image'])){
   $empty_image = '';
   $id = $_SESSION['id'];
   $delete_image = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
   $delete_image->execute([$id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image'] != ''){
      unlink('../images/'.$fetch_delete_image['image']);
   }
   $unset_image = $conn->prepare("UPDATE `admin` SET image = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $id]);
   $success_msg[] = 'image deleted!';
}

if(isset($_POST['delete_image2'])){
   $empty_image = '';
   $id = $_SESSION['id'];
   $delete_image = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
   $delete_image->execute([$id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image2'] != ''){
      unlink('../images/'.$fetch_delete_image['image2']);
   }
   $unset_image = $conn->prepare("UPDATE `admin` SET image2 = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $id]);
   $success_msg[] = 'image 2 deleted!';
}

if(isset($_POST['delete_image3'])){
   $empty_image = '';
   $id = $_SESSION['id'];
   $delete_image = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
   $delete_image->execute([$id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image3'] != ''){
      unlink('../images/'.$fetch_delete_image['image3']);
   }
   $unset_image = $conn->prepare("UPDATE `admin` SET image3 = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $id]);
   $success_msg[] = 'image 3 deleted!';
}

if(isset($_POST['delete_image4'])){
   $empty_image = '';
   $id = $_SESSION['id'];
   $delete_image = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
   $delete_image->execute([$id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image4'] != ''){
      unlink('../images/'.$fetch_delete_image['image4']);
   }
   $unset_image = $conn->prepare("UPDATE `admin` SET image4 = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $id]);
   $success_msg[] = 'image 4 deleted!';
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
      <h3>update profile</h3>
            <p>Centre name </p>
            <input type="text" name="name"placeholder="<?= $fetch_profile['name']; ?>" maxlength="255"  class="box">
         </div>
         <div class="flex">
         <div class="col">
         <p>Phone number </p>
            <input type="text" name="phone"placeholder="<?= $fetch_profile['phone']; ?>" maxlength="255"  class="box">
            <p>Address </p>
            <input type="text" name="address"placeholder="<?= $fetch_profile['address']; ?>" maxlength="255"  class="box">
            <p>Location </p>
            <select name="location" class="box">
               <option value="" selected><?= $fetch_profile['location']; ?></option>
               <option value="Johor">Johor</option>
               <option value="Kedah">Kedah</option>
               <option value="Kelantan">Kelantan</option>
               <option value="Melaka">Melaka</option>
               <option value="Negeri Sembilan">Negeri Sembilan</option>
               <option value="Pahang">Pahang</option>
               <option value="Perak">Perak</option>
               <option value="Perlis">Perlis</option>
               <option value="Pulau Pinang (Penang)">Pulau Pinang (Penang)</option>
               <option value="Sabah">Sabah</option>
               <option value="Sarawak">Sarawak</option>
               <option value="Selangor">Selangor</option>
               <option value="Terengganu">Terengganu</option>
               <option value="Kuala Lumpur">Kuala Lumpur</option>
               <option value="Labuan">Labuan</option>
               <option value="Putrajaya">Putrajaya</option>
            </select>
            </div>
      </div>
      <p>Bank account </p>
            <input type="text" name="bank_acc"placeholder="<?= $fetch_profile['bank_acc']; ?>" maxlength="255"  class="box">
      <p>Description <span>*</span></p>
      <textarea name="description" class="box" required maxlength="10000" placeholder="write description..." cols="30" rows="10"><?= $fetch_profile['description']; ?></textarea>
      <?php if($fetch_profile['image'] != ''){ ?>
         <img src="../images/<?= $fetch_profile['image']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image" style="float: right;">
      <?php } ?>
      <p>Update profile image</p>
      <input type="file" name="image" accept="image/*"  class="box">
      <?php if($fetch_profile['image2'] != ''){ ?>
         <img src="../images/<?= $fetch_profile['image2']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image2" style="float: right;">
      <?php } ?>
      <p>Update image 2</p>
      <input type="file" name="image2" accept="image/*"  class="box">
      <?php if($fetch_profile['image3'] != ''){ ?>
         <img src="../images/<?= $fetch_profile['image3']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image3" style="float: right;">
      <?php } ?>
      <p>Update image 3</p>
      <input type="file" name="image3" accept="image/*"  class="box">
      <?php if($fetch_profile['image4'] != ''){ ?>
         <img src="../images/<?= $fetch_profile['image4']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image4" style="float: right;">
      <?php } ?>
      <p>Update image 4</p>
      <input type="file" name="image4" accept="image/*"  class="box">
      <input type="submit" name="submit" value="update now" class="btn">
      <a href="../admin/admin_detail.php" class="exit-btn">Back</a>
   </form>

</section>
<script src="../js/admin_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>