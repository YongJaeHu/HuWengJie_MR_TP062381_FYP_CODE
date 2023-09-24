<?php
include "../connect.php";

session_start();

$Email = $_GET['email'];

if (isset($_GET["delete"])) {
   $deleteid = $_GET['id'];
   $deleteid = filter_var($deleteid, FILTER_SANITIZE_SPECIAL_CHARS);
   $delete_image = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
   $delete_image->execute([$deleteid]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if ($fetch_delete_image['image'] != '') {
      unlink('../images/' . $fetch_delete_image['image']);
   }
   $delete_centre = $conn->prepare("DELETE FROM `admin` WHERE id = ?");
   $delete_centre->execute([$deleteid]);
   $affected_rows = $delete_centre->rowCount();
   if ($affected_rows > 0) {
      $success_msg[] = 'Approval rejected successfully!';
   } else {
      $error_msg[] = 'Failed to reject approval.';
   }
   header('Location: approval_view.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Approval</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>
<body style="padding-left: 0;">
   <section class="ar_view">

      <h1 class="heading">Centre Approval</h1>

      <div class="box_container">

         <?php
         $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE email = ?");
         $select_admin->execute([$Email]);
         if ($select_admin->rowCount() > 0) {
            while ($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)) {
               $admin_id = $fetch_admin['id'];
         ?>
               <form method="post" action="approval_view.php" class="box">
               <input type="hidden" name="id" value="<?= $fetch_admin['id']; ?>">
                  <?php if ($fetch_admin['image'] != '') { ?>
                     <img src="../images/<?= $fetch_admin['image']; ?>" class="article_image" alt="">
                  <?php } ?>
                  <div class="status" style="background-color:<?php
                  if ($fetch_admin['approval'] == '0') {
                     echo 'coral';
                  } else {
                      echo 'green';
                     }
                     ?>;">
                     <?php
                      if ($fetch_admin['approval'] == '0') {
                         echo 'Waiting for Approval';
                        } else {
                           echo 'Approved';
                        }
                        ?>
                        </div>
                        <a href="centre_location.php?location=<?= $fetch_admin['location']; ?>" class="post-cat"> <i class="fas fa-tag"></i> <span><?= $fetch_admin['location']; ?></span></a>
                        <div class="title"><span>Name: </span><?= $fetch_admin['name']; ?></div></a>
                        <div class="title"><span>Email: </span><?= $fetch_admin['email']; ?></div></a>
                        <div class="title"><span>Address: </span><?= $fetch_admin['address']; ?></div></a>
                        <div class="title"><span>Phone: </span><?= $fetch_admin['phone']; ?></div></a>
                     <a href="approval_view.php?id=<?= $fetch_admin['id']; ?>&delete=1" class="delete-btn" onclick="return delete_msg(event);">Delete Approval</a>
                     <a href="../admin/admin_login.php" class="exit-btn">Go Back</a>
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
function confirm_msg(event) {
    event.preventDefault();
    var urlToRedirect = event.currentTarget.getAttribute('href');  
    console.log(urlToRedirect); 
    swal({
        title: "Are you sure to Approve?",
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
        title: "Are you sure to Delete Your Approval?",
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
</script>
</body>
</html>
