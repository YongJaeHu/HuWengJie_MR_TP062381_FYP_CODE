<?php
include "../connect.php";

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}   
$email = $_SESSION['email'];
// $name = $_SESSION('name');

$article = $conn->prepare("SELECT * FROM `article` WHERE admin_id = ?");
$article->execute([$id]);
$total_article = $article->rowCount();

$kid = $conn->prepare("SELECT * FROM `kid` WHERE centre_id = ?");
$kid->execute([$id]);
$total_kid = $kid->rowCount();

$order = $conn->prepare("SELECT * FROM `order` WHERE centre_id = ?");
$order->execute([$id]);
$total_order = $order->rowCount();

$order_centre = $conn->prepare("SELECT * FROM `order` WHERE centre_id = ? and status = 'waiting for approval from centre'");
$order_centre->execute([$id]);
$total_order_centre = $order_centre->rowCount();

$order_int = $conn->prepare("SELECT * FROM `order` WHERE centre_id = ? and status = 'waiting for home visit/interview'");
$order_int->execute([$id]);
$total_order_int = $order_int->rowCount();

$event = $conn->prepare("SELECT * FROM `event` WHERE admin_id = ?");
$event->execute([$id]);
$total_event = $event->rowCount();

$post = $conn->prepare("SELECT * FROM `post` WHERE author_id = ?");
$post->execute([$id]);
$total_post = $post->rowCount();

$report = $conn->prepare("SELECT * FROM `report` WHERE centre_id = ?");
$report->execute([$id]);
$total_report = $report->rowCount();

$contribute = $conn->prepare("SELECT * FROM `contribute` WHERE centre_id = ?");
$contribute->execute([$id]);
$total_contribute = $contribute->rowCount();

$contact = $conn->prepare("SELECT * FROM `contact` WHERE receiver_id = ?");
$contact->execute([$id]);
$total_contact = $contact->rowCount();

$new_contact = $conn->prepare("SELECT * FROM `contact` WHERE receiver_id = ? and status = 'unreply'");
$new_contact->execute([$id]);
$total_new_contact = $new_contact->rowCount();

$select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
$select_profile->execute([$id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
$role = $fetch_profile['role'];

$centre = $conn->prepare("SELECT * FROM `admin` WHERE role = '0' and approval = '1'");
$centre->execute();
$total_centre = $centre->rowCount();

$approval = $conn->prepare("SELECT * FROM `admin` WHERE approval = '0'");
$approval->execute();
$total_approval = $approval->rowCount();

$admin = $conn->prepare("SELECT * FROM `admin` WHERE role = '1'");
$admin->execute();
$total_admin = $admin->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Page</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
<?php
include "../admin/admin_header.php"; 
?>

<section class="main">
<h1 class="heading">Task</h1>
<div class="box_container">
      <div class="box">
         <span><?= $total_order_centre; ?></span>
         <p>total orders (waiting for approval from centre)</p>
         <a href="order_status.php?status=waiting for approval from centre" class="btn">view order</a>
      </div>
      <div class="box">
         <span><?= $total_order_int; ?></span>
         <p>total orders (waiting for home visit / interview)</p>
         <a href="order_status.php?status=waiting for home visit/interview" class="btn">view order</a>
      </div>
      <div class="box">
         <span><?= $total_new_contact; ?></span>
         <p>New contact</p>
         <a href="contact_view.php" class="btn">view contact</a>
      </div>
      <?php if ($role == 1) { ?>
         <div class="box">
         <span><?= $total_approval; ?></span>
         <p>total approval request from centre</p>
         <a href="approval_view.php" class="btn">view approval</a>
      </div>
         <?php } ?>
   </div>
</div>
<h1 class="heading">Profile</h1>
<div class="box_container">
      <div class="box">
         <span><?= $total_kid; ?></span>
         <p>total kids</p>
         <a href="kid_view.php" class="btn">view kids</a>
      </div>
      <div class="box">
         <span><?= $total_order; ?></span>
         <p>total orders</p>
         <a href="order_view.php" class="btn">view order</a>
      </div>
      <div class="box">
         <span><?= $total_article; ?></span>
         <p>total articles</p>
         <a href="article_view.php" class="btn">view article</a>
      </div>
      <div class="box">
         <span><?= $total_event; ?></span>
         <p>total event</p>
         <a href="event_view.php" class="btn">view event</a>
      </div>
      <div class="box">
         <span><?= $total_contribute; ?></span>
         <p>total contribution</p>
         <a href="contribute_view.php" class="btn">view contribution</a>
      </div>
      <div class="box">
         <span><?= $total_report; ?></span>
         <p>total report</p>
         <a href="report_view.php" class="btn">view report</a>
      </div>
      <div class="box">
         <span><?= $total_contact; ?></span>
         <p>total contact</p>
         <a href="contact_view.php" class="btn">view contact</a>
      </div>
      <?php if ($role == 1) { ?>
         <div class="box">
         <span><?= $total_centre; ?></span>
         <p>total centre</p>
         <a href="centre_view.php" class="btn">view centre</a>
      </div>
      <div class="box">
         <span><?= $total_admin; ?></span>
         <p>total admin</p>
         <a href="centre_view.php" class="btn">view admin</a>
      </div>
         <?php } ?>
   </div>
</div>
<script src="../js/admin_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>