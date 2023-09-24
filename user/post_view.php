<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: user_login.php');
   exit();
}    

if(isset($_SESSION['success_msg'])){
   $success_msg = $_SESSION['success_msg'];
   unset($_SESSION['success_msg']); // Unset the success message from session

}

if (isset($_POST['like_post'])) {
   $post_id = $_POST['post_id'];
   $post_id = filter_var($post_id, FILTER_SANITIZE_SPECIAL_CHARS);
   
   if ($post_id != '') {
      $post_id= $_POST['post_id'];
      $post_id= filter_var($post_id, FILTER_SANITIZE_SPECIAL_CHARS);
      // $id = $_POST['id'];
      // $id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);

      $select_like = $conn->prepare("SELECT * FROM `like` WHERE post_id = ? AND author_id = ?");
      $select_like->execute([$post_id, $id]);

      if ($select_like->rowCount() > 0) {
         $remove_like = $conn->prepare("DELETE FROM `like` WHERE post_id = ? AND author_id = ?");
         $remove_like->execute([$post_id, $id]);
         $warning_msg[] = 'removed from like';
      } else {
         $add_like = $conn->prepare("INSERT INTO `like`( post_id, author_id) VALUES(?,?)");
         $add_like->execute([ $post_id, $id]);
         $success_msg[] = 'added to like';
      }
   } else {
      $error_msg[] = 'Something Error!';
   }
}


