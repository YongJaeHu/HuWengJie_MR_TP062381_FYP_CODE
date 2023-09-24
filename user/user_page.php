<?php  
include "../connect.php"; 

session_start();
if(isset($_SESSION['id'])){
  $id = $_SESSION['id'];
}else{
  $id = '';
};

?>
<?php
include "../user/user_header.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Centre Details</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">

</head>
<body>
<section class="home">
   <div class="content">
      <h3>Pet Adoption and Rescue Centre Management System</h3>
      <p>Shine Them a Bright Future.</p>
      <?php if ($_SESSION['id'] != '') { ?>
      <a href="../user/kid_view.php" class="option-btn">Start</a>
      <?php } else {
   ?>
   <a href="../user/user_login.php" class="option-btn">Start</a>
   <?php
}
      ?>
   </div>
</section>

<section class="ar_view">

<h1 class="heading">Latest Pets</h1>

   <div class="box_container">
      <?php
      $kid = $conn->prepare("SELECT * FROM `kid` where status = 'waiting for owner' ORDER BY date DESC LIMIT 3");
         $kid->execute();
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
            echo '<p class="empty">no kid added yet!</p>';
         }
      ?>
   </div>
   <div class="more-btn" style="text-align: center; margin-top:1rem;">
      <a href="kid_view.php"  class="inline-btn">view More Pet</a>
   </div>
</section>

<section class="ar_view">

<h1 class="heading">Centre List</h1>
<div class="box_container">

   <?php
   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE approval = ? LIMIT 3");
   $select_admin->execute(['1']);
   if ($select_admin->rowCount() > 0) {
      while ($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)) {
         $admin_id = $fetch_admin['id'];
   ?>
         <form method="post" action="approval_view.php" class="box">
         <input type="hidden" name="id" value="<?= $fetch_admin['id']; ?>">
         <div class="column">
<a href="centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>">
<img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt=""></a>
      <div>
      <a href="centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['name']; ?></a>
         <div><a class = "small" href="centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['email']; ?></div>
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
            <div class="description"><?= $fetch_admin['description']; ?></div>
            <a href="centre_location.php?location=<?= $fetch_admin['location']; ?>" class="post-cat"> <i class="fas fa-tag"></i> <span><?= $fetch_admin['location']; ?></span></a>
            <a href="centre_detail.php?admin_id=<?= $fetch_admin['id']; ?>" class="btn">view detail</a>
         </form>
   <?php }
   } else { ?>
      <p class="empty">no centre yet!</p>
   <?php } ?>
</div>
<div class="more-btn" style="text-align: center; margin-top:1rem;">
      <a href="centre_view.php"  class="inline-btn">view More Centre</a>
   </div>
</section>

<section class="faq" id="faq">
   <h1 class="heading">FAQ</h1>
   <div class="box_container">
      <div class="box">
         <h3><span>Who We Are?</span><i class="fas fa-angle-down"></i></h3>
         <p>We are an online adoption platform which aims to serve as a primary animal adoption platfrom in Malaysia which allows all centres in Malaysia to promote their services.</p>
      </div>
      <div class="box">
         <h3><span>Can we be trusted?</span><i class="fas fa-angle-down"></i></h3>
         <p>Yes. We will check the authentication and license of all the registered centres. If facing any problem, you may contact the related centre and our admin too. We are pleased to help you</p>
      </div>
      <div class="box">
         <h3><span>Free to register?</span><i class="fas fa-angle-down"></i></h3>
         <p>Yes. We are free to register. Upon registeration, you can start adopting the pet and post it in our Petagram feature.</p>
      </div>
      <div class="box">
         <h3><span>What does article feature mean?</span><i class="fas fa-angle-down"></i></h3>
         <p>Our registered centres and admin will post the articles related to professional tips in pet caring. You may learn from those articles.</p>
      </div>
      <div class="box">
         <h3><span>What is Petagram?</span><i class="fas fa-angle-down"></i></h3>
         <p>Petagram is a feature which allows to post your life with your pet. Feel free to post it.</p>
      </div>
      <div class="box">
         <h3><span>What is the function of Report feature?</span><i class="fas fa-angle-down"></i></h3>
         <p>Report feature allows you to report any abandomed animal or any withnessed animal abuse. You may use report feature if you require help in pet caring.</p>
      </div>
   </div>
</section>
<?php include 'footer.php'; ?>
<script src="../js/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>
