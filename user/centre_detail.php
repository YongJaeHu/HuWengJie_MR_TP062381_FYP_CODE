<?php  
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if (!isset($id)) {
   header('location: user_login.php');
   exit();
}

if (isset($_GET['admin_id'])) {
   $admin_id = $_GET['admin_id'];
}

if (isset($_GET['delete_comment'])) {
   $comment_id = $_GET['review_id'];
   $comment_id = filter_var($comment_id, FILTER_SANITIZE_STRING);
   $delete_comment = $conn->prepare("DELETE FROM `review` WHERE id = ?");
   $delete_comment->execute([$comment_id]);
   $success_msg[] = 'Review deleted!';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Details</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">

</head>
<body>
   
<?php
include "../user/user_header.php"; 
?>

<section class="detail_container">
<h1 class="heading">Centre Detail</h1>
<div class="box_container">
   <?php
      $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
      $select_admin->execute([$admin_id]);
      if($select_admin->rowCount() > 0){
         while($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)){
            $id = $fetch_admin['id'];

   ?>
   <form method="post" class=box>
   <input type="hidden" name="id" value="<?= $id; ?>">
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
      <div class="details">
     <div class="swiper images-container">
         <div class="swiper-wrapper">
         <?php if(!empty($fetch_admin['image'])){ ?>
            <img src="../images/<?= $fetch_admin['image']; ?>" alt="" class="swiper-slide">
            <?php } ?>
            <?php if(!empty($fetch_admin['image2'])){ ?>
            <img src="../images/<?= $fetch_admin['image2']; ?>" alt="" class="swiper-slide">
            <?php } ?>
            <?php if(!empty($fetch_admin['image3'])){ ?>
            <img src="../images/<?= $fetch_admin['image3']; ?>" alt="" class="swiper-slide">
            <?php } ?>
            <?php if(!empty($fetch_admin['image4'])){ ?>
            <img src="../images/<?= $fetch_admin['image4']; ?>" alt="" class="swiper-slide">
            <?php } ?>
         </div>
         <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
  </div>
     </div>
     <div class="info"> 
         <p><i class="fas fa-user"></i><span><?= $fetch_admin['name']; ?></span></p></a>
         <p><i class="fas fa-map-marker-alt"></i><span><?= $fetch_admin['address']; ?></span></p>
         <p><i class="fa-solid fa-envelope"></i><?= $fetch_admin['email']; ?></a></p>
         <p><i class="fas fa-phone"></i><?= $fetch_admin['phone']; ?></a></p>
         <?php if ($fetch_admin['bank_acc'] != '0') { ?>
         <p><i class="fa-solid fa-piggy-bank"></i><?= $fetch_admin['bank_acc']; ?></a></p>
         <?php } ?>
      </div>
      <?php if ($fetch_admin['description'] != '') { ?>
      <h3 class="title">Who We Are? - <span><?= $fetch_admin['name']; ?></span></h3>
      <p class="content"><?= $fetch_admin['description']; ?></p>
      <?php } ?>
      <div class="flex-btn">
      <a href="contribution_add.php?centre_id=<?= $id; ?>"  class="btn">Contribute</a>
         <a href="contact_add.php?centre_id=<?= $id; ?>" class="btn">Contact</a>
      </div>
      <div class="flex-btn">
      <?php if ($fetch_admin['id'] == $_SESSION['id']) { ?>
      <a href="../admin/admin_updpro.php"  class="option-btn">Update</a>
      <?php } ?>
         <a href="centre_view.php" class="exit-btn">go back</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no post added yet! <a href="add_post.php" class="btn" style="margin-top:1.5rem;">add post</a></p>';
      }
   ?>

</section>

<section class="ar_view">

<h1 class="heading">kid list</h1>

   <div class="box_container">
      <?php
      $kid = $conn->prepare("SELECT * FROM `kid` where status = 'waiting for owner' ORDER BY date DESC");
      $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
      $select_admin->execute([$id]);
      $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

         $kid = $conn->prepare("SELECT * FROM `kid` WHERE centre_id = ? AND status = 'waiting for owner' LIMIT 3");
         $kid->execute([$id]);
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
      </form>
      <?php
            }
         } else {
            echo '<p class="empty">no kid added yet! <a href="kid_add.php" class="btn" style="margin-top:1.5rem;">add kid</a></p>';
         }
      ?>
   </div>
   <div class="more-btn" style="text-align: center; margin-top:1rem;">
      <a href="kid_centre.php?centre_id=<?= $id; ?>"  class="inline-btn">view More</a>
   </div>
</section>

<section class="rating">

