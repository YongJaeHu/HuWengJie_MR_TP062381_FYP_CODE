<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}   

if(isset($_POST['save'])){
   $order_id = $_GET['id'];
   $appoint_date = $_POST["datetime"];
   $appoint_date = filter_var($appoint_date, FILTER_SANITIZE_SPECIAL_CHARS);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_SPECIAL_CHARS);

   if(!empty($order_id)){
      $update_order= $conn->prepare("UPDATE `order` SET appoint_date = ?, status = ? WHERE id = ?");
      $update_order->execute([$appoint_date, $status, $order_id]);
   $success_msg[] = 'Order Info Updated successfully!';
   }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>kid</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
include "../admin/admin_header.php"; 
?>

<section class="ar_edit">

   <h1 class="heading">edit order</h1>

   <?php
      $order_id = $_GET['id'];
      $select_order= $conn->prepare("SELECT * FROM `order` WHERE id = ?");
      $select_order->execute([$order_id]);
      if($select_order->rowCount() > 0){
         while($fetch_order= $select_order->fetch(PDO::FETCH_ASSOC)){
            $select_kid = $conn->prepare("SELECT * FROM `kid` WHERE id = ?");
            $select_kid->execute([$fetch_order['kid_id']]);
            $fetch_kid = $select_kid->fetch(PDO::FETCH_ASSOC);

            $select_admin = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
            $select_admin->execute([$fetch_order['user_id']]);
            $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
   <img src="../images/<?= $fetch_kid['image']; ?>" class="image" alt="">
   <p><i>Order ID: </i><?= $fetch_order['id']; ?></p>
   <p><i>Customer Name: </i><?= $fetch_admin['name']; ?></p>    
   <p><i>Pet Name: </i><?= $fetch_kid['name']; ?></p>
   <p><i>Price: RM</i><?= $fetch_kid['price']; ?></p>
   <p>Status <span>*</span></p>
      <select name="status" class="box" required >
         <option value="<?= $fetch_order['status']; ?>" selected><?= $fetch_order['status']; ?></option>
         <option value="waiting for approval from centre">waiting for approval from centre</option>
         <option value="waiting for home visit/interview">waiting for home visit/interview</option>
         <option value="finish">finish</option>
      </select>
      <input type="hidden" name="id" value="<?= $fetch_order['id']; ?>">
      <div class="col">
      <p>Choose Home Visit / Interview date and time<span>*</span></p>
      <input type="datetime-local" name="datetime" required class="box" id="datetime-input" value="<?= $fetch_order['appoint_date']; ?>">
      <p><span>*</span><i>Note: Please inform the customer before changing the date</i></p>
      <input type="submit" value="save" name="save" class="btn">
      <a href="order_view.php" class="exit-btn">go back</a>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no order found!</p>';
   ?>
   <?php
      }
   ?>

</section>
<script src="../js/admin_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>