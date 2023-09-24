<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}    

$name = ''; // Initialize the $name variable

if(isset($_POST['publish'])){
   $centre_id= $_POST['id'];
   $name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS);
   $approval = '1';

   $select_post = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
   $select_post->execute([$centre_id]);
   $fetch_profile = $select_post->fetch(PDO::FETCH_ASSOC);

   if ($select_post->rowCount() > 0 AND $fetch_profile['approval'] == '1') {
      $error_msg[] = 'You already are approved!';
   }else{
      $update_pass = $conn->prepare("UPDATE `admin` SET approval = ? WHERE id = ?");
      $update_pass->execute([$approval, $centre_id]);
      $success_msg[] = 'Approved Successfully!';
   }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>posts</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
include "../admin/admin_header.php"; 
?>

<section class="ar_edit">

   <!-- <h1 class="heading">add new post</h1> -->

   <form action="" method="post" enctype="multipart/form-data">
   <h1 class="heading">Approval</h1>
      <input type="hidden" name="name" value="<?= $fetch_profile['name']; ?>">
      <input type="hidden" name="email" value="<?= $fetch_profile['email']; ?>">
      <input type="hidden" name="id" value="<?= $fetch_profile['id']; ?>">
      <div class="flex-btn">
         <input type="submit" value="Request to be approved" name="publish" class="btn">
      </div>
      <a href="../admin/article_view.php" class="exit-btn">Back</a>
   </form>
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
