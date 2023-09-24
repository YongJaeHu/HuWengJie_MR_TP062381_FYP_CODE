<?php
include "../connect.php";

if(isset($_POST["submit"])){
   $id = uni_id();
   $name = $_POST["name"];
   $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
   $location = $_POST["location"];
   $location = filter_var($location, FILTER_SANITIZE_SPECIAL_CHARS);
   $email = $_POST["email"];
   $email= filter_var($email, FILTER_SANITIZE_SPECIAL_CHARS);
   $password = sha1($_POST["password"]);
   $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
   $cpass = sha1($_POST["cpass"]);
   $cpass = filter_var($cpass, FILTER_SANITIZE_SPECIAL_CHARS);
   $phone = $_POST["phone"];
   $phone = filter_var($phone, FILTER_SANITIZE_SPECIAL_CHARS);
   $address = $_POST["address"];
   $address = filter_var($address, FILTER_SANITIZE_SPECIAL_CHARS);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
   $image_ext = pathinfo($image, PATHINFO_EXTENSION);
   $re_image = uni_id().'.'.$image_ext;
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = "../images/" . $re_image;

   $admin = $conn->prepare("SELECT * FROM `admin` WHERE email = ?");
   $admin->execute([$email]);
   if($admin->rowCount() > 0){
      $error_msg[] = 'This email is already registered.';
   }else{
      if($password != $cpass){
         $error_msg[] = 'Passowrd not matched.';
      }else{
         $insert_admin = $conn->prepare("INSERT INTO `admin`(id, name, location, email, password, image, phone, address) VALUES(?,?,?,?,?,?,?,?)");
         $insert_admin->execute([ $id, $name, $location, $email, $password, $re_image , $phone, $address]);
         move_uploaded_file($image_tmp_name, $image_folder);
         $success_msg[] = 'registered! please wait for approval from admin';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Centre register</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body style="padding-left: 0;">
<section class="norm_container">
   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h1 class="heading">Registration Form For Pet Shelter</h1>
      <p>Center Name <span>*</span></p>
      <input type="text" name="name" placeholder="Enter center name" maxlength="255" required class="box">
      <p>Email <span>*</span></p>
            <input type="email" name="email" placeholder="Enter email" maxlength="255" required class="box">
      <div class="flex">
         <div class="col">
         <p>Phone <span>*</span></p>
            <input type="number" name="phone" placeholder="Enter Phone number" maxlength="255" required class="box">
         </div>
         <div class="col">
            <p>Location <span>*</span></p>
            <select name="location" class="box" required>
               <option value="" disabled selected>--center location</option>
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
   </div>
   <p>Address <span>*</span></p>
      <input type="text" name="address" placeholder="Enter Detailed Address" maxlength="255" required class="box">
   <p>Center Profile Image / Logo <span>*</span></p>
   <input type="file" name="image" accept="image/*" required class="box">
   <p>Password <span>*</span></p>
   <input type="password" name="password" placeholder="Enter password" maxlength="255" required class="box">
   <p>Confirm password <span>*</span></p>
   <input type="password" name="cpass" placeholder="Confirm your password" maxlength="255" required class="box">
   <input type="submit" name="submit" value="register" class="btn">
   <div class="flex">
         <a href="../admin/admin_login.php" class="exit-btn">Go Back</a>
      </div>
   <p class="link">Registered? <a href="admin_login.php">Go to Login</a></p>
</form>
</section>

<script src="../js/admin_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>  
