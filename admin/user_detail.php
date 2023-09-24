<?php
include "../connect.php";

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}

if (isset($_GET['user_id'])) {
   $user_id = $_GET['user_id'];
} else {
   $user_id = $id;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User detail</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>

   <?php
   include "../admin/admin_header.php";
   ?>

   <section class="ar_view">

      <h1 class="heading">User detail</h1>
      <div class="box_container">

         <?php
         $select_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
         $select_user->execute([$user_id ]);
         if ($select_user->rowCount() > 0) {
            while ($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)) {
               $user_id = $fetch_user['id'];
         ?>
               <form method="post" action="approval_view.php" class="box">
               <input type="hidden" name="id" value="<?= $fetch_user['id']; ?>">
               <div class="column">
      <a href="../user/user_detail.php?user_id=<?= $fetch_user['id']; ?>">
      <img src="../images/<?= $fetch_user['image']; ?>" class="image" alt=""></a>
            <div>
            <a href="../user/user_detail.php?user_id=<?= $fetch_user['id']; ?>"><?= $fetch_user['name']; ?></a>
               <div><a class = "small" href="../user/user_detail.php?user_id=<?= $fetch_user['id']; ?>"><?= $fetch_user['email']; ?></div>
            </div>
         </div>
                  <?php if ($fetch_user['image'] != '') { ?>
                     <img src="../images/<?= $fetch_user['image']; ?>" class="article_image" alt="">
                  <?php } ?>
                  <div class="title"><i>Name: </i><?= $fetch_user['name']; ?></div></a>      
         <div class="title"><i>Email: </i><?= $fetch_user['email']; ?></div></a>
         <div class="title"><i>Phone: </i><?= $fetch_user['phone']; ?></div>
         <?php if ($fetch_user['id'] == $_SESSION['id']) { ?>
                  <a href="user_updpro.php?user_id=<?= $fetch_user['id']; ?>" class="btn">Update Profile</a>
                  <a href="user_repass.php?>" class="option-btn" onclick="return update_msg(event);">Reset Password</a>
                  <?php } else{ ?>
                  <a href="user_contact_add.php?user_id=<?= $fetch_user['id']; ?>" class="btn">Contact</a>
                  <?php } ?>
               </form>
         <?php }
         } else { ?>
            <p class="empty">not found user!</p>
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
        title: "Are you sure to Reset passowrd?",
        text: "You will be logout.",
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