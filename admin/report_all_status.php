<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}    

if(isset($_SESSION['success_msg'])){
   $success_msg = $_SESSION['success_msg'];
   unset($_SESSION['success_msg']); // Unset the success message from session

}

if (isset($_GET['delete'])) {
   $id = $_GET['id'];
   $id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);
   $delete_image = $conn->prepare("SELECT * FROM `report` WHERE id = ?");
   $delete_image->execute([$id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if ($fetch_delete_image['image'] != '') {
      unlink('../images/' . $fetch_delete_image['image']);
   }
   $delete_report= $conn->prepare("DELETE FROM `report` WHERE id = ?");
   $delete_report->execute([$id]);

   $affected_rows = $delete_report->rowCount();
   if ($affected_rows > 0) {
      $success_msg[] = 'report deleted successfully!';
      header('location:report_view.php');
      exit();
   } else {
      $error_msg[] = 'Failed to delete the kid.';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>report view</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
include "../admin/admin_header.php"; 
?>

<section class="ar_view">

<h1 class="heading">report list</h1>
<form action="report_search.php" method="post" class="search">
      <input type="text" name="search_report" maxlength="100" placeholder="search report..." required>
      <button type="submit" name="search_report_btn" class="fas fa-search"></button>
   </form>
<div class="box">
<?php
      $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
      $select_admin->execute([$id]);
      $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
      $role = $fetch_admin['role'];    
      ?>
   <div class="flex-btn">
      <a href="report_view.php" class="btn">Waiting for Centre</a>
      <a href="report_status.php?status=undergoing" class="btn">My Undergoing</a>
      <a href="report_status.php?status=finish" class="btn">My Finish</a>
      <?php if ($role == 1) { ?>
         <a href="report_all_status.php" class="btn">View all Report Status</a>
      <?php } ?>
   </div>
</div>
   <div class="box_container">

      <?php
         $report= $conn->prepare("SELECT * FROM `report`");
         $report->execute();
         if ($report->rowCount() > 0) {
            while ($fetch_report= $report->fetch(PDO::FETCH_ASSOC)) {
               $report_id = $fetch_report['id'];

               $select_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
               $select_user->execute([$fetch_report['reporter_id']]);
               $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

               $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
               $select_admin->execute([$fetch_report['centre_id']]);
               $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

      ?>
      <form method="post" class="box">
      <input type="hidden" name="report_id" value="<?= $report_id; ?>">
      <div class="column">
      <a href="../user/user_detail.php?user_id=<?= $fetch_user['id']; ?>">
      <img src="../images/<?= $fetch_user['image']; ?>" class="image" alt=""></a>
            <div>
            <a href="../user/user_detail.php?user_id=<?= $fetch_user['id']; ?>"><?= $fetch_user['name']; ?></a>
               <div><?= $fetch_report['date']; ?></div>
            </div>
         </div>
            <img src="../images/<?= $fetch_report['image']; ?>" class="article_image" alt="">
         <div class="status" style="background-color:<?php if ($fetch_report['status'] == 'waiting for centre') {echo 'red'; } else if ($fetch_report['status'] == 'undergoing') {echo 'blue'; } else {echo 'limegreen';}; ?>;"><?= $fetch_report['status']; ?></div>
         <div class="title"><i>ID: </i><?= $fetch_report['id']; ?></div></a>  
         <div class="title"><i>Reporter Name: </i><?= $fetch_user['name']; ?></div></a>
         <?php if ($fetch_report['centre_id'] != '') { ?>
            <div class="title"><i>Centre Name: </i><?= $fetch_admin['name']; ?></div>
            <?php } ?>      
         <div class="title"><i>Title: </i><?= $fetch_report['title']; ?></div></a>
         <div class="report"><?= $fetch_report['content']; ?></div>
         <div class="report"><i class="fas fa-tag"></i> <span><?= $fetch_report['location']; ?></span></div>
         <div class="flex-btn">
            <a href="report_edit.php?id=<?= $report_id; ?>" class="option-btn">edit</a>
            <a href="report_view.php?id=<?= $report_id; ?>&delete=1" class="delete-btn" onclick="return delete_msg(event);">delete</a>
         </div>
      </form>
      <?php
            }
         } else {
            echo '<p class="empty">no report yet!</p>';
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