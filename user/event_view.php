<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: user_login.php');
   exit();
}    

if (isset($_POST['like_post'])) {
   $event_id = $_POST['post_id'];
   $event_id = filter_var($event_id, FILTER_SANITIZE_SPECIAL_CHARS);
   
   if ($event_id != '') {
      $event_id= $_POST['post_id'];
      $event_id= filter_var($event_id, FILTER_SANITIZE_SPECIAL_CHARS);
      // $id = $_POST['id'];
      // $id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);

      $select_like = $conn->prepare("SELECT * FROM `like` WHERE post_id = ? AND author_id = ?");
      $select_like->execute([$event_id, $id]);

      if ($select_like->rowCount() > 0) {
         $remove_like = $conn->prepare("DELETE FROM `like` WHERE post_id = ? AND author_id = ?");
         $remove_like->execute([$event_id, $id]);
         $warning_msg[] = 'removed from like';
      } else {
         $add_like = $conn->prepare("INSERT INTO `like`( post_id, author_id) VALUES(?,?)");
         $add_like->execute([ $event_id, $id]);
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
   <title>Event List</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include "../user/user_header.php"; 
?>

<section class="ar_view">

   <h1 class="heading">Event list</h1>
   <form action="event_search.php" method="post" class="search">
      <input type="text" name="search_event" maxlength="100" placeholder="search event..." required>
      <button type="submit" name="search_event_btn" class="fas fa-search"></button>
   </form>

   <div class="box_container">

      <?php
      $event = $conn->prepare("SELECT * FROM `event` WHERE status = 'published' ORDER BY date DESC");
         $event->execute();
         if ($event->rowCount() > 0) {
            while ($fetch_event = $event->fetch(PDO::FETCH_ASSOC)) {
               $event_id = $fetch_event['id'];

               $comment = $conn->prepare("SELECT * FROM `comment` WHERE post_id = ?");
               $comment->execute([$event_id]);
               $total_comments = $comment->rowCount();

               $like = $conn->prepare("SELECT * FROM `like` WHERE post_id = ?");
               $like->execute([$event_id]);
               $total_likes = $like->rowCount();
               
               $confirm_like = $conn->prepare("SELECT * FROM `like` WHERE author_id = ? AND post_id = ?");
               $confirm_like->execute([$id, $event_id]);

               $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
               $select_admin->execute([$fetch_event['admin_id']]);
               $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
      ?>
      
      <form method="post" class="box">
      <input type="hidden" name="post_id" value="<?= $event_id; ?>">
      <div class="column">
      <a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>">
      <img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt=""></a>
            <div>
            <a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['name']; ?></a>
               <!-- <div><?= $fetch_event['date']; ?></div> -->
            </div>
         </div>
         <?php if ($fetch_event['image'] != '') { ?>
            <a href="event_detail.php?event_id=<?= $event_id; ?>" class="img" alt="">
            <img src="../images/<?= $fetch_event['image']; ?>" class="article_image" alt=""></a>
         <?php } ?>
         <div class="status" style="background-color:<?php if ($fetch_event['status'] == 'published') {echo 'limegreen'; } else {echo 'coral';}; ?>;"><?= $fetch_event['status']; ?></div>
         <a href="event_detail.php?event_id=<?= $event_id; ?>" class="img" alt="">      
         <div class="title"><?= $fetch_event['title']; ?></div></a>
         <div class="price"><?= $fetch_event['date']; ?></div>
               <div class="posts-content"><?= $fetch_event['content']; ?></div>
            <div class="icons">
            <button type="submit" name="like_post" id="like-button"><i class="fas fa-heart" style="<?php if($confirm_like->rowCount() > 0){ echo 'color:var(--main-color);background-color:white;'; } ?>"></i><span><?= $total_likes; ?></span></button>
            <a href="event_detail.php?event_id=<?= $event_id; ?>" class="comments"><i class="fas fa-comment"></i><span><?= $total_comments; ?></span></a>
         </div>
         <a href="event_detail.php?event_id=<?= $event_id; ?>" class="btn">view event</a>
      </form>
      <?php
            }
         } else {
            echo '<p class="empty">no event added yet! <a href="event_add.php" class="btn" style="margin-top:1.5rem;">add event</a></p>';
         }
      ?>

   </div>
</section>
<?php include 'footer.php'; ?>
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