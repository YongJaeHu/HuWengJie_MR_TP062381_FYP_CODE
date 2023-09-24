<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: user_login.php');
   exit();
}      

$prev_contact_id = $_GET['prev_report_id'];
$receiver_id = $_GET['receiver_id'];

// $receiver_id = $_SESSION['centre_id'];

// Initialize variables
$admin_id = isset($_POST['admin_id']) ? $_POST['admin_id'] : '';
$title = isset($_POST['title']) ? $_POST['title'] : '';
$content = isset($_POST['content']) ? $_POST['content'] : '';

if(isset($_POST['publish'])){
   $contact_id = uni_id();
   $prev_contact_id = $_GET['prev_report_id'];
   $receiver_id = $_GET['receiver_id'];
   $sender_id = $_POST['sender_id'];
   $sender_id = filter_var($sender_id, FILTER_SANITIZE_SPECIAL_CHARS);
   // $receiver_id = $_POST['receiver_id'];
   // $receiver_id = filter_var($receiver_id, FILTER_SANITIZE_SPECIAL_CHARS);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_SPECIAL_CHARS);
   $status = 'unreply';

   $up_status = 'replied';
   
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

if(!empty($prev_contact_id)){
   $update_contact= $conn->prepare("UPDATE `contact` SET status = ? WHERE id = ?");
   $update_contact->execute([$up_status, $prev_contact_id]);
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
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include "../user/user_header.php"; 
?>

<section class="ar_edit">

   <!-- <h1 class="heading">add new post</h1> -->

   <form action="" method="post" enctype="multipart/form-data">
   <?php
               $select_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
               $select_user->execute([$_GET['receiver_id']]);
               $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

               $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
               $select_admin->execute([$_GET['receiver_id']]);
               $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

               $content = $conn->prepare("SELECT * FROM `contact` WHERE id = ?");
               $content->execute([$_GET['prev_report_id']]);
               $fetch_content = $content->fetch(PDO::FETCH_ASSOC);
      ?>
   <h1 class="heading">Contact form</h1>
      <input type="hidden" name="sender_id" value="<?= $fetch_profile['id']; ?>">
      <?php if ($fetch_user && $fetch_user !== false) { ?>
      <p><i>Receiver Name: </i><?= $fetch_user['name']; ?></p>
      <p><i>Receiver Email: </i><?= $fetch_user['email']; ?></p>
      <?php } elseif ($fetch_admin && $fetch_admin !== false) { ?>
         <p><i>Receiver Name: </i><?= $fetch_admin['name']; ?></p>
      <p><i>Receiver Email: </i><?= $fetch_admin['email']; ?></p>
      <?php } ?>
      <p><i>Title: </i><?= $fetch_content['title']; ?></p>
      <p><i>Content: </i><?= $fetch_content['content']; ?></p>
      <p>Your Title <span>*</span></p>
      <input type="text" name="title" maxlength="255" required placeholder="title" class="box" value="<?= $title; ?>">
      <p>Reply <span>*</span></p>
      <textarea name="content" class="box" required maxlength="10000" placeholder="write content..." cols="30" rows="10"></textarea>
      <p>Image</p>
      <input type="file" name="image" class="box" accept="image/*">
      <div class="flex-btn">
         <input type="submit" value="send" name="publish" class="btn">
      </div>
      <a href="../user/contact_view.php" class="exit-btn">Back</a>
   </form>

</section>
<?php include 'footer.php'; ?>
<script src="../js/admin_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>