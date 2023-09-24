<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}   

if(isset($_POST['save'])){
   $event_id = $_GET['event_id'];
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_SPECIAL_CHARS);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_SPECIAL_CHARS);
   $date = $_POST['datetime'];
   $date = filter_var($date, FILTER_SANITIZE_SPECIAL_CHARS);

   $update_post = $conn->prepare("UPDATE `event` SET title = ?, content = ?, status = ?, date = ? WHERE id = ?");
   $update_post->execute([$title, $content, $status, $date, $event_id]);
   $success_msg[] = 'Updated successfully!';
   if(!empty($event_id)){
      $update_title = $conn->prepare("UPDATE `event` SET title = ? WHERE id = ?");
      $update_title->execute([$title, $event_id]);
      $success_msg[] = 'updated successfully!';
   }
   if(!empty($content)){
      $update_content = $conn->prepare("UPDATE `event` SET content = ? WHERE id = ?");
      $update_content->execute([$content, $event_id]);
      $success_msg[] = 'content updated successfully!';
   }
   if(!empty($date)){
      $update_date = $conn->prepare("UPDATE `event` SET date = ? WHERE id = ?");
      $update_date->execute([$date, $event_id]);
      $success_msg[] = 'updated successfully!';
   }
   
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
   $image_ext = pathinfo($image, PATHINFO_EXTENSION);
   $re_image = uni_id().'.'.$image_ext;
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = "../images/" . $re_image;

   if(!empty($image)){
         $update_image = $conn->prepare("UPDATE `event` SET `image` = ? WHERE id = ?");
         $update_image->execute([$re_image, $event_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         $success_msg[] = 'updated successfully!';
      }
   
      $image2 = $_FILES['image2']['name'];
      $image2 = filter_var($image2, FILTER_SANITIZE_SPECIAL_CHARS);
      $image2_ext = pathinfo($image2, PATHINFO_EXTENSION);
      $re_image2 = uni_id().'.'.$image2_ext;
      $image2_tmp_name = $_FILES['image2']['tmp_name'];
      $image2_folder = '../images/'.$re_image2;
      
   if(!empty($image2)){
         $update_image2 = $conn->prepare("UPDATE `event` SET `image2` = ? WHERE id = ?");
         $update_image2->execute([$re_image2, $event_id]);
         move_uploaded_file($image2_tmp_name, $image2_folder);
         $success_msg[] = 'updated successfully!';
      }
      
      $image3 = $_FILES['image3']['name'];
      $image3 = filter_var($image3, FILTER_SANITIZE_SPECIAL_CHARS);
      $image3_ext = pathinfo($image3, PATHINFO_EXTENSION);
      $re_image3 = uni_id().'.'.$image3_ext;
      $image3_tmp_name = $_FILES['image3']['tmp_name'];
      $image3_folder = '../images/'.$re_image3;
      
   if(!empty($image3)){
         $update_image3 = $conn->prepare("UPDATE `event` SET `image3` = ? WHERE id = ?");
         $update_image3->execute([$re_image3, $event_id]);
         move_uploaded_file($image3_tmp_name, $image3_folder);
         $success_msg[] = 'updated successfully!';
      }
      
      $image4 = $_FILES['image4']['name'];
      $image4 = filter_var($image4, FILTER_SANITIZE_SPECIAL_CHARS);
      $image4_ext = pathinfo($image4, PATHINFO_EXTENSION);
      $re_image4 = uni_id().'.'.$image4_ext;
      $image4_tmp_name = $_FILES['image4']['tmp_name'];
      $image4_folder = '../images/'.$re_image4;
      
   if(!empty($image4)){
         $update_image4 = $conn->prepare("UPDATE `event` SET `image4` = ? WHERE id = ?");
         $update_image4->execute([$re_image4, $event_id]);
         move_uploaded_file($image4_tmp_name, $image4_folder);
         $success_msg[] = 'updated successfully!';
      }
}

if(isset($_POST['delete_post'])){
   $event_id = $_POST['event_id'];
   $event_id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);
   $delete_image = $conn->prepare("SELECT * FROM `event` WHERE id = ?");
   $delete_image->execute([$event_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image'] != ''){
      unlink('../images/'.$fetch_delete_image['image']);
   }
   $delete_image2 = $conn->prepare("SELECT * FROM `event` WHERE id = ?");
   $delete_image2->execute([$event_id]);
   $fetch_delete_image2 = $delete_image2->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image2'] != ''){
      unlink('../images/'.$fetch_delete_image2['image2']);
   }
   $delete_image3 = $conn->prepare("SELECT * FROM `event` WHERE id = ?");
   $delete_image3->execute([$event_id]);
   $fetch_delete_image3 = $delete_image3->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image3'] != ''){
      unlink('../images/'.$fetch_delete_image['image3']);
   }
   $delete_image4 = $conn->prepare("SELECT * FROM `event` WHERE id = ?");
   $delete_image4->execute([$event_id]);
   $fetch_delete_image4 = $delete_image4->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image4'] != ''){
      unlink('../images/'.$fetch_delete_image['image4']);
   }
   $delete_post = $conn->prepare("DELETE FROM `event` WHERE id = ?");
   $delete_post->execute([$event_id]);
   $delete_comment = $conn->prepare("DELETE FROM `comment` WHERE id = ?");
   $delete_comment->execute([$event_id]);
   $delete_like = $conn->prepare("DELETE FROM `like` WHERE id = ?");
   $delete_like->execute([$event_id]);
   $affected_rows = $delete_post->rowCount();
   if($affected_rows > 0){
      $success_msg[] = 'Event deleted successfully!';
   }else{
      $error_message[] = 'Failed to delete the event.';
   }
}

