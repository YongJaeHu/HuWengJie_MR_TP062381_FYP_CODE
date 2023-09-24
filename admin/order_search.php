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
   $delete_image = $conn->prepare("SELECT * FROM `order` WHERE id = ?");
   $delete_image->execute([$id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if ($fetch_delete_image['image'] != '') {
      unlink('../images/' . $fetch_delete_image['image']);
   }
   $delete_order= $conn->prepare("DELETE FROM `order` WHERE id = ?");
   $delete_order->execute([$id]);

   $status = 'waiting for owner';

   $update_kid = $update_kid = $conn->prepare("UPDATE `kid` SET status = ? WHERE id = ?");
   $update_kid->execute([$status, $fetch_delete_image['kid_id']]);

   $affected_rows = $delete_order->rowCount();
   if ($affected_rows > 0) {
      $success_msg[] = 'order deleted successfully!';
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
   <title>Order view</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
include "../admin/admin_header.php"; 
?>

<section class="ar_view">

<h1 class="heading">Order list</h1>
<form action="order_search.php" method="post" class="search">
      <input type="text" name="search_order" maxlength="100" placeholder="search order..." required>
      <button type="submit" name="search_order_btn" class="fas fa-search"></button>
   </form>
<div class="box">
   <div class="flex-btn">
      <a href="order_status.php?status=waiting for approval from centre" class="btn">Waiting for Approval from centre</a>
      <a href="order_status.php?status=waiting for home visit/interview" class="btn">Waiting for home visit/interview</a>
      <a href="order_status.php?status=finish" class="btn">Finish</a>
      <a href="order_view.php" class="btn">View all</a>
   </div>
</div>
   <div class="box_container">

      <?php
            if(isset($_POST['search_order']) or isset($_POST['search_order_btn'])){
               $search_order = $_POST['search_order'];
               $search_order = "%{$search_order}%";
               $order = $conn->prepare("SELECT * FROM `order` WHERE id LIKE ? and centre_id = ?");
               $order->execute([$search_order, $id]);
         // $order= $conn->prepare("SELECT * FROM `order` WHERE centre_id = ?");
         // $order->execute([$id]);
         if ($order->rowCount() > 0) {
            while ($fetch_order= $order->fetch(PDO::FETCH_ASSOC)) {
               $order_id = $fetch_order['id'];

               $select_admin = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
               $select_admin->execute([$fetch_order['user_id']]);
               $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

               $select_kid = $conn->prepare("SELECT * FROM `kid` WHERE id = ?");
               $select_kid->execute([$fetch_order['kid_id']]);
               $fetch_kid = $select_kid->fetch(PDO::FETCH_ASSOC);
      ?>
      <form method="post" class="box">
      <input type="hidden" name="order_id" value="<?= $order_id; ?>">
      <div class="column">
      <a href="../user/user_detail.php?user_id=<?= $fetch_admin['id']; ?>">
      <img src="../images/<?= $fetch_admin['image']; ?>" class="image" alt=""></a>
            <div>
            <a href="../user/user_detail.php?user_id=<?= $fetch_admin['id']; ?>"><?= $fetch_admin['name']; ?></a>
               <div><?= $fetch_order['date']; ?></div>
            </div>
         </div>
         <?php if ($fetch_kid['image'] != '') { ?>
            <a href="kid_detail.php?kid_id=<?= $fetch_order['kid_id']; ?>" class="img" alt="">
            <img src="../images/<?= $fetch_kid['image']; ?>" class="article_image" alt=""></a>
         <?php } ?>
         <div class="status" style="background-color:<?php if ($fetch_order['status'] == 'waiting for approval from centre') {echo 'red'; } else if ($fetch_order['status'] == 'waiting for home visit/interview') {echo 'blue'; } else {echo 'limegreen';}; ?>;"><?= $fetch_order['status']; ?></div>
         <a href="kid_detail.php?kid_id=<?= $fetch_order['kid_id']; ?>" class="img" alt=""></a> 
         <div class="title"><i>Order: </i><?= $fetch_order['id']; ?></div></a>  
         <div class="title"><i>Customer Name: </i><?= $fetch_admin['name']; ?></div></a>      
         <div class="title"><i>Pet Name: </i><?= $fetch_kid['name']; ?></div></a>
         <div class="title"><i>Price: RM</i><span><?= $fetch_kid['price']; ?></span></div>
         <div class="title"><i>Home Visit / Interview Time:</i></div>
         <div class="price"><span><?= $fetch_order['appoint_date']; ?></span></div>
         <a href="kid_detail.php?kid_id=<?= $fetch_order['kid_id']; ?>" class="btn">view Pet detail</a>
         <div class="flex-btn">
            <a href="order_edit.php?id=<?= $order_id; ?>" class="option-btn">edit</a>
            <a href="order_view.php?id=<?= $order_id; ?>&delete=1" class="delete-btn" onclick="return delete_msg(event);">delete</a>
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