if (isset($_GET['delete'])) {
   $id = $_GET['id'];
   $id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);
   $delete_image = $conn->prepare("SELECT * FROM `post` WHERE id = ?");
   $delete_image->execute([$id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if ($fetch_delete_image['image'] != '') {
      unlink('../images/' . $fetch_delete_image['image']);
   }
   if ($fetch_delete_image['image2'] != '') {
      unlink('../images/' . $fetch_delete_image['image2']);
   }
   if ($fetch_delete_image['image3'] != '') {
      unlink('../images/' . $fetch_delete_image['image3']);
   }
   if ($fetch_delete_image['image4'] != '') {
      unlink('../images/' . $fetch_delete_image['image4']);
   }
   $delete_post = $conn->prepare("DELETE FROM `post` WHERE id = ?");
   $delete_post->execute([$id]);
   $delete_comment = $conn->prepare("DELETE FROM `comment` WHERE id = ?");
   $delete_comment->execute([$id]);
   $delete_like = $conn->prepare("DELETE FROM `like` WHERE id = ?");
   $delete_like->execute([$id]);
   $affected_rows = $delete_post->rowCount();
   if ($affected_rows > 0) {
      $success_msg[] = 'Post is deleted!';
      $_SESSION['success_msg'] = $success_msg;
      header('location:post_view.php');
      exit();
   } else {
      $error_msg[] = 'Failed to delete the post.';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>post List</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include "../user/user_header.php"; 
?>

<section class="ar_view">

   <h1 class="heading">petagram</h1>
   <div class="right-align">
      <a href="post_add.php" class="inline-btn" style="margin-bottom:.5rem;">add post</a>
   </div>
   <form action="post_search.php" method="post" class="search">
      <input type="text" name="search_post" maxlength="100" placeholder="search post..." required>
      <button type="submit" name="search_post_btn" class="fas fa-search"></button>
   </form>
   <div class="box">
         <div class="flex-btn">
            <a href="post_status.php?status=published&admin_id=<?= $id; ?>" class="btn">My Published post</a>
            <a href="post_status.php?status=draft&admin_id=<?= $id; ?>" class="option-btn">My Draft</a>

    <a href="post_view.php" class="exit-btn">View All posts</a>
         </div>
      </div>

   <div class="box_container">

      <?php
      $select_post = $conn->prepare("SELECT * FROM `post` WHERE status = 'published' ORDER BY date DESC");
      $select_post->execute();
         // $select_post = $conn->prepare("SELECT * FROM `post` WHERE status = 'published' AND author_id = ? ORDER BY date DESC");
         // $select_post->execute([$id]);
         if ($select_post->rowCount() > 0) {
            while ($fetch_post = $select_post->fetch(PDO::FETCH_ASSOC)) {
               $post_id = $fetch_post['id'];

               $comment = $conn->prepare("SELECT * FROM `comment` WHERE post_id = ?");
               $comment->execute([$post_id]);
               $total_comments = $comment->rowCount();

               $like = $conn->prepare("SELECT * FROM `like` WHERE post_id = ?");
               $like->execute([$post_id]);
               $total_likes = $like->rowCount();
               
               $confirm_like = $conn->prepare("SELECT * FROM `like` WHERE author_id = ? AND post_id = ?");
               $confirm_like->execute([$id, $post_id]);

               $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
               $select_admin->execute([$fetch_post['author_id']]);
               $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

               $select_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
               $select_user->execute([$fetch_post['author_id']]);
               $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);
      ?>
      <form method="post" class="box">
      <input type="hidden" name="post_id" value="<?= $post_id; ?>">
      <div class="column">
      <?php if ($fetch_user && $fetch_user !== false) { ?>
        <a href="../user/user_detail.php?user_id=<?= $fetch_post['author_id']; ?>">
            <img src="../images/<?= $fetch_user['image']; ?>" class="image" alt="">
        </a>
        <div>
            <a href="../user/user_detail.php?user_id=<?= $fetch_post['author_id']; ?>">
                <?= $fetch_user['name']; ?>
            </a>
            <div><?= $fetch_post['date']; ?></div>
        </div>
        <?php } elseif ($fetch_admin && $fetch_admin !== false) { ?>
      <a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>">
      <img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt=""></a>
            <div>
            <a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['name']; ?></a>
               <div><?= $fetch_post['date']; ?></div>
            </div>
            <?php } ?>
         </div>
         <?php if ($fetch_post['image'] != '') { ?>
            <a href="post_detail.php?post_id=<?= $post_id; ?>" class="img" alt="">
            <img src="../images/<?= $fetch_post['image']; ?>" class="article_image" alt=""></a>
         <?php } ?>
         <div class="status" style="background-color:<?php if ($fetch_post['status'] == 'published') {echo 'limegreen'; } else {echo 'coral';}; ?>;"><?= $fetch_post['status']; ?></div>
         <a href="post_detail.php?post_id=<?= $post_id; ?>" class="img" alt="">      
         <div class="title"><?= $fetch_post['title']; ?></div></a>
               <div class="posts-content"><?= $fetch_post['content']; ?></div>
            <div class="icons">
            <button type="submit" name="like_post" id="like-button"><i class="fas fa-heart" style="<?php if($confirm_like->rowCount() > 0){ echo 'color:var(--main-color);background-color:white;'; } ?>"></i><span><?= $total_likes; ?></span></button>
            <a href="post_detail.php?post_id=<?= $post_id; ?>" class="comments"><i class="fas fa-comment"></i><span><?= $total_comments; ?></span></a>
         </div>
         <a href="post_detail.php?post_id=<?= $post_id; ?>" class="btn">view post</a>
         <?php if ($fetch_post['author_id'] == $_SESSION['id']) { ?>
         <div class="flex-btn">
            <a href="post_edit.php?id=<?= $post_id; ?>" class="option-btn">edit</a>
            <a href="post_view.php?id=<?= $post_id; ?>&delete=1" class="delete-btn" onclick="return delete_msg(event);">delete</a>
         </div>
         <?php } ?>
      </form>
      <?php
            }
         } else {
            echo '<p class="empty">no post added yet! <a href="post_add.php" class="btn" style="margin-top:1.5rem;">add post</a></p>';
         }
      ?>

   </div>
</section>
<?php include 'footer.php'; ?>
<script src="../js/script.js"></script>
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
    }
</script>
</body>
</html>