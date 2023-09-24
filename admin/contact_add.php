<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}      

// $receiver_id = $_SESSION['centre_id'];

// Initialize variables
$admin_id = isset($_POST['admin_id']) ? $_POST['admin_id'] : '';
$title = isset($_POST['title']) ? $_POST['title'] : '';
$content = isset($_POST['content']) ? $_POST['content'] : '';

if(isset($_POST['publish'])){
   $contact_id = uni_id();
   $receiver_id = $_GET['centre_id'];
   $sender_id = $_POST['sender_id'];
   $sender_id = filter_var($sender_id, FILTER_SANITIZE_SPECIAL_CHARS);
   // $receiver_id = $_POST['receiver_id'];
   // $receiver_id = filter_var($receiver_id, FILTER_SANITIZE_SPECIAL_CHARS);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_SPECIAL_CHARS);
   $status = 'unreply';
   
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

   $insert_post = $conn->prepare("INSERT INTO `contact`(id, sender_id, receiver_id, title, content, image, status) VALUES(?,?,?,?,?,?,?)");
   $insert_post->execute([$contact_id, $sender_id, $receiver_id, $title, $content, $re_image, $status]);
   move_uploaded_file($image_tmp_name, $image_folder);
   $success_msg[] = 'Sent!';
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
   <title>Contact</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
include "../admin/admin_header.php"; 
?>

<section class="ar_edit">

   <!-- <h1 class="heading">add new post</h1> -->

   <form action="" method="post" enctype="multipart/form-data">
   <h1 class="heading">Contact form</h1>
      <input type="hidden" name="sender_id" value="<?= $fetch_profile['id']; ?>">
      <?php
      $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
      $select_admin->execute([$_GET['centre_id']]);
      $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
         ?>
      <p><i>Receiver Name: </i><?= $fetch_admin['name']; ?></p>
      <p><i>Receiver Email: </i><?= $fetch_admin['email']; ?></p>
      <p>Title <span>*</span></p>
      <input type="text" name="title" maxlength="255" required placeholder="title" class="box" value="<?= $title; ?>">
      <p>Content <span>*</span></p>
      <textarea name="content" class="box" required maxlength="10000" placeholder="write content..." cols="30" rows="10"><?= $content; ?></textarea>
      <p>Image</p>
      <input type="file" name="image" class="box" accept="image/*">
      <div class="flex-btn">
         <input type="submit" value="Send" name="publish" class="btn">
      </div>
      <a href="../admin/contact_view.php" class="exit-btn">Back</a>
   </form>

</section>
<script src="../js/admin_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>