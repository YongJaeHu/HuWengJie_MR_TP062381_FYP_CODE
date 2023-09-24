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
   $delete_image = $conn->prepare("SELECT * FROM `kid` WHERE id = ?");
   $delete_image->execute([$id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if ($fetch_delete_image['image'] != '') {
      unlink('../images/' . $fetch_delete_image['image']);
   }
   $delete_kid = $conn->prepare("DELETE FROM `kid` WHERE id = ?");
   $delete_kid->execute([$id]);
   $delete_comment = $conn->prepare("DELETE FROM `comment` WHERE id = ?");
   $delete_comment->execute([$id]);
   // $delete_like = $conn->prepare("DELETE FROM `like` WHERE id = ?");
   // $delete_like->execute([$id]);
   $affected_rows = $delete_kid->rowCount();
   if ($affected_rows > 0) {
      $success_msg[] = 'kid deleted successfully!';
      header('location:kid_view.php');
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
   <title>Kid view</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
include "../admin/admin_header.php"; 
?>

<section class="ar_view">

<h1 class="heading">kid list</h1>
<?php
$select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
$select_admin->execute([$id]);
$fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
$role = $fetch_admin['role'];    
?>
      <div class="right-align">
         <a href="kid_add.php" class="inline-btn">add kid</a>
      </div>
   <form action="kid_search.php" method="post" class="search">
      <input type="text" name="search_kid" maxlength="100" placeholder="search kid..." required>
      <button type="submit" name="search_kid_btn" class="fas fa-search"></button>
   </form>
<div class="box">
   <div class="flex-btn">
      <a href="kid_cat.php?category=Dog" class="btn">Dog</a>
      <a href="kid_cat.php?category=Cat" class="btn">Cat</a>
      <a href="kid_cat.php?category=Rabbit" class="btn">Rabbit</a>
      <a href="kid_cat.php?category=Hamster" class="btn">Hamster</a>
      <a href="kid_cat.php?category=Birds" class="btn">Birds</a>
   </div>
   <?php if ($role == 1) { ?>
   <div class="flex-btn">
   <a href="kid_location_cat.php" class="option-btn">Sort By location</a>
   <a href="kid_view.php" class="exit-btn">Cancel Sort</a>
</div>
</div>
<?php } ?>
   <div class="box_container">

      <?php
      if(isset($_POST['search_kid']) or isset($_POST['search_kid_btn'])){
         $search_kid = $_POST['search_kid'];
         $search_kid = "%{$search_kid}%";
      $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
      $select_admin->execute([$id]);
      $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
      $role = $fetch_admin['role'];
      if ($role == 0) {
         $kid = $conn->prepare("SELECT * FROM `kid` WHERE name LIKE ? OR id LIKE ? and centre_id = ?");
         $kid->execute([$search_kid, $search_kid, $_SESSION['id']]);
      } else {
         $kid = $conn->prepare("SELECT * FROM `kid` WHERE name LIKE ? OR id LIKE ? ");
         $kid->execute([$search_kid,$search_kid]);
      }
         if ($kid->rowCount() > 0) {
            while ($fetch_kid = $kid->fetch(PDO::FETCH_ASSOC)) {
               $kid_id = $fetch_kid['id'];

               $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
               $select_admin->execute([$fetch_kid['centre_id']]);
               $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
      ?>
      <form method="post" class="box">
      <input type="hidden" name="kid_id" value="<?= $kid_id; ?>">
      <div class="column">
      <a href="../admin/admin_detail.php?admin_id=<?= $fetch_admin['id']; ?>">
      <img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt=""></a>
            <div>
            <a href="../admin/admin_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['name']; ?></a>
               <div><?= $fetch_kid['date']; ?></div>
            </div>
         </div>
         <?php if ($fetch_kid['image'] != '') { ?>
            <a href="kid_detail.php?kid_id=<?= $kid_id; ?>" class="img" alt="">
            <img src="../images/<?= $fetch_kid['image']; ?>" class="article_image" alt=""></a>
         <?php } ?>
         <a href="kid_detail.php?kid_id=<?= $kid_id; ?>" class="img" alt="">      
         <div class="title"><?= $fetch_kid['name']; ?></div></a>
         <div class="price"><i>RM</i><span><?= $fetch_kid['price']; ?></span></div>
         <a href="kid_cat.php?category=<?= $fetch_kid['category']; ?>" class="post-cat"> <i class="fas fa-tag"></i> <span><?= $fetch_kid['category']; ?></span></a>
         <a href="kid_location.php?location=<?= $fetch_kid['location']; ?>" class="post-cat"> <i class="fas fa-tag"></i> <span><?= $fetch_kid['location']; ?></span></a>
         <a href="kid_detail.php?kid_id=<?= $kid_id; ?>" class="btn">view kid</a>
         <div class="flex-btn">
            <a href="kid_edit.php?id=<?= $kid_id; ?>" class="option-btn">edit</a>
            <a href="kid_view.php?id=<?= $kid_id; ?>&delete=1" class="delete-btn" onclick="return delete_msg(event);">delete</a>
         </div>
      </form>
      <?php
            }
         } else{
            echo '<p class="empty">no results found!</p>';
         }
      }else{
         echo '<p class="empty">please search something!</p>';
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