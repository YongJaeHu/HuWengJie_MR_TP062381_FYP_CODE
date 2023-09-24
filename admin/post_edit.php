<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}   

if(isset($_POST['save'])){
   $post_id = $_GET['id'];
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_SPECIAL_CHARS);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_SPECIAL_CHARS);

   $update_post = $conn->prepare("UPDATE `post` SET title = ?, content = ?, status = ? WHERE id = ?");
   $update_post->execute([$title, $content, $status, $post_id]);
   $success_msg[] = 'Updated successfully!';
   if(!empty($post_id)){
      $update_title = $conn->prepare("UPDATE `post` SET title = ? WHERE id = ?");
      $update_title->execute([$title, $post_id]);
      $success_msg[] = 'updated successfully!';
   }
   if(!empty($content)){
      $update_content = $conn->prepare("UPDATE `post` SET content = ? WHERE id = ?");
      $update_content->execute([$content, $post_id]);
      $success_msg[] = 'content updated successfully!';
   }
   
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
   $image_ext = pathinfo($image, PATHINFO_EXTENSION);
   $re_image = uni_id().'.'.$image_ext;
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = "../images/" . $re_image;

   if(!empty($image)){
         $update_image = $conn->prepare("UPDATE `post` SET `image` = ? WHERE id = ?");
         $update_image->execute([$re_image, $post_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         $success_msg[] = 'image updated successfully!';
      }
   
      $image2 = $_FILES['image2']['name'];
      $image2 = filter_var($image2, FILTER_SANITIZE_SPECIAL_CHARS);
      $image2_ext = pathinfo($image2, PATHINFO_EXTENSION);
      $re_image2 = uni_id().'.'.$image2_ext;
      $image2_tmp_name = $_FILES['image2']['tmp_name'];
      $image2_folder = '../images/'.$re_image2;
      
   if(!empty($image2)){
         $update_image2 = $conn->prepare("UPDATE `post` SET `image2` = ? WHERE id = ?");
         $update_image2->execute([$re_image2, $post_id]);
         move_uploaded_file($image2_tmp_name, $image2_folder);
         $success_msg[] = 'updated successfully!';
      }
      
      $image3 = $_FILES['image3']['name'];
      $image3 = filter_var($image3, FILTER_SANITIZE_SPECIAL_CHARS);
      $image3_ext = pathinfo($image3, PATHINFO_EXTENSION);
      $re_image3 = uni_id().'.'.$image3_ext;
      $image3_tmp_name = $_FILES['image3']['tmp_name'];
      $image3_folder = '../images/'.$re_image3;
      
   if(!empty($image3)){
         $update_image3 = $conn->prepare("UPDATE `post` SET `image3` = ? WHERE id = ?");
         $update_image3->execute([$re_image3, $post_id]);
         move_uploaded_file($image3_tmp_name, $image3_folder);
         $success_msg[] = 'updated successfully!';
      }
      
      $image4 = $_FILES['image4']['name'];
      $image4 = filter_var($image4, FILTER_SANITIZE_SPECIAL_CHARS);
      $image4_ext = pathinfo($image4, PATHINFO_EXTENSION);
      $re_image4 = uni_id().'.'.$image4_ext;
      $image4_tmp_name = $_FILES['image4']['tmp_name'];
      $image4_folder = '../images/'.$re_image4;
      
   if(!empty($image4)){
         $update_image4 = $conn->prepare("UPDATE `post` SET `image4` = ? WHERE id = ?");
         $update_image4->execute([$re_image4, $post_id]);
         move_uploaded_file($image4_tmp_name, $image4_folder);
         $success_msg[] = 'updated successfully!';
      }
      $_SESSION['success_msg'] = $success_msg; // Store success message in session
      header('Location: post_view.php');
      exit();
}

if(isset($_POST['delete_post'])){
   $post_id = $_POST['post_id'];
   $post_id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);
   $delete_image = $conn->prepare("SELECT * FROM `post` WHERE id = ?");
   $delete_image->execute([$post_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image'] != ''){
      unlink('../images/'.$fetch_delete_image['image']);
   }
   $delete_image2 = $conn->prepare("SELECT * FROM `post` WHERE id = ?");
   $delete_image2->execute([$post_id]);
   $fetch_delete_image2 = $delete_image2->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image2'] != ''){
      unlink('../images/'.$fetch_delete_image2['image2']);
   }
   $delete_image3 = $conn->prepare("SELECT * FROM `post` WHERE id = ?");
   $delete_image3->execute([$post_id]);
   $fetch_delete_image3 = $delete_image3->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image3'] != ''){
      unlink('../images/'.$fetch_delete_image['image3']);
   }
   $delete_image4 = $conn->prepare("SELECT * FROM `post` WHERE id = ?");
   $delete_image4->execute([$post_id]);
   $fetch_delete_image4 = $delete_image4->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image4'] != ''){
      unlink('../images/'.$fetch_delete_image['image4']);
   }
   $delete_post = $conn->prepare("DELETE FROM `post` WHERE id = ?");
   $delete_post->execute([$post_id]);
   $delete_comment = $conn->prepare("DELETE FROM `comment` WHERE id = ?");
   $delete_comment->execute([$post_id]);
   $delete_like = $conn->prepare("DELETE FROM `like` WHERE id = ?");
   $delete_like->execute([$post_id]);
   $affected_rows = $delete_post->rowCount();
   if($affected_rows > 0){
      $success_msg[] = 'Post deleted successfully!';
      $_SESSION['success_msg'] = $success_msg;
   }else{
      $error_message[] = 'Failed to delete the post.';
      $_SESSION['error_msg'] = $error_msg;
   }
}

