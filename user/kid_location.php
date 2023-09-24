<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: user_login.php');
   exit();
}   

if(isset($_GET['location'])){
   $location = $_GET['location'];
}else{
   $location = '';
}

if (isset($_POST['like_post'])) {
    // $article_id = $_POST['post_id'];
    // $article_id = filter_var($article_id, FILTER_SANITIZE_SPECIAL_CHARS);
    
    // if ($kid_id != '') {
       $kid_id= $_POST['kid_id'];
       $kid_id= filter_var($kid_id, FILTER_SANITIZE_SPECIAL_CHARS);
       // $id = $_POST['id'];
       // $id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);
 
       $select_like = $conn->prepare("SELECT * FROM `save` WHERE kid_id = ? AND user_id = ?");
       $select_like->execute([$kid_id, $id]);
 
       if ($select_like->rowCount() > 0) {
          $remove_like = $conn->prepare("DELETE FROM `save` WHERE kid_id = ? AND user_id = ?");
          $remove_like->execute([$kid_id, $id]);
          $warning_msg[] = 'removed from save';
       } else {
          $add_like = $conn->prepare("INSERT INTO `save`(kid_id, user_id) VALUES(?,?)");
          $add_like->execute([ $kid_id, $id]);
          $success_msg[] = 'saved';
       }
    } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Kid</title>
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
   $heading = "All Article";

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

   <h1 class="heading">Pet location - <?php echo $heading; ?></h1>
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
   <div class="flex-btn">
   <a href="kid_location_cat.php" class="option-btn">Sort By location</a>
   <a href="kid_view.php" class="exit-btn">Cancel Sort</a>
</div>
</div>


   <div class="box_container">

      <?php
         $kid = $conn->prepare("SELECT * FROM `kid` WHERE location = ?");
         $kid->execute([$location]);
         if ($kid->rowCount() > 0) {
            while ($fetch_kid = $kid->fetch(PDO::FETCH_ASSOC)) {
               $kid_id = $fetch_kid['id'];

               $confirm_like = $conn->prepare("SELECT * FROM `save` WHERE user_id = ? AND kid_id = ?");
               $confirm_like->execute([$id, $kid_id]);

               $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
               $select_admin->execute([$fetch_kid['centre_id']]);
               $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
      ?>
      <form method="post" class="box">
      <input type="hidden" name="kid_id" value="<?= $kid_id; ?>">
      <div class="column">
      <a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>">
      <img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt=""></a>
            <div>
            <a href="../user/centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['name']; ?></a>
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
         <button type="submit" name="like_post" class="option-btn" style="<?php if($confirm_like->rowCount() > 0) { echo 'color:var(--main-color);'; } ?>">
        <i class="fa-solid fa-bookmark"></i> Save
    </button>
         <!-- <div class="flex-btn">
            <a href="kid_edit.php?id=<?= $kid_id; ?>" class="option-btn">edit</a>
            <a href="kid_view.php?id=<?= $kid_id; ?>&delete=1" class="delete-btn" onclick="return delete_msg(event);">delete</a>
         </div> -->
      </form>
      <?php
            }
         } else {
            echo '<p class="empty">no kid added yet!</p>';
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