<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}   

if(isset($_POST['save'])){
   $report_id = $_GET['id'];
   $centre_id = $_POST["centre_id"];
   $centre_id = filter_var($centre_id, FILTER_SANITIZE_SPECIAL_CHARS);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_SPECIAL_CHARS);

   if(!empty($report_id)){
      $update_report= $conn->prepare("UPDATE `report` SET centre_id = ?, status = ? WHERE id = ?");
      $update_report->execute([$centre_id, $status, $report_id]);
   $success_msg[] = 'report Info Updated successfully!';
   }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Report</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
include "../admin/admin_header.php"; 
?>

<section class="ar_edit">

   <h1 class="heading">Report</h1>

   <?php
      $report_id = $_GET['id'];
      $select_report= $conn->prepare("SELECT * FROM `report` WHERE id = ?");
      $select_report->execute([$report_id]);
      if($select_report->rowCount() > 0){
         while($fetch_report= $select_report->fetch(PDO::FETCH_ASSOC)){
            $report_id = $fetch_report['id'];

            $select_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
            $select_user->execute([$fetch_report['reporter_id']]);
            $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
   <?php if ($fetch_report['image'] != '') { ?>
   <img src="../images/<?= $fetch_report['image']; ?>" class="image" alt="">
   <?php } ?>  
   <p>Evidence image: </p>
   <p><i>ID: </i><?= $fetch_report['id']; ?></p></a>  
         <p><i>Reporter Name: </i><?= $fetch_user['name']; ?></p></a>      
         <p><i>Title: </i><?= $fetch_report['title']; ?></p></a>
         <p><?= $fetch_report['content']; ?></p>
         <p><i>Location: </i><?= $fetch_report['location']; ?></p></a>
         <p><i>Address: </i><?= $fetch_report['address']; ?></p></a>
   <p>Status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fetch_report['status']; ?>" selected><?= $fetch_report['status']; ?></option>
         <option value="waiting for centre">waiting for centre</option>
         <option value="undergoing">undergoing</option>
         <option value="finish">finish</option>
      </select>
      <input type="hidden" name="centre_id" value="<?= $_SESSION['id']; ?>">
      <div class="col">
      <input type="submit" value="save" name="save" class="btn">
      <a href="report_view.php" class="exit-btn">go back</a>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no report found!</p>';
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