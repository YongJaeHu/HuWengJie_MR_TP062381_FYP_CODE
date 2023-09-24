<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}    

// if(isset($_SESSION['success_msg'])){
//    $success_msg = $_SESSION['success_msg'];
//    unset($_SESSION['success_msg']); // Unset the success message from session

// }

if (isset($_POST['like_post'])) {
   $article_id = $_POST['post_id'];
   $article_id = filter_var($article_id, FILTER_SANITIZE_SPECIAL_CHARS);
   
   if ($article_id != '') {
      $article_id= $_POST['post_id'];
      $article_id= filter_var($article_id, FILTER_SANITIZE_SPECIAL_CHARS);
      // $id = $_POST['id'];
      // $id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);

      $select_like = $conn->prepare("SELECT * FROM `like` WHERE post_id = ? AND author_id = ?");
      $select_like->execute([$article_id, $id]);

      if ($select_like->rowCount() > 0) {
         $remove_like = $conn->prepare("DELETE FROM `like` WHERE post_id = ? AND author_id = ?");
         $remove_like->execute([$article_id, $id]);
         $warning_msg[] = 'removed from like';
      } else {
         $add_like = $conn->prepare("INSERT INTO `like`( post_id, author_id) VALUES(?,?)");
         $add_like->execute([ $article_id, $id]);
         $success_msg[] = 'added to like';
      }
   } else {
      $error_msg[] = 'Something Error!';
   }
}


if (isset($_GET['delete'])) {
   $id = $_GET['id'];
   $id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);
   $delete_image = $conn->prepare("SELECT * FROM `article` WHERE id = ?");
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
   $delete_post = $conn->prepare("DELETE FROM `article` WHERE id = ?");
   $delete_post->execute([$id]);
   $delete_comment = $conn->prepare("DELETE FROM `comment` WHERE id = ?");
   $delete_comment->execute([$id]);
   $delete_like = $conn->prepare("DELETE FROM `like` WHERE id = ?");
   $delete_like->execute([$id]);
   $affected_rows = $delete_post->rowCount();
   if ($affected_rows > 0) {
      $success_msg[] = 'Post is deleted!';
      $_SESSION['success_msg'] = $success_msg;
      header('location:article_view.php');
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
   <title>Article List</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
include "../admin/admin_header.php"; 
?>

<section class="ar_view">

   <h1 class="heading">article list</h1>
   <div class="right-align">
      <a href="article_add.php" class="inline-btn" style="margin-bottom:.5rem;">add article</a>
   </div>
   <form action="article_search.php" method="post" class="search">
      <input type="text" name="search_article" maxlength="100" placeholder="search article..." required>
      <button type="submit" name="search_article_btn" class="fas fa-search"></button>
   </form>
   <div class="box">
   <?php
      $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
      $select_admin->execute([$id]);
      $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
      $role = $fetch_admin['role'];    
      ?>
         <div class="flex-btn">
            <a href="article_status.php?status=published&admin_id=<?= $id; ?>" class="btn">My Published Article</a>
            <a href="article_status.php?status=draft&admin_id=<?= $id; ?>" class="option-btn">My Draft</a>
            <?php if ($role == 1) { ?>
    <a href="article_view.php" class="exit-btn">View All Articles</a>
<?php } ?>
         </div>
      </div>

   <div class="box_container">

      <?php
      if(isset($_POST['search_article']) or isset($_POST['search_article_btn'])){
         $search_article = $_POST['search_article'];
         $article = $conn->prepare("SELECT * FROM `article` WHERE title LIKE '%{$search_article}%' AND status = 'published'");
      $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
      $select_admin->execute([$id]);
      $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
      $role = $fetch_admin['role'];
      if ($role == 1) {
         $article->execute();
      } else {
         $article = $conn->prepare("SELECT * FROM `article` WHERE status = 'published' AND admin_id = ? ORDER BY date DESC");
         $article->execute([$id]);
      }
         if ($article->rowCount() > 0) {
            while ($fetch_article = $article->fetch(PDO::FETCH_ASSOC)) {
               $article_id = $fetch_article['id'];

               $comment = $conn->prepare("SELECT * FROM `comment` WHERE post_id = ?");
               $comment->execute([$article_id]);
               $total_comments = $comment->rowCount();

               $like = $conn->prepare("SELECT * FROM `like` WHERE post_id = ?");
               $like->execute([$article_id]);
               $total_likes = $like->rowCount();
               
               $confirm_like = $conn->prepare("SELECT * FROM `like` WHERE author_id = ? AND post_id = ?");
               $confirm_like->execute([$id, $article_id]);

               $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
               $select_admin->execute([$fetch_article['admin_id']]);
               $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
      ?>
      
      <form method="post" class="box">
      <input type="hidden" name="post_id" value="<?= $article_id; ?>">
      <div class="column">
      <a href="../admin/admin_detail.php?admin_id=<?= $fetch_admin['id']; ?>">
      <img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt=""></a>
            <div>
            <a href="../admin/admin_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['name']; ?></a>
               <div><?= $fetch_article['date']; ?></div>
            </div>
         </div>
         <?php if ($fetch_article['image'] != '') { ?>
            <a href="article_detail.php?post_id=<?= $article_id; ?>" class="img" alt="">
            <img src="../images/<?= $fetch_article['image']; ?>" class="article_image" alt=""></a>
         <?php } ?>
         <div class="status" style="background-color:<?php if ($fetch_article['status'] == 'published') {echo 'limegreen'; } else {echo 'coral';}; ?>;"><?= $fetch_article['status']; ?></div>
         <a href="article_detail.php?post_id=<?= $article_id; ?>" class="img" alt="">      
         <div class="title"><?= $fetch_article['title']; ?></div></a>
               <div class="posts-content"><?= $fetch_article['content']; ?></div>
            <div class="icons">
            <button type="submit" name="like_post" id="like-button"><i class="fas fa-heart" style="<?php if($confirm_like->rowCount() > 0){ echo 'color:var(--main-color);background-color:white;'; } ?>"></i><span><?= $total_likes; ?></span></button>
            <a href="article_detail.php?post_id=<?= $article_id; ?>" class="comments"><i class="fas fa-comment"></i><span><?= $total_comments; ?></span></a>
         </div>
         <a href="article_detail.php?post_id=<?= $article_id; ?>" class="btn">view article</a>
         <div class="flex-btn">
            <a href="article_edit.php?id=<?= $article_id; ?>" class="option-btn">edit</a>
            <a href="article_view.php?id=<?= $article_id; ?>&delete=1" class="delete-btn" onclick="return delete_msg(event);">delete</a>
         </div>
      </form>
      <?php
            }
         } else{
            echo '<p class="empty">no results found!</p>';
         }
      }else{
         echo '<p class="empty">please search something!</p>';
      }
      ?>

   </div>
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
    }
</script>
</body>
</html>