if(isset($_POST['delete_image'])){
   $empty_image = '';
   $post_id = $_GET['id'];
   $delete_image = $conn->prepare("SELECT * FROM `post` WHERE id = ?");
   $delete_image->execute([$post_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image'] != ''){
      unlink('../images/'.$fetch_delete_image['image']);
   }
   $unset_image = $conn->prepare("UPDATE `post` SET image = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $post_id]);
   $success_msg[] = 'image deleted!';
}

if(isset($_POST['delete_image2'])){
   $empty_image = '';
   $post_id = $_GET['id'];
   $delete_image = $conn->prepare("SELECT * FROM `post` WHERE id = ?");
   $delete_image->execute([$post_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image2'] != ''){
      unlink('../images/'.$fetch_delete_image['image2']);
   }
   $unset_image = $conn->prepare("UPDATE `post` SET image2 = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $post_id]);
   $success_msg[] = 'image 2 deleted!';
}

if(isset($_POST['delete_image3'])){
   $empty_image = '';
   $post_id = $_GET['id'];
   $delete_image = $conn->prepare("SELECT * FROM `post` WHERE id = ?");
   $delete_image->execute([$post_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image3'] != ''){
      unlink('../images/'.$fetch_delete_image['image3']);
   }
   $unset_image = $conn->prepare("UPDATE `post` SET image3 = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $post_id]);
   $success_msg[] = 'image 3 deleted!';
}

if(isset($_POST['delete_image4'])){
   $empty_image = '';
   $post_id = $_GET['id'];
   $delete_image = $conn->prepare("SELECT * FROM `post` WHERE id = ?");
   $delete_image->execute([$post_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image4'] != ''){
      unlink('../images/'.$fetch_delete_image['image4']);
   }
   $unset_image = $conn->prepare("UPDATE `post` SET image4 = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $post_id]);
   $success_msg[] = 'image 4 deleted!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>post Edit</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
include "../admin/admin_header.php"; 
?>

<section class="ar_edit">

   <h1 class="heading">edit post</h1>

   <?php
      $post_id = $_GET['id'];
      $select_post = $conn->prepare("SELECT * FROM `post` WHERE id = ?");
      $select_post->execute([$post_id]);
      if($select_post->rowCount() > 0){
         while($fetch_post = $select_post->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="old_image" value="<?= $fetch_post['image']; ?>">
      <input type="hidden" name="id" value="<?= $fetch_post['id']; ?>">
      <p>post status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fetch_post['status']; ?>" selected><?= $fetch_post['status']; ?></option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>Title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="add post title" class="box" value="<?= $fetch_post['title']; ?>">
      <p>Content <span>*</span></p>
      <textarea name="content" class="box" required maxlength="10000" placeholder="write your content..." cols="30" rows="10"><?= $fetch_post['content']; ?></textarea>
      <?php if($fetch_post['image'] != ''){ ?>
         <img src="../images/<?= $fetch_post['image']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image" style="float: right;">
      <?php } ?>
      <p>Thumbnail image</p>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <?php if($fetch_post['image2'] != ''){ ?>
         <img src="../images/<?= $fetch_post['image2']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image2" style="float: right;">
      <?php } ?>
      <p>Image 2</p>
      <input type="file" name="image2" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <?php if($fetch_post['image3'] != ''){ ?>
         <img src="../images/<?= $fetch_post['image3']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image3" style="float: right;">
      <?php } ?>
      <p>Image 3</p>
      <input type="file" name="image3" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <?php if(!empty($fetch_post['image4'])){ ?>
         <img src="../images/<?= $fetch_post['image4']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image4" style="float: right;">
      <?php } ?>
      <p>Image 4</p>
      <input type="file" name="image4" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <div class="flex-btn">
         <input type="submit" value="save post" name="save" class="btn">
         <a href="post_view.php?id=<?= $post_id; ?>&delete=1" class="delete-btn" onclick="return delete_msg(event);">delete</a>
      </div>
      <a href="post_view.php" class="exit-btn" onclick="return confirm_msg(event);">go back</a>
   </form>

   <?php
         }
      }else{
         echo '<p class="empty">no post found!</p>';
   ?>
   <div class="flex-btn">
      <a href="post_view.php" class="option-btn">view post</a>
      <a href="post_add.php" class="option-btn">add post</a>
   </div>
   <?php
      }
   ?>

</section>
<script src="../js/admin_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
<script>
function delete_msg(event) {
    event.preventDefault();
    var urlToRedirect = event.currentTarget.getAttribute('href');  
    console.log(urlToRedirect); 
    swal({
        title: "Are you sure to Delete that?",
        text: "Be careful in deletion.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willCancel) => {
            if (willCancel) { 
                window.location.href = urlToRedirect;   
            }  
        });
    };

    function confirm_msg(event) {
    event.preventDefault();
    var urlToRedirect = event.currentTarget.getAttribute('href');  
    console.log(urlToRedirect); 
    swal({
      title: "Are you sure to Go Back?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willCancel) => {
            if (willCancel) { 
                window.location.href = urlToRedirect;   
            }  
        });
    }
</script>
</body>
</html>