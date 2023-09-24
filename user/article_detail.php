<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: user_login.php');
   exit();
}

$admin_id = $_SESSION['id'];
// $user_email = $_SESSION['email'];
$article_id= $_GET['post_id'];

if (isset($_POST['like_post'])) {
   if ($article_id!= '') {
      $article_id= $_POST['post_id'];
      $article_id= filter_var($article_id, FILTER_SANITIZE_SPECIAL_CHARS);
      // $id = $_POST['id'];
      // $id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);

      $select_like = $conn->prepare("SELECT * FROM `like` WHERE post_id = ? AND author_id = ?");
      $select_like->execute([$article_id, $admin_id]);

      if ($select_like->rowCount() > 0) {
         $remove_like = $conn->prepare("DELETE FROM `like` WHERE post_id = ? AND author_id = ?");
         $remove_like->execute([$article_id, $admin_id]);
         $warning_msg[] = 'removed from like';
      } else {
         $add_like = $conn->prepare("INSERT INTO `like`( post_id, author_id) VALUES(?,?)");
         $add_like->execute([ $article_id, $admin_id]);
         $success_msg[] = 'added to like';
      }
   } else {
      $error_msg[] = 'please login first!';
   }
}

if (isset($_POST['add_comment'])) {
   if ($article_id!= '') {
      // $id = uni_id();
      $article_id= $_POST['post_id'];
      $article_id= filter_var($article_id, FILTER_SANITIZE_SPECIAL_CHARS);
      $comment = $_POST['comment'];
      $comment = filter_var($comment, FILTER_SANITIZE_SPECIAL_CHARS);

      // Retrieve user information based on the logged-in user's session
      // $user_email = $_SESSION['email'];
      $select_content = $conn->prepare("SELECT * FROM `article` WHERE id = ? LIMIT 1");
      $select_content->execute([$article_id]);
      $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);

      if ($select_content->rowCount() > 0) {
         $insert_comment = $conn->prepare("INSERT INTO `comment`(post_id, commentor_id, comment) VALUES(?,?,?)");
         $insert_comment->execute([$article_id, $admin_id, $comment]);
         $success_msg[] = 'New comment added!';
         // $_SESSION['success_msg'] = $success_msg;
      } else {
         $error_msg[] = 'Something went wrong!';
      }
   } else {
      $info_msg[] = 'Please login first!';
   }
}

if (isset($_POST['update_now'])) {
   $update_id = $_POST['update_id'];
   $update_id = filter_var($update_id, FILTER_SANITIZE_SPECIAL_CHARS);
   $update_box = $_POST['update_box'];
   $update_box = filter_var($update_box, FILTER_SANITIZE_SPECIAL_CHARS);

   $verify_comment = $conn->prepare("SELECT * FROM `comment` WHERE id = ?");
   $verify_comment->execute([$update_id]);
   $existing_comment = $verify_comment->fetch(PDO::FETCH_ASSOC);

   if ($existing_comment && $existing_comment['comment'] == $update_box) {
      $error_msg[] = 'The comment content is the same. No changes were made.';
   } else {
      $update_comment = $conn->prepare("UPDATE `comment` SET comment = ? WHERE id = ?");
      $update_comment->execute([$update_box, $update_id]);
      $success_msg[] = 'Comment edited successfully!';
      $_SESSION['success_msg'] = $success_msg;
   }
}

