<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: user_login.php');
   exit();
}     


if(isset($_POST['publish'])){
   $report_id = uni_id();
   $reporter_id = $_POST['reporter_id'];
   $reporter_id = filter_var($reporter_id, FILTER_SANITIZE_SPECIAL_CHARS);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_SPECIAL_CHARS);
   $location = $_POST["location"];
   $location = filter_var($location, FILTER_SANITIZE_SPECIAL_CHARS);
   $address = $_POST["address"];
   $address = filter_var($address, FILTER_SANITIZE_SPECIAL_CHARS);
   $status = 'waiting for centre';
   
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
   $image_ext = pathinfo($image, PATHINFO_EXTENSION);
   $re_image = uni_id().'.'.$image_ext;
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = "../images/" . $re_image;

   if (!empty($image)) {
      move_uploaded_file($image_tmp_name, $image_folder);
  }else{
   $re_image = '';
}
   $insert_post = $conn->prepare("INSERT INTO `report`(id, reporter_id, title, content, location, address, image, status) VALUES(?,?,?,?,?,?,?,?)");
   $insert_post->execute([$report_id, $reporter_id, $title, $content, $location, $address, $re_image, $status]);
   move_uploaded_file($image_tmp_name, $image_folder);
   $success_msg[] = 'Report Sent!';
   // $_SESSION['success_msg'] = $success_msg; // Store success message in session
   // header('Location: article_view.php');
   // exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Report Add</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include "../user/user_header.php"; 
?>

<section class="ar_edit">

   <!-- <h1 class="heading">add new post</h1> -->

   <form action="" method="post" enctype="multipart/form-data">
   <h1 class="heading">add new report</h1>
      <input type="hidden" name="reporter_id" value="<?= $fetch_profile['id']; ?>">
      <p>Title <span>*</span></p>
      <input type="text" name="title" maxlength="255" required placeholder="title" class="box">
      <p>Content <span>*</span></p>
      <textarea name="content" class="box" required maxlength="10000" placeholder="write content..." cols="30" rows="10"></textarea>
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
      <p>Address <span>*</span></p>
      <input type="text" name="address" placeholder="Enter Detailed Address" maxlength="255" required class="box">
      <p>Evidence Image<span>*</span></p>
      <input type="file" name="image" required class="box" accept="image/*">
      <div class="flex-btn">
         <input type="submit" value="publish report" name="publish" class="btn">
      </div>
      <a href="../user/user_page.php" class="exit-btn">Back</a>
   </form>

</section>
<?php include 'footer.php'; ?>
<script src="../js/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>
