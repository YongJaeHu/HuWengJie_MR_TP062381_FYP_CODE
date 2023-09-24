<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: user_login.php');
   exit();
}      

$status = 'active';
$message = [];

// Initialize variables
$admin_id = isset($_POST['admin_id']) ? $_POST['admin_id'] : '';
$title = isset($_POST['title']) ? $_POST['title'] : '';
$content = isset($_POST['content']) ? $_POST['content'] : '';

if(isset($_POST['publish'])){
   $post_id = uni_id();
   $admin_id = $_POST['admin_id'];
   $admin_id = filter_var($admin_id, FILTER_SANITIZE_SPECIAL_CHARS);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_SPECIAL_CHARS);
   $status = 'published';
   
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

   $image2 = $_FILES['image2']['name'];
   $image2 = filter_var($image2, FILTER_SANITIZE_SPECIAL_CHARS);
   $image2_ext = pathinfo($image2, PATHINFO_EXTENSION);
   $re_image2 = uni_id().'.'.$image2_ext;
   $image2_tmp_name = $_FILES['image2']['tmp_name'];
   $image2_folder = "../images/" . $re_image2;

   if (!empty($image2)) {
      move_uploaded_file($image2_tmp_name, $image2_folder);
  }else{
   $re_image2 = '';
}
     

   $image3 = $_FILES['image3']['name'];
   $image3 = filter_var($image3, FILTER_SANITIZE_SPECIAL_CHARS);
   $image3_ext = pathinfo($image3, PATHINFO_EXTENSION);
   $re_image3 = uni_id().'.'.$image3_ext;
   $image3_tmp_name = $_FILES['image3']['tmp_name'];
   $image3_folder = "../images/" . $re_image3;

      
   if (!empty($image3)) {
      move_uploaded_file($image3_tmp_name, $image3_folder);
  }else{
   $re_image3 = '';
}

   $image4 = $_FILES['image4']['name'];
   $image4 = filter_var($image4, FILTER_SANITIZE_SPECIAL_CHARS);
   $image4_ext = pathinfo($image4, PATHINFO_EXTENSION);
   $re_image4 = uni_id().'.'.$image4_ext;
   $image4_tmp_name = $_FILES['image4']['tmp_name'];
   $image4_folder = "../images/" . $re_image4;

   if (!empty($image4)) {
      move_uploaded_file($image4_tmp_name, $image4_folder);
  }else{
   $re_image4 = '';
}

   $insert_post = $conn->prepare("INSERT INTO `post`(id, author_id, title, content, image, image2, image3, image4, status) VALUES(?,?,?,?,?,?,?,?,?)");
   $insert_post->execute([$post_id, $admin_id, $title, $content, $re_image, $re_image2, $re_image3, $re_image4, $status]);
   move_uploaded_file($image_tmp_name, $image_folder);
   $success_msg[] = 'Post Published!';
   // $_SESSION['success_msg'] = $success_msg; // Store success message in session
   // header('Location: post_view.php');
   // exit();
}

if(isset($_POST['draft'])){
   $post_id = uni_id();
   $admin_id = $_POST['admin_id'];
   $admin_id = filter_var($admin_id, FILTER_SANITIZE_SPECIAL_CHARS);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_SPECIAL_CHARS);
   $status = 'draft';
   
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

   $image2 = $_FILES['image2']['name'];
   $image2 = filter_var($image2, FILTER_SANITIZE_SPECIAL_CHARS);
   $image2_ext = pathinfo($image2, PATHINFO_EXTENSION);
   $re_image2 = uni_id().'.'.$image2_ext;
   $image2_tmp_name = $_FILES['image2']['tmp_name'];
   $image2_folder = "../images/" . $re_image2;

   if (!empty($image2)) {
      move_uploaded_file($image2_tmp_name, $image2_folder);
  }else{
   $re_image2 = '';
}
     

   $image3 = $_FILES['image3']['name'];
   $image3 = filter_var($image3, FILTER_SANITIZE_SPECIAL_CHARS);
   $image3_ext = pathinfo($image3, PATHINFO_EXTENSION);
   $re_image3 = uni_id().'.'.$image3_ext;
   $image3_tmp_name = $_FILES['image3']['tmp_name'];
   $image3_folder = "../images/" . $re_image3;

      
   if (!empty($image3)) {
      move_uploaded_file($image3_tmp_name, $image3_folder);
  }else{
   $re_image3 = '';
}

   $image4 = $_FILES['image4']['name'];
   $image4 = filter_var($image4, FILTER_SANITIZE_SPECIAL_CHARS);
   $image4_ext = pathinfo($image4, PATHINFO_EXTENSION);
   $re_image4 = uni_id().'.'.$image4_ext;
   $image4_tmp_name = $_FILES['image4']['tmp_name'];
   $image4_folder = "../images/" . $re_image4;

   if (!empty($image4)) {
      move_uploaded_file($image4_tmp_name, $image4_folder);
  }else{
   $re_image4 = '';
}

      $insert_post = $conn->prepare("INSERT INTO `post`(id, author_id, title, content, image, image2, image3, image4, status) VALUES(?,?,?,?,?,?,?,?,?)");
      $insert_post->execute([$post_id, $admin_id, $title, $content, $re_image, $re_image2, $re_image3, $re_image4, $status]);
      move_uploaded_file($image_tmp_name, $image_folder);
      $success_msg[] = 'Saved Draft!';
      // $_SESSION['success_msg'] = $success_msg; // Store success message in session
      // header('Location: post_view.php');
      // exit();
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Post Add</title>
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
   <h1 class="heading">add new post</h1>
      <input type="hidden" name="admin_id" value="<?= $fetch_profile['id']; ?>">
      <p>Title <span>*</span></p>
      <input type="text" name="title" maxlength="255" required placeholder="title" class="box" value="<?= $title; ?>">
      <p>Content <span>*</span></p>
      <textarea name="content" class="box" required maxlength="10000" placeholder="write content..." cols="30" rows="10"><?= $content; ?></textarea>
      <p>Thumbnail Image</p>
      <input type="file" name="image" class="box" accept="image/*">
      <p>Image2</p>
      <input type="file" name="image2" class="box" accept="image/*">
      <p>Image3</p>
      <input type="file" name="image3" class="box" accept="image/*">
      <p>Image4</p>
      <input type="file" name="image4" class="box" accept="image/*">
      <div class="flex-btn">
         <input type="submit" value="publish post" name="publish" class="btn">
         <input type="submit" value="save draft" name="draft" class="option-btn">
      </div>
      <a href="../user/post_view.php" class="exit-btn">Back</a>
   </form>

</section>
<?php include 'footer.php'; ?>
<script src="../js/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>
