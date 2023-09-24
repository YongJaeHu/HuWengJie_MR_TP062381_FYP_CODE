<?php
include "../connect.php"; 

session_start();
if(isset($_SESSION['id'])){
    $id = $_SESSION['id'];
  }else{
    $id = '';
  };   

if(isset($_GET['location'])){
   $location = $_GET['location'];
}else{
   $location = '';
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
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include "../user/user_header.php"; 
?>


<section class="ar_view">
<?php
   $heading = "All Centre";

   if (!empty($location)) {
      if ($location == "Johor") {
          $heading = "Johor";
      } elseif ($location == "Kedah") {
          $heading = "Kedah";
      } elseif ($location == "Kelantan") {
          $heading = "Kelantan";
      } elseif ($location == "Melaka") {
          $heading = "Melaka";
      } elseif ($location == "Negeri Sembilan") {
          $heading = "Negeri Sembilan";
      } elseif ($location == "Pahang") {
          $heading = "Pahang";
      } elseif ($location == "Perak") {
          $heading = "Perak";
      } elseif ($location == "Perlis") {
          $heading = "Perlis";
      } elseif ($location == "Pulau Pinang (Penang)") {
          $heading = "Pulau Pinang (Penang)";
      } elseif ($location == "Sabah") {
          $heading = "Sabah";
      } elseif ($location == "Sarawak") {
          $heading = "Sarawak";
      } elseif ($location == "Selangor") {
          $heading = "Selangor";
      } elseif ($location == "Terengganu") {
          $heading = "Terengganu";
      } elseif ($location == "Kuala Lumpur") {
          $heading = "Kuala Lumpur";
      } elseif ($location == "Labuan") {
          $heading = "Labuan";
      } elseif ($location == "Putrajaya") {
          $heading = "Putrajaya";
      } else {
          // If none of the conditions match, you can set a default heading
          $heading = "Unknown Location";
      }
  }
   ?>

   <h1 class="heading">Centre location - <?php echo $heading; ?></h1>

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
         $centre = $conn->prepare("SELECT * FROM `admin` WHERE location = ? and approval = 1");
         $centre->execute([$location]);
         if ($centre->rowCount() > 0) {
            while ($fetch_centre = $centre->fetch(PDO::FETCH_ASSOC)) {
               $centre_id = $fetch_centre['id'];

               $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
               $select_admin->execute([$fetch_centre['id']]);
               $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
      ?>
      <form method="post" class="box">
      <input type="hidden" name="centre_id" value="<?= $centre_id; ?>">
      <div class="column">
      <a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>">
      <img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt=""></a>
            <div>
            <a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['name']; ?></a>
            <div><a class = "small" href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['email']; ?></div>
            </div>
         </div>
         <?php if ($fetch_centre['image'] != '') { ?>
            <a href="centre_detail.php?admin_id=<?= $centre_id; ?>" class="img" alt="">
            <img src="../images/<?= $fetch_centre['image']; ?>" class="article_image" alt=""></a>
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
         <a href="centre_detail.php?admin_id=<?= $centre_id; ?>" class="img" alt="">      
         <div class="title"><?= $fetch_centre['name']; ?></div></a>
         <a href="centre_location.php?location=<?= $fetch_centre['location']; ?>" class="post-cat"> <i class="fas fa-tag"></i> <span><?= $fetch_centre['location']; ?></span></a>
         <a href="centre_detail.php?admin_id=<?= $centre_id; ?>" class="btn">view centre</a>
      </form>
      <?php
            }
         } else {
            echo '<p class="empty">no centre yet!</p>';
         }
      ?>

   </div>
</section>
<?php include 'footer.php'; ?>
<script src="../js/script.js"></script>
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