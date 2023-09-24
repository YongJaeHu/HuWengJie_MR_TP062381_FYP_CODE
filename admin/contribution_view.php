<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}    

// if (isset($_GET['delete'])) {
//    $contribute_id = $_GET['id'];
//    $contribute_id = filter_var($contribute_id, FILTER_SANITIZE_SPECIAL_CHARS);
   
//    // Corrected variable name here
//    $delete_image = $conn->prepare("SELECT * FROM `contribute` WHERE id = ?");
//    $delete_image->execute([$contribute_id]);
//    $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   
//    if ($fetch_delete_image['image'] != '') {
//       unlink('../images/' . $fetch_delete_image['image']);
//    }
//    $delete_contribute= $conn->prepare("DELETE FROM `contribute` WHERE id = ?");
//    $delete_contribute->execute([$contribute_id]);

//    $affected_rows = $delete_contribute->rowCount();
//    if ($affected_rows > 0) {
//       $success_msg[] = 'Message deleted successfully!';
//       // header('location:contact_view.php');
//       // exit();
//    } else {
//       $error_msg[] = 'Failed to delete the message.';
//    }
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contribution view</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
include "../admin/admin_header.php"; 
?>

<section class="ar_view">

<h1 class="heading">Contribution list</h1>
<form action="contribution_search.php" method="post" class="search">
      <input type="text" name="search_contribution" maxlength="100" placeholder="search contribution..." required>
      <button type="submit" name="search_contribution_btn" class="fas fa-search"></button>
   </form>
   <div class="box_container">

      <?php
         $contribute = $conn->prepare("SELECT * FROM `contribute` WHERE centre_id = ?");
         $contribute->execute([$id]);
         if ($contribute->rowCount() > 0) {
            while ($fetch_contribute= $contribute->fetch(PDO::FETCH_ASSOC)) {
               $contribute_id = $fetch_contribute['id'];

               $select_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
               $select_user->execute([$fetch_contribute['sender_id']]);
               $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

               $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
               $select_admin->execute([$fetch_contribute['sender_id']]);
               $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

      ?>
      <form method="post" class="box">
      <input type="hidden" name="contribute_id" value="<?= $contribute_id; ?>">
      <div class="column">
      <?php if ($fetch_admin && $fetch_admin !== false) { ?>
      <a href="../admin/admin_detail.php?admin_id=<?= $fetch_admin['id']; ?>">
      <img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt=""></a>
            <div>
            <a href="../admin/admin_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['name']; ?></a>
               <div><?= $fetch_contribute['date']; ?></div>
            </div>
            <?php } elseif ($fetch_user && $fetch_user !== false) { ?>
               <a href="user_detail.php?user_id=<?= $fetch_user['id']; ?>">
               <img src="../images/<?= $fetch_user['image']; ?>" class="image" alt=""></a>
               <div>
                  <a href="user_detail.php?user_id=<?= $fetch_user['id']; ?>"><?= $fetch_user['name']; ?></a>
                  <div><?= $fetch_contribute['date']; ?></div>
               </div>
            <?php } ?>
         </div>
            <img src="../images/<?= $fetch_contribute['image']; ?>" class="article_image" alt="" onclick="displayImage(this)">
            <p><i>Reminder: Click on image to enlarge</i></p>
         <div class="title"><i>ID: </i><?= $fetch_contribute['id']; ?></div></a>      
         <div class="title"><i>Title: </i><?= $fetch_contribute['title']; ?></div></a>
         <div class="title"><i>Description: </i><?= $fetch_contribute['description']; ?></div>
         <!-- <div class="flex-btn">
         <a href="contribution_view.php?id=<?= $contribute_id; ?>&delete=1" class="delete-btn" onclick="return delete_msg(event);">delete</a>
         </div> -->
      </form>
      <?php
            }
         } else {
            echo '<p class="empty">no contribution yet!</p>';
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
    function displayImage(imgElement) {
    var imageUrl = imgElement.src;
    var overlay = document.createElement("div");
    overlay.classList.add("image-overlay");
    
    var img = document.createElement("img");
    img.src = imageUrl;
    img.classList.add("enlarged-image");

    overlay.appendChild(img);
    document.body.appendChild(overlay);

    overlay.onclick = function() {
      document.body.removeChild(overlay);
    };
  }
</script>
<style>
  .image-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }

  .enlarged-image {
    max-width: 90%;
    max-height: 90%;
  }
</style>
</body>
</html>