if (isset($_GET['delete_comment'])) {
   $comment_id = $_GET['comment_id'];
   $comment_id = filter_var($comment_id, FILTER_SANITIZE_STRING);
   $delete_comment = $conn->prepare("DELETE FROM `comment` WHERE id = ?");
   $delete_comment->execute([$comment_id]);
   $success_msg[] = 'Comment deleted!';
   // $_SESSION['success_msg'] = $success_msg;

   // Redirect back to the same page to reflect the changes
   // header("Location: article_detail.php?post_id=$article_id");
   // exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>article</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include "../user/user_header.php"; 
?>

<?php
   if(isset($_POST['edit_comment'])){
      $edit_id = $_POST['comment_id'];
      $edit_id = filter_var($edit_id, FILTER_SANITIZE_STRING);
      $verify_comment = $conn->prepare("SELECT * FROM `comment` WHERE id = ? LIMIT 1");
      $verify_comment->execute([$edit_id]);
      if($verify_comment->rowCount() > 0){
         $fetch_edit_comment = $verify_comment->fetch(PDO::FETCH_ASSOC);
?>
<section class="com_edit">
   <h1 class="heading">edit comment</h1>
   <form action="" method="post">
      <input type="hidden" name="update_id" value="<?= $fetch_edit_comment['id']; ?>">
      <textarea name="update_box" class="box" maxlength="10000" required placeholder="please enter your comment" cols="30" rows="10"><?= $fetch_edit_comment['comment']; ?></textarea>
      <div class="flex">
         <a href="article_detail.php?post_id=<?= $article_id; ?>" class="inline-exit-btn" onclick="confirm_msg(event);">cancel edit</a>
         <input type="submit" value="update now" name="update_now" class="inline-btn">
      </div>
   </form>
</section>
<?php
   }else{
      $error_msg[] = 'comment was not found!';
   }
}
?>

<section class="detail_container">
<h1 class="heading">article detail</h1>
   <div class="box_container">
      <?php
      // $article = $conn->prepare("SELECT * FROM `post` WHERE email = ? AND title = ?");
      // $article->execute([$email, $title]);
      $article = $conn->prepare("SELECT * FROM `article` WHERE id = ?");
      $article->execute([$article_id]);
      if($article->rowCount() > 0){
         while($fetch_post = $article->fetch(PDO::FETCH_ASSOC)){
            $article_id= $fetch_post['id'];
            // $email = $fetch_post['admin_id'];

            $count_comment = $conn->prepare("SELECT * FROM `comment` WHERE post_id = ?");
            $count_comment->execute([$article_id]);
            $total_comment = $count_comment->rowCount();

            $count_like = $conn->prepare("SELECT * FROM `like` WHERE post_id = ?");
            $count_like->execute([$article_id]);
            $total_like = $count_like->rowCount();

            $confirm_like = $conn->prepare("SELECT * FROM `like` WHERE author_id = ? AND post_id = ?");
            $confirm_like->execute([$admin_id, $article_id]);

            $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
            $select_admin->execute([$fetch_post['admin_id']]);
            $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
            ?>
            <form class="box" method="post">
               <input type="hidden" name="post_id" value="<?= $article_id; ?>">
               <input type="hidden" name="email" value="<?= $email; ?>">
               <div class="status" style="background-color:<?php if($fetch_post['status'] == 'published'){echo 'limegreen'; }else{echo 'coral';}; ?>;"><?= $fetch_post['status']; ?></div>
               <div class="column">
               <a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>">
                  <img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt=""></a>
                  <div><a href="../user/centre_detail.php?admin_id=<?= $fetch_post['admin_id']; ?>"><?= $fetch_admin['name']; ?></a><div>
                     <?= $fetch_post['date']; ?></div>
                  </div>
               </div>
               <!-- <?php if ($fetch_post['image'] != '') { ?>
                  <img src="../images/<?= $fetch_post['image']; ?>" class="article_image" alt="">
                  <?php } ?> -->
                  <div class="swiper images-container">
                     <div class="swiper-wrapper">
                        <?php if ($fetch_post['image'] != '') { ?>
                           <img src="../images/<?= $fetch_post['image']; ?>" class="swiper-slide" alt="">
                           <?php } ?>
                           <?php if(!empty($fetch_post['image2'])){ ?>
                              <img src="../images/<?= $fetch_post['image2']; ?>" alt="" class="swiper-slide">
                           <?php } ?>
                           <?php if(!empty($fetch_post['image3'])){ ?>
                              <img src="../images/<?= $fetch_post['image3']; ?>" alt="" class="swiper-slide">
                           <?php } ?>
                           <?php if(!empty($fetch_post['image4'])){ ?>
                              <img src="../images/<?= $fetch_post['image4']; ?>" alt="" class="swiper-slide">
                           <?php } ?>                               
                        </div>
               <div class="swiper-button-next"></div>
               <div class="swiper-button-prev"></div>
               <div class="swiper-pagination"></div>
            </div>
            <div class="title"><?= $fetch_post['title']; ?></div>
            <div class="content"><?= $fetch_post['content']; ?></div>
            <div class="icons">
               <button type="submit" name="like_post" id="like-button">
                  <i class="fas fa-heart" style="<?php if($confirm_like->rowCount() > 0){ echo 'color:var(--main-color);background-color:white;'; } ?>"></i><span><?= $total_like; ?></span></button>
                  <div class="comment"><i class="fas fa-comment" id="comment-icon"></i><span><?= $total_comment; ?></span></div>
               </div>
               <a href="article_view.php" class="inline-exit-btn">go back</a>
            </form>
            <?php
         }
      }else{
         echo '<p class="empty">no post added yet! <a href="add_post.php" class="btn" style="margin-top:1.5rem;">add post</a></p>';
      }
   ?>
   </div>
</div>
</div>
</section>

<section class="comment">
   <h1 id="add-comment" class="heading">Add comment</h1>
   <form action="" method="post" class="comm_add">
      <input type="hidden" name="name" value="<?= $name; ?>">
      <input type="hidden" name="id" value="<?= $id; ?>">
      <input type="hidden" name="post_id" value="<?= $article_id; ?>">
      <textarea name="comment" required placeholder="write your comment..." maxlength="10000" cols="30" rows="10"></textarea>
      <input type="submit" value="add comment" name="add_comment" class="inline-btn">
   </form>

   <h1 class="heading">comment</h1>
<div class="comm_dis">
<?php
$email = $_SESSION['email'];
$select_comment = $conn->prepare("SELECT * FROM `comment` WHERE post_id = ?");
$select_comment->execute([$article_id]);
if ($select_comment->rowCount() > 0) {
   while ($fetch_comment = $select_comment->fetch(PDO::FETCH_ASSOC)) {
      $select_commentor = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
      $select_commentor->execute([$fetch_comment['commentor_id']]);
      $fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC);
      
      $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
      $select_admin->execute([$fetch_comment['commentor_id']]);
      $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
      
      ?>
      <div class="box">
         <div class="user">
            <?php if ($fetch_commentor && $fetch_commentor !== false) { ?>
               <a href="../user/user_detail.php?user_id=<?= $fetch_commentor['id']; ?>">
               <img src="../images/<?= $fetch_commentor['image']; ?>" alt=""></a>
               <div>
                  <h3>
                  <a href="../user/user_detail.php?user_id=<?= $fetch_commentor['id']; ?>"><?= $fetch_commentor['name']; ?></a></h3>
                  <span><?= $fetch_comment['date']; ?></span>
               </div>
            <?php } elseif ($fetch_admin && $fetch_admin !== false) { ?>
               <a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>">
               <img src="../images/<?= $fetch_admin['image']; ?>" alt=""></a>
               <div>
                  <h3><a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['name']; ?></a></h3>
                  <span><?= $fetch_comment['date']; ?></span>
               </div>
            <?php } ?>
         </div>
         <p class="text"><?= $fetch_comment['comment']; ?></p>
         <?php if ($fetch_comment['commentor_id'] == $admin_id) { ?>
            <form action="" method="post" class="flex-btn">
               <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
               <button type="submit" name="edit_comment" class="inline-option-btn">edit comment</button>
               <a href="article_detail.php?post_id=<?= $article_id; ?>&delete_comment=true&comment_id=<?= $fetch_comment['id']; ?>" class="inline-delete-btn" onclick="delete_msg(event);">delete comment</a>

            </form>
         <?php } ?>
      </div>
      <?php
   }
} else {
   echo '<p class="empty">no comment added yet!</p>';
}
?>

</div>
</section>
<?php include 'footer.php'; ?>   
<script src="../js/admin_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<?php include '../message.php'; ?>
<script>
var swiper = new Swiper(".images-container", {
   pagination: {
        el: ".swiper-pagination",
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
</script>
<script>
   // Like button click event
   document.getElementById('like-button').addEventListener('click', function(e) {
      e.stopPropagation(); 

   });

   // Comment icon click event
   document.getElementById('comment-icon').addEventListener('click', function(e) {
      e.stopPropagation(); 
      window.location.href = '#add-comment';
   });

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

    function confirm_msg(event) {
    event.preventDefault();
    var urlToRedirect = event.currentTarget.getAttribute('href');  
    console.log(urlToRedirect); 
    swal({
        title: "Are you sure to Go Back without Saving?",
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