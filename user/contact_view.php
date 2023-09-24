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

if (isset($_GET['delete'])) {
   $report_id = $_GET['id'];
   $report_id = filter_var($report_id, FILTER_SANITIZE_SPECIAL_CHARS);
   
   // Corrected variable name here
   $delete_image = $conn->prepare("SELECT * FROM `contact` WHERE id = ?");
   $delete_image->execute([$report_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   
   if ($fetch_delete_image['image'] != '') {
      unlink('../images/' . $fetch_delete_image['image']);
   }
   $delete_report= $conn->prepare("DELETE FROM `contact` WHERE id = ?");
   $delete_report->execute([$report_id]);

   $affected_rows = $delete_report->rowCount();
   if ($affected_rows > 0) {
      $success_msg[] = 'Message deleted successfully!';
      // header('location:contact_view.php');
      // exit();
   } else {
      $error_msg[] = 'Failed to delete the message.';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact view</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include "../user/user_header.php"; 
?>

<section class="ar_view">

<h1 class="heading">Contact list</h1>
   <form action="contact_search.php" method="post" class="search">
      <input type="text" name="search_contact" maxlength="100" placeholder="search contact..." required>
      <button type="submit" name="search_contact_btn" class="fas fa-search"></button>
   </form>
<div class="box">


   <div class="flex-btn">
      <a href="contact_view.php" class="btn">Unreply</a>
      <a href="contact_replied.php" class="btn">Replied</a>
      <a href="contact_view_own.php" class="btn">My own message</a>
   </div>
</div>
   <div class="box_container">

      <?php
         $report= $conn->prepare("SELECT * FROM `contact` where status = 'unreply' and receiver_id = ?");
         $report->execute([$id]);
         if ($report->rowCount() > 0) {
            while ($fetch_report= $report->fetch(PDO::FETCH_ASSOC)) {
               $report_id = $fetch_report['id'];

               $select_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
               $select_user->execute([$fetch_report['sender_id']]);
               $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

               $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
               $select_admin->execute([$fetch_report['sender_id']]);
               $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

      ?>
      <form method="post" class="box">
      <input type="hidden" name="report_id" value="<?= $report_id; ?>">
      <div class="column">
      <?php if ($fetch_admin && $fetch_admin !== false) { ?>
      <a href="../admin/admin_detail.php?admin_id=<?= $fetch_admin['id']; ?>">
      <img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt=""></a>
            <div>
            <a href="../admin/admin_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['name']; ?></a>
               <div><?= $fetch_report['date']; ?></div>
            </div>
            <?php } elseif ($fetch_user && $fetch_user !== false) { ?>
               <a href="../user/user_detail.php?user_id=<?= $fetch_user['id']; ?>">
               <img src="../images/<?= $fetch_user['image']; ?>" class="image" alt=""></a>
               <div>
                  <a href="../user/user_detail.php?user_id=<?= $fetch_user['id']; ?>"><?= $fetch_user['name']; ?></a>
                  <div><?= $fetch_report['date']; ?></div>
               </div>
            <?php } ?>
         </div>
            <img src="../images/<?= $fetch_report['image']; ?>" class="article_image" alt="">
         <div class="status" style="background-color:<?php if ($fetch_report['status'] == 'unreply') {echo 'red'; } else {echo 'limegreen';}; ?>;"><?= $fetch_report['status']; ?></div>
         <div class="title"><i>ID: </i><?= $fetch_report['id']; ?></div></a>      
         <div class="title"><i>Title: </i><?= $fetch_report['title']; ?></div></a>
         <div class="report"><i>Content: </i><?= $fetch_report['content']; ?></div>
         <div class="flex-btn">
         <a href="contact_reply_add.php?prev_report_id=<?= $report_id; ?>&receiver_id=<?= $fetch_report['sender_id']; ?>" class="option-btn">reply</a>
            <!-- <a href="contact_view.php?id=<?= $report_id; ?>&delete=1" class="delete-btn" onclick="return delete_msg(event);">delete</a> -->
         </div>
      </form>
      <?php
            }
         } else {
            echo '<p class="empty">no contact yet!</p>';
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