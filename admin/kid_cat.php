<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}   

if(isset($_GET['category'])){
   $category = $_GET['category'];
}else{
   $category = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Article Add</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
include "../admin/admin_header.php"; 
?>


<section class="ar_view">
<?php
   $heading = "All Pet";

   if (!empty($category)) {
      if ($category == "Dog") {
          $heading = "Dog";
      } elseif ($category == "Cat") {
          $heading = "Cat";
      } elseif ($category == "Rabbit") {
          $heading = "Rabbit";
      } elseif ($category == "Hamster") {
          $heading = "Hamster";
      } elseif ($category == "Birds") {
          $heading = "Birds";
      } else {
          // If none of the conditions match, you can set a default heading
          $heading = "Unknown";
      }
  }
   ?>

   <h1 class="heading">Pet category - <?php echo $heading; ?></h1>
   <div class="right-align">
         <a href="kid_add.php" class="inline-btn">add kid</a>
      </div>
   <form action="kid_search.php" method="post" class="search">
      <input type="text" name="search_kid" maxlength="100" placeholder="search kid..." required>
      <button type="submit" name="search_kid_btn" class="fas fa-search"></button>
   </form>
   <?php
$select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
$select_admin->execute([$id]);
$fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
$role = $fetch_admin['role'];    
?>
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
   <?php } ?>
   <a href="kid_view.php" class="exit-btn">Cancel Sort</a>
</div>
</div>

   <div class="box_container">

      <?php
      if ($role == 1) {
         $kid = $conn->prepare("SELECT * FROM `kid` WHERE category = ? and status = 'waiting for owner'");
         $kid->execute([$category]);
      } else{
         $kid = $conn->prepare("SELECT * FROM `kid` WHERE category = ? and status = 'waiting for owner' and centre_id = ?");
         $kid->execute([$category, $id]);
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
         } else {
            echo '<p class="empty">no kid added yet! <a href="kid_add.php" class="btn" style="margin-top:1.5rem;">add kid</a></p>';
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