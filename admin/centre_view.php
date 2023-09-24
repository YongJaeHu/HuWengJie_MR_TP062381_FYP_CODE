<?php
include "../connect.php";

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}   

$name = ''; // Initialize the $name variable


if (isset($_GET['delete'])) {
   $centre_id = $_GET['admin_id'];
   $centre_id = filter_var($centre_id, FILTER_SANITIZE_SPECIAL_CHARS);
   $delete_image = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
   $delete_image->execute([$centre_id]);
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
   $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE id = ?");
   $delete_admin->execute([$centre_id]);
   $delete_article = $conn->prepare("DELETE FROM `article` WHERE admin_id = ?");
   $delete_article->execute([$centre_id]);
   $delete_post = $conn->prepare("DELETE FROM `post` WHERE author_id = ?");
   $delete_post->execute([$centre_id]);
   $delete_event = $conn->prepare("DELETE FROM `event` WHERE admin_id = ?");
   $delete_event->execute([$centre_id]);
   $delete_order = $conn->prepare("DELETE FROM `order` WHERE centre_id = ?");
   $delete_order->execute([$centre_id]);
   $delete_report = $conn->prepare("DELETE FROM `report` WHERE centre_id = ?");
   $delete_report->execute([$centre_id]);
   $delete_con = $conn->prepare("DELETE FROM `contribute` WHERE centre_id = ?");
   $delete_con->execute([$centre_id]);
   $delete_review = $conn->prepare("DELETE FROM `review` WHERE centre_id = ?");
   $delete_review->execute([$centre_id]);
   $delete_kid = $conn->prepare("DELETE FROM `kid` WHERE centre_id = ?");
   $delete_kid->execute([$centre_id]);
   $delete_comment = $conn->prepare("DELETE FROM `comment` WHERE commentor_id = ?");
   $delete_comment->execute([$centre_id]);
   $delete_like = $conn->prepare("DELETE FROM `like` WHERE author_id = ?");
   $delete_like->execute([$centre_id]);
   $affected_rows = $delete_admin->rowCount();
   if ($affected_rows > 0) {
      $success_msg[] = 'Centre is deleted!';
      // $_SESSION['success_msg'] = $success_msg;
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
   <title>Centre List</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>

   <?php
   include "../admin/admin_header.php";
   ?>

   <section class="ar_view">

      <h1 class="heading">Centre List</h1>
      <div class="right-align">
      <a href="admin_register_centre.php" class="inline-btn" style="margin-bottom:.5rem;">add centre</a>
   </div>
   <form action="centre_search.php" method="post" class="search">
      <input type="text" name="search_centre" maxlength="100" placeholder="search centre..." required>
      <button type="submit" name="search_centre_btn" class="fas fa-search"></button>
   </form>
      <?php
$select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
$select_admin->execute([$id]);
$fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
$role = $fetch_admin['role'];    
?>
<?php if ($role == 1) { ?>
<div class="box">
   <div class="flex-btn">
   <a href="centre_location_cat.php" class="option-btn">Sort By location</a>
   <a href="centre_view.php" class="exit-btn">Cancel Sort</a>
</div>
</div>
<?php } ?>

      <div class="box_container">

         <?php
         $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE approval = ?");
         $select_admin->execute(['1']);
         if ($select_admin->rowCount() > 0) {
            while ($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)) {
               $admin_id = $fetch_admin['id'];
         ?>
               <form method="post" action="approval_view.php" class="box">
               <input type="hidden" name="id" value="<?= $fetch_admin['id']; ?>">
               <div class="column">
      <a href="../admin/admin_detail.php?admin_id=<?= $fetch_admin['id']; ?>">
      <img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt=""></a>
            <div>
            <a href="../admin/admin_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['name']; ?></a>
               <div><a class = "small" href="../admin/admin_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['email']; ?></div>
            </div>
         </div>
                  <?php if ($fetch_admin['image'] != '') { ?>
                     <img src="../images/<?= $fetch_admin['image']; ?>" class="article_image" alt="">
                  <?php } ?>
                  <div class="status" style="background-color:<?php
               if ($fetch_admin['role'] == '1') {
                  echo 'orange';
               }
               ?>;">
                  <?php
                  if ($fetch_admin['role'] == '1') {
                     echo 'Admin';
                  }
                  ?>
               </div>
                  <div class="description"><?= $fetch_admin['description']; ?></div>
                  <a href="centre_location.php?location=<?= $fetch_admin['location']; ?>" class="post-cat"> <i class="fas fa-tag"></i> <span><?= $fetch_admin['location']; ?></span></a>
                  <a href="admin_detail.php?admin_id=<?= $fetch_admin['id']; ?>" class="btn">view detail</a>

                  <div class="flex-btn">
                  <a href="centre_edit_role.php?admin_id=<?= $fetch_admin['id']; ?>" class="option-btn">edit</a>
                  <a href="centre_view.php?admin_id=<?= $fetch_admin['id']; ?>&delete=1" class="delete-btn" onclick="return delete_msg(event);">delete</a>
                  </div>
               </form>
         <?php }
         } else { ?>
            <p class="empty">no approval yet!</p>
         <?php } ?>

      </div>
   </section>
   <script src="../js/admin_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
<script>
function update_msg(event) {
    event.preventDefault();
    var urlToRedirect = event.currentTarget.getAttribute('href');  
    console.log(urlToRedirect); 
    swal({
        title: "Are you sure to Update this as Admin?",
        text: "Be careful.",
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