<h1 id="add-comment" class="heading">Rating</h1>

   <?php
      $select_post = $conn->prepare("SELECT * FROM `admin` WHERE id = ? LIMIT 1");
      $select_post->execute([$admin_id]);
      if($select_post->rowCount() > 0){
         while($fetch_post = $select_post->fetch(PDO::FETCH_ASSOC)){

        $total_ratings = 0;
        $rating_1 = 0;
        $rating_2 = 0;
        $rating_3 = 0;
        $rating_4 = 0;
        $rating_5 = 0;

        $select_ratings = $conn->prepare("SELECT * FROM `review` WHERE centre_id = ?");
        $select_ratings->execute([$fetch_post['id']]);
        $total_reivews = $select_ratings->rowCount();
        while($fetch_rating = $select_ratings->fetch(PDO::FETCH_ASSOC)){
            $total_ratings += $fetch_rating['rating'];
            if($fetch_rating['rating'] == 1){
               $rating_1 += $fetch_rating['rating'];
            }
            if($fetch_rating['rating'] == 2){
               $rating_2 += $fetch_rating['rating'];
            }
            if($fetch_rating['rating'] == 3){
               $rating_3 += $fetch_rating['rating'];
            }
            if($fetch_rating['rating'] == 4){
               $rating_4 += $fetch_rating['rating'];
            }
            if($fetch_rating['rating'] == 5){
               $rating_5 += $fetch_rating['rating'];
            }
        }

        if($total_reivews != 0){
            $average = round($total_ratings / $total_reivews, 1);
        }else{
            $average = 0;
        }
        
   ?>
   <div class="row">
      <div class="col">
         <div class="flex">
            <div class="review">
               <h3><?= $average; ?><i class="fas fa-star"></i></h3>
               <p><?= $total_reivews; ?> reviews</p>
            </div>
            <div class="rating">
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_5; ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_4; ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_3; ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_2; ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_1; ?></span>
               </p>
            </div>
         </div>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">post is missing!</p>';
      }
   ?>

</section>

<section class="comment">
   <h1 id="add-comment" class="heading">User Review</h1>
   <div class="right-align">
      <a href="review_add.php?admin_id=<?= $admin_id;?>" class="inline-btn" style="margin-bottom:.5rem;">add review</a>
   </div>
   </div>
   <div class="comm_dis">
   <?php
      $select_reviews = $conn->prepare("SELECT * FROM `review` WHERE centre_id = ?");
      $select_reviews->execute([$admin_id]);
      if($select_reviews->rowCount() > 0){
         while($fetch_review = $select_reviews->fetch(PDO::FETCH_ASSOC)){
   ?>
         <?php
            $select_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
            $select_user->execute([$fetch_review['user_id']]);
            while($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
         ?>
         <div class="box">
            <div class="user">
            <?php if($fetch_user['image'] != ''){ ?>
                  <a href="../user/user_detail.php?user_id=<?= $fetch_user['id']; ?>">
                  <img src="../images/<?= $fetch_user['image']; ?>" alt=""></a>  
               <div>
                  <h3>
                  <a href="../user/user_detail.php?user_id=<?= $fetch_user['id']; ?>"><?= $fetch_user['name']; ?></a></h3>
                  <span><?= $fetch_review['date']; ?></span>
               </div>
               <?php }; ?> 
            </div>
            <div class="rating">
               <?php if($fetch_review['rating'] == 1){ ?>
                  <p style="background:var(--red);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
               <?php }; ?> 
               <?php if($fetch_review['rating'] == 2){ ?>
                  <p style="background:var(--main-color);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
               <?php }; ?>
               <?php if($fetch_review['rating'] == 3){ ?>
                  <p style="background:var(--main-color);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
               <?php }; ?>   
               <?php if($fetch_review['rating'] == 4){ ?>
                  <p style="background:green;"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
               <?php }; ?>
               <?php if($fetch_review['rating'] == 5){ ?>
                  <p style="background:green;"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
               <?php }; ?>
            </div>
            <h3><p class="title"><?= $fetch_review['title']; ?></p></h3>
            <?php if($fetch_review['description'] != ''){ ?>
               <p class="text"><?= $fetch_review['description']; ?></p>
            <?php }; ?> 
            <?php if ($fetch_review['user_id'] == $_SESSION['id']) { ?>
            <form action="" method="post" class="flex-btn">
               <input type="hidden" name="review_id" value="<?= $fetch_review['id']; ?>">
               <a href="centre_detail.php?admin_id=<?= $admin_id; ?>&delete_comment=true&review_id=<?= $fetch_review['id']; ?>" class="inline-delete-btn" onclick="delete_msg(event);">delete review</a>
            </form>
         <?php } ?>     
         </div>
      <?php
            }
         }
      }else{
         echo '<p class="empty">no reviews added yet!</p>';
      }
   ?>
   </div>
</section>
<?php include 'footer.php'; ?>
<script src="../js/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<?php include '../message.php'; ?>
<script>
var swiper = new Swiper(".images-container", {
   pagination: {
        el: ".swiper-pagination",
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
    
    function delete_msg(ev) {
    ev.preventDefault();
    var urlToRedirect = ev.currentTarget.getAttribute('href');  
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