if(isset($_POST['delete_image'])){
   $empty_image = '';
   $event_id = $_GET['event_id'];
   $delete_image = $conn->prepare("SELECT * FROM `event` WHERE id = ?");
   $delete_image->execute([$event_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image'] != ''){
      unlink('../images/'.$fetch_delete_image['image']);
   }
   $unset_image = $conn->prepare("UPDATE `event` SET image = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $event_id]);
   $success_msg[] = 'image deleted!';
}

if(isset($_POST['delete_image2'])){
   $empty_image = '';
   $event_id = $_GET['event_id'];
   $delete_image = $conn->prepare("SELECT * FROM `event` WHERE id = ?");
   $delete_image->execute([$event_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image2'] != ''){
      unlink('../images/'.$fetch_delete_image['image2']);
   }
   $unset_image = $conn->prepare("UPDATE `event` SET image2 = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $event_id]);
   $success_msg[] = 'image 2 deleted!';
}

if(isset($_POST['delete_image3'])){
   $empty_image = '';
   $event_id = $_GET['event_id'];
   $delete_image = $conn->prepare("SELECT * FROM `event` WHERE id = ?");
   $delete_image->execute([$event_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image3'] != ''){
      unlink('../images/'.$fetch_delete_image['image3']);
   }
   $unset_image = $conn->prepare("UPDATE `event` SET image3 = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $event_id]);
   $success_msg[] = 'image 3 deleted!';
}

if(isset($_POST['delete_image4'])){
   $empty_image = '';
   $event_id = $_GET['event_id'];
   $delete_image = $conn->prepare("SELECT * FROM `event` WHERE id = ?");
   $delete_image->execute([$event_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image4'] != ''){
      unlink('../images/'.$fetch_delete_image['image4']);
   }
   $unset_image = $conn->prepare("UPDATE `event` SET image4 = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $event_id]);
   $success_msg[] = 'image 4 deleted!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Event Edit</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
include "../admin/admin_header.php"; 
?>

<section class="ar_edit">

   <h1 class="heading">edit Article</h1>

   <?php
      $event_id = $_GET['event_id'];
      $event = $conn->prepare("SELECT * FROM `event` WHERE id = ?");
      $event->execute([$event_id]);
      if($event->rowCount() > 0){
         while($fetch_event = $event->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="old_image" value="<?= $fetch_event['image']; ?>">
      <input type="hidden" name="id" value="<?= $fetch_event['id']; ?>">
      <p>post status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fetch_event['status']; ?>" selected><?= $fetch_event['status']; ?></option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>Title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="add post title" class="box" value="<?= $fetch_event['title']; ?>">
      <p>Content <span>*</span></p>
      <textarea name="content" class="box" required maxlength="10000" placeholder="write your content..." cols="30" rows="10"><?= $fetch_event['content']; ?></textarea>
      <p>Event date and time<span>*</span></p>
      <input type="datetime-local" name="datetime" required class="box" id="datetime-input" value="<?= $fetch_event['date']; ?>">
      <?php if($fetch_event['image'] != ''){ ?>
         <img src="../images/<?= $fetch_event['image']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image" style="float: right;">
      <?php } ?>
      <p>Thumbnail image</p>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <?php if($fetch_event['image2'] != ''){ ?>
         <img src="../images/<?= $fetch_event['image2']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image2" style="float: right;">
      <?php } ?>
      <p>Image 2</p>
      <input type="file" name="image2" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <?php if($fetch_event['image3'] != ''){ ?>
         <img src="../images/<?= $fetch_event['image3']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image3" style="float: right;">
      <?php } ?>
      <p>Image 3</p>
      <input type="file" name="image3" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <?php if(!empty($fetch_event['image4'])){ ?>
         <img src="../images/<?= $fetch_event['image4']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image4" style="float: right;">
      <?php } ?>
      <p>Image 4</p>
      <input type="file" name="image4" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <div class="flex-btn">
         <input type="submit" value="save " name="save" class="btn">
         <a href="event_view.php?event_id=<?= $event_id; ?>&delete=1" class="delete-btn" onclick="return delete_msg(event);">delete</a>
      </div>
      <a href="event_view.php" class="exit-btn" onclick="return confirm_msg(event);">go back</a>
   </form>

   <?php
         }
      }else{
         echo '<p class="empty">no post found!</p>';
   ?>
   <div class="flex-btn">
      <a href="event_view.php" class="option-btn">view event</a>
      <a href="event_add.php" class="option-btn">add event</a>
   </div>
   <?php
      }
   ?>

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
    };

    function confirm_msg(event) {
    event.preventDefault();
    var urlToRedirect = event.currentTarget.getAttribute('href');  
    console.log(urlToRedirect); 
    swal({
      title: "Are you sure to Go Back?",
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