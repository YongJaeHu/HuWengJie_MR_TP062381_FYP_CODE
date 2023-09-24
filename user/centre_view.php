<?php
include "../connect.php";

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: user_login.php');
   exit();
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
   <link rel="stylesheet" href="../css/style.css">
</head>

<body>

   <?php
   include "../user/user_header.php";
   ?>

   <section class="ar_view">

      <h1 class="heading">Centre List</h1>
   <form action="centre_search.php" method="post" class="search">
      <input type="text" name="search_centre" maxlength="100" placeholder="search centre..." required>
      <button type="submit" name="search_centre_btn" class="fas fa-search"></button>
   </form>
<div class="box">
   <div class="flex-btn">
   <a href="centre_location_cat.php" class="option-btn">Sort By location</a>
   <a href="centre_view.php" class="exit-btn">Cancel Sort</a>
</div>
</div>

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
      <a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>">
      <img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt=""></a>
            <div>
            <a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['name']; ?></a>
               <div><a class = "small" href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['email']; ?></div>
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
               <?php if ($fetch_admin['description'] == '') { ?>
      <div class="description">We Are <span><?= $fetch_admin['name']; ?></span></div>
      <?php } elseif (($fetch_admin['description'] != '') ) { ?>
                  <div class="description"><?= $fetch_admin['description']; ?></div>
                  <?php } ?>
                  <a href="centre_location.php?location=<?= $fetch_admin['location']; ?>" class="post-cat"> <i class="fas fa-tag"></i> <span><?= $fetch_admin['location']; ?></span></a>
                  <a href="centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>" class="btn">view detail</a>
               </form>
         <?php }
         } else { ?>
            <p class="empty">no centre yet!</p>
         <?php } ?>

      </div>
   </section>
   <?php include 'footer.php'; ?>
   <script src="../js/script.js"></script>
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