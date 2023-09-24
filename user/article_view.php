<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: user_login.php');
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Article List</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include "../user/user_header.php"; 
?>

<section class="ar_view">

   <h1 class="heading">article list</h1>
   <form action="article_search.php" method="post" class="search">
      <input type="text" name="search_article" maxlength="100" placeholder="search article..." required>
      <button type="submit" name="search_article_btn" class="fas fa-search"></button>
   </form>
   <div class="box_container">

      <?php
      $article = $conn->prepare("SELECT * FROM `article` WHERE status = 'published' ORDER BY date DESC");
      $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
      $select_admin->execute([$id]);
      $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
         $article->execute();
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
      <a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>">
      <img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt=""></a>
            <div>
            <a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['name']; ?></a>
               <div><?= $fetch_article['date']; ?></div>
            </div>
         </div>
         <?php if ($fetch_article['image'] != '') { ?>
            <a href="../user/article_detail.php?post_id=<?= $article_id; ?>" class="img" alt="">
            <img src="../images/<?= $fetch_article['image']; ?>" class="article_image" alt=""></a>
         <?php } ?>
         <div class="status" style="background-color:<?php if ($fetch_article['status'] == 'published') {echo 'limegreen'; } else {echo 'coral';}; ?>;"><?= $fetch_article['status']; ?></div>
         <a href="../user/article_detail.php?post_id=<?= $article_id; ?>" class="img" alt="">      
         <div class="title"><?= $fetch_article['title']; ?></div></a>
               <div class="posts-content"><?= $fetch_article['content']; ?></div>
            <div class="icons">
            <button type="submit" name="like_post" id="like-button"><i class="fas fa-heart" style="<?php if($confirm_like->rowCount() > 0){ echo 'color:var(--main-color);background-color:white;'; } ?>"></i><span><?= $total_likes; ?></span></button>
            <a href="../user/article_detail.php?post_id=<?= $article_id; ?>" class="comments"><i class="fas fa-comment"></i><span><?= $total_comments; ?></span></a>
         </div>
         <a href="../user/article_detail.php?post_id=<?= $article_id; ?>" class="btn">view article</a>
      </form>
      <?php
            }
         } else {
            echo '<p class="empty">no article added yet! <a href="article_add.php" class="btn" style="margin-top:1.5rem;">add post</a></p>';
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