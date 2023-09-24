<?php
include '../connect.php';

session_start();
$id = $_SESSION['id'];  


if(isset($_POST['submit'])){
   $order_id = uni_id();
   $kid_id = $_GET['kid_id'];
   $select_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
   $select_user->execute([$id]);
   $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
   $phone = $_POST['phone'];
   $phone = filter_var($phone, FILTER_SANITIZE_SPECIAL_CHARS);
   
   $user_id = $_POST['user_id'];
   $user_id = filter_var($user_id, FILTER_SANITIZE_SPECIAL_CHARS);

   $kid_id = $_POST['kid_id'];
   $kid_id = filter_var($kid_id, FILTER_SANITIZE_SPECIAL_CHARS);

   $centre_id = $_POST['centre_id'];
   $centre_id = filter_var($centre_id, FILTER_SANITIZE_SPECIAL_CHARS);

   $datetime = $_POST['datetime'];
   $datetime = filter_var($datetime, FILTER_SANITIZE_SPECIAL_CHARS);

   $status = 'waiting for approval from centre';

   $insert_order = $conn->prepare("INSERT INTO `order`(id, user_id, kid_id, centre_id, appoint_date,status) VALUES(?,?,?,?,?,?)");
   $insert_order->execute([$order_id, $user_id, $kid_id, $centre_id,$datetime, $status]);
   $success_msg[] = 'Order Applied Successfully!';
   $_SESSION['success_msg'] = $success_msg;

   $update_kid = $conn->prepare("UPDATE `kid` SET status = ? WHERE id = ?");
   $update_kid->execute([$status, $kid_id]);

   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `user` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $id]);
   }

   if(!empty($phone)){
      $update_phone = $conn->prepare("UPDATE `user` SET phone = ? WHERE id = ?");
      $update_phone->execute([$phone, $id]);
   }

   header('location:order_view.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Place Order</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">

</head>

<?php
   include "../user/user_header.php";
   $kid_id = $_GET['kid_id'];
   $select_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
   $select_user->execute([$id]);
   $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

   $select_kid = $conn->prepare("SELECT * FROM `kid` WHERE id = ?");
   $select_kid->execute([$kid_id]);
   $fetch_kid = $select_kid->fetch(PDO::FETCH_ASSOC);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
$select_admin->execute([$fetch_kid['centre_id']]);
$fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

   ?>
<body>

<section class="norm_container">
<form class="register" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="user_id" value="<?= $fetch_user['id']; ?>">
<input type="hidden" name="kid_id" value="<?= $fetch_kid['id']; ?>">
<input type="hidden" name="centre_id" value="<?= $fetch_kid['centre_id']; ?>">
   <h3>Order Detail</h3>
   <img src="../images/<?= $fetch_kid['image']; ?>" class="image" alt="">
   <p><i>Pet Name: </i><?= $fetch_kid['name']; ?></p>
   <p><i>Centre Name: </i><?= $fetch_admin['name']; ?></p>    
   <p><i>Price: RM</i><?= $fetch_kid['price']; ?></p>
   <div class="flex">
      <div class="col">
         <p>Name</p>
         <input type="text" name="name" placeholder="<?= $fetch_user['name']; ?>" maxlength="255" class="box">
      </div>
      <div class="col">
         <p>Phone number</p>
         <input type="text" name="phone" placeholder="<?= $fetch_user['phone']; ?>" maxlength="255" class="box">
      </div>
   </div>

   <!-- Datetime Input -->
   <div class="col">
      <p>Choose Home Visit / Interview date and time<span>*</span></p>
      <input type="datetime-local" name="datetime" required class="box" id="datetime-input">
   </div>
   <p><span>*</span><i>Note: Payment will be received after the home visit / interview</i></p>
   <input type="submit" name="submit" value="Place Order now" class="btn">
   <a href="kid_detail.php?kid_id=<?= $kid_id;?>" class="exit-btn">go back</a>
</form>
</section>
<?php include 'footer.php'; ?>
<script src="../js/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>