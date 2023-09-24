<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}   

if(isset($_POST['save'])){
   $kid_id = $_GET['id'];
   $name = $_POST["name"];
   $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
   $age = $_POST["age"];
   $age = filter_var($age, FILTER_SANITIZE_SPECIAL_CHARS); 
   $category = $_POST["category"];
   $category = filter_var($category, FILTER_SANITIZE_SPECIAL_CHARS);
   $breed = $_POST["breed"];
   $breed= filter_var($breed, FILTER_SANITIZE_SPECIAL_CHARS);
   $sex= $_POST["sex"];
   $sex= filter_var($sex, FILTER_SANITIZE_SPECIAL_CHARS);
   $color = $_POST["color"];
   $color = filter_var($color, FILTER_SANITIZE_SPECIAL_CHARS);
   $price= $_POST["price"];
   $price= filter_var($price, FILTER_SANITIZE_SPECIAL_CHARS);
   $location = $_POST['location'];
   $location = filter_var($location, FILTER_SANITIZE_SPECIAL_CHARS);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_SPECIAL_CHARS);
   if(isset($_POST['vaccinated'])){
      $vaccinated = $_POST['vaccinated'];
      $vaccinated = filter_var($vaccinated, FILTER_SANITIZE_SPECIAL_CHARS);
   }else{
      $vaccinated = 'no';
   }
   if(isset($_POST['dewormed'])){
      $dewormed = $_POST['dewormed'];
      $dewormed = filter_var($dewormed, FILTER_SANITIZE_SPECIAL_CHARS);
   }else{
      $dewormed = 'no';
   }
   if(isset($_POST['spray'])){
      $spray = $_POST['spray'];
      $spray = filter_var($spray, FILTER_SANITIZE_SPECIAL_CHARS);
   }else{
      $spray = 'no';
   }
   if(isset($_POST['neutered'])){
      $neutered = $_POST['neutered'];
      $neutered = filter_var($neutered, FILTER_SANITIZE_SPECIAL_CHARS);
   }else{
      $neutered = 'no';
   }

   if(!empty($kid_id)){
      $update_kid = $conn->prepare("UPDATE `kid` SET name = ?, age = ?, breed = ?, sex = ?, color = ?, price = ?, location=?, description = ?, vaccinated = ?, dewormed = ?, spray = ?, neutered = ? WHERE id = ?");
      $update_kid->execute([$name, $age, $breed, $sex, $color, $price, $location, $description, $vaccinated, $dewormed, $spray, $neutered, $kid_id]);
   $success_msg[] = 'Kid Info Updated successfully!';
   }
   
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
   $image_ext = pathinfo($image, PATHINFO_EXTENSION);
   $re_image = uni_id().'.'.$image_ext;
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../images/'.$re_image;

   if(!empty($image)){
         $update_image = $conn->prepare("UPDATE `kid` SET `image` = ? WHERE id = ?");
         $update_image->execute([$re_image, $kid_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         $message[] = 'image updated successfully!';
      }

      $image2 = $_FILES['image2']['name'];
      $image2 = filter_var($image2, FILTER_SANITIZE_SPECIAL_CHARS);
      $image2_ext = pathinfo($image2, PATHINFO_EXTENSION);
      $re_image2 = uni_id().'.'.$image2_ext;
      $image2_tmp_name = $_FILES['image2']['tmp_name'];
      $image2_folder = '../images/'.$re_image2;
      
   if(!empty($image2)){
         $update_image2 = $conn->prepare("UPDATE `kid` SET `image2` = ? WHERE id = ?");
         $update_image2->execute([$re_image2, $kid_id]);
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
         $update_image3 = $conn->prepare("UPDATE `kid` SET `image3` = ? WHERE id = ?");
         $update_image3->execute([$re_image3, $kid_id]);
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
         $update_image4 = $conn->prepare("UPDATE `kid` SET `image4` = ? WHERE id = ?");
         $update_image4->execute([$re_image4, $kid_id]);
         move_uploaded_file($image4_tmp_name, $image4_folder);
         $success_msg[] = 'updated successfully!';
         $_SESSION['success_msg'] = $success_msg; // Store success message in session
         header('Location: kid_view.php');
         exit();
      }
}

if(isset($_POST['delete_kid'])){
   $kid_id = $_POST['kid_id'];
   $kid_id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);
   $delete_image = $conn->prepare("SELECT * FROM `kid` WHERE id = ?");
   $delete_image->execute([$kid_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image'] != ''){
      unlink('../images/'.$fetch_delete_image['image']);
   }
   $delete_image2 = $conn->prepare("SELECT * FROM `kid` WHERE id = ?");
   $delete_image2->execute([$kid_id]);
   $fetch_delete_image2 = $delete_image2->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image2'] != ''){
      unlink('../images/'.$fetch_delete_image2['image2']);
   }
   $delete_image3 = $conn->prepare("SELECT * FROM `kid` WHERE id = ?");
   $delete_image3->execute([$kid_id]);
   $fetch_delete_image3 = $delete_image3->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image3'] != ''){
      unlink('../images/'.$fetch_delete_image['image3']);
   }
   $delete_image4 = $conn->prepare("SELECT * FROM `kid` WHERE id = ?");
   $delete_image4->execute([$kid_id]);
   $fetch_delete_image4 = $delete_image4->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image4'] != ''){
      unlink('../images/'.$fetch_delete_image['image4']);
   }
   $delete_kid = $conn->prepare("DELETE FROM `kid` WHERE id = ?");
   $delete_kid->execute([$kid_id]);
   $delete_comment = $conn->prepare("DELETE FROM `comment` WHERE id = ?");
   $delete_comment->execute([$kid_id]);
   $affected_rows = $delete_kid->rowCount();
   if($affected_rows > 0){
      $message[] = 'kid deleted successfully!';
   }else{
      $message[] = 'Failed to delete the kid.';
   }
}

if(isset($_POST['delete_image'])){
   $empty_image = '';
   $kid_id = $_GET['id'];
   $delete_image = $conn->prepare("SELECT * FROM `kid` WHERE id = ?");
   $delete_image->execute([$kid_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image'] != ''){
      unlink('../images/'.$fetch_delete_image['image']);
   }
   $unset_image = $conn->prepare("UPDATE `kid` SET image = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $kid_id]);
   $success_msg[] = 'image deleted!';
}

if(isset($_POST['delete_image2'])){
   $empty_image = '';
   $kid_id = $_GET['id'];
   $delete_image = $conn->prepare("SELECT * FROM `kid` WHERE id = ?");
   $delete_image->execute([$kid_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image2'] != ''){
      unlink('../images/'.$fetch_delete_image['image2']);
   }
   $unset_image = $conn->prepare("UPDATE `kid` SET image2 = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $kid_id]);
   $success_msg[] = 'image 2 deleted!';
}

if(isset($_POST['delete_image3'])){
   $empty_image = '';
   $kid_id = $_GET['id'];
   $delete_image = $conn->prepare("SELECT * FROM `kid` WHERE id = ?");
   $delete_image->execute([$kid_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image3'] != ''){
      unlink('../images/'.$fetch_delete_image['image3']);
   }
   $unset_image = $conn->prepare("UPDATE `kid` SET image3 = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $kid_id]);
   $success_msg[] = 'image 3 deleted!';
}

if(isset($_POST['delete_image4'])){
   $empty_image = '';
   $kid_id = $_GET['id'];
   $delete_image = $conn->prepare("SELECT * FROM `kid` WHERE id = ?");
   $delete_image->execute([$kid_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image4'] != ''){
      unlink('../images/'.$fetch_delete_image['image4']);
   }
   $unset_image = $conn->prepare("UPDATE `kid` SET image4 = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $kid_id]);
   $success_msg[] = 'image 4 deleted!';
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

   <h1 class="heading">edit Kid info</h1>

   <?php
      $kid_id = $_GET['id'];
      $select_kid = $conn->prepare("SELECT * FROM `kid` WHERE id = ?");
      $select_kid->execute([$kid_id]);
      if($select_kid->rowCount() > 0){
         while($fetch_kid = $select_kid->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
   <!-- <p>Status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fetch_kid['status']; ?>" selected><?= $fetch_kid['status']; ?></option>
         <option value="waiting for approval from centre">waiting for approval from centre</option>
         <option value="waiting for home visit/interview">waiting for home visit/interview</option>
         <option value="finish">finish</option>
      </select> -->
      <!-- <input type="hidden" name="old_image" value="<?= $fetch_kid['image']; ?>"> -->
      <input type="hidden" name="id" value="<?= $fetch_kid['id']; ?>">
      <p>Name<span>*</span></p>
      <input type="text" name="name" maxlength="100" placeholder="add kid title" required class="box" value="<?= $fetch_kid['name']; ?>">
      <p>Age <span>*</span></p>
      <input type="text" name="age" placeholder="Enter animal's age" maxlength="255" required class="box" value=<?= $fetch_kid['age']; ?>>
      <div class="flex">
         <div class="col">
            <p>Category <span>*</span></p>
            <select name="category" class="box">
            <option value="" selected><?= $fetch_kid['category']; ?></option>
            <option value="Dog">Dog</option>
            <option value="Cat">Cat</option>
            <option value="Rabbit">Rabbit</option>
            <option value="Hamster">Hamster</option>
            <option value="Birds">Birds</option>
            </select>
            <p>Breed <span>*</span></p>
            <input type="text" name="breed" required placeholder="Enter breed" maxlength="255" class="box" value="<?= $fetch_kid['breed']; ?>">
         </div>
         <div class="col">
         <p>Sex <span>*</span></p>
<select name="sex" class="box">
   <option value="Male" <?php if($fetch_kid['sex'] === 'Male') echo 'selected'; ?>>Male</option>
   <option value="Female" <?php if($fetch_kid['sex'] === 'Female') echo 'selected'; ?>>Female</option>
</select>
            <p>Color <span>*</span></p>
            <input type="text" name="color" required placeholder="Enter animal's color" maxlength="255" class="box" value="<?= $fetch_kid['color']; ?>">
      </div>
   </div>
   <div class="checkbox">
   <p>Condition <span>*</span></p>
         <div required class="box">
         <p><input type="checkbox" name="vaccinated" value="yes" <?php if($fetch_kid['vaccinated'] == 'yes'){echo 'checked'; } ?>/>Vaccinated</p>
                  <p><input type="checkbox" name="dewormed" value="yes" <?php if($fetch_kid['dewormed'] == 'yes'){echo 'checked'; } ?>/>Dewormed</p>
                  <p><input type="checkbox" name="spray" value="yes" <?php if($fetch_kid['spray'] == 'yes'){echo 'checked'; } ?>/>Spray</p>
                  <p><input type="checkbox" name="neutered" value="yes" <?php if($fetch_kid['neutered'] == 'yes'){echo 'checked'; } ?>/>Neutered</p>
               </div>
      </div>
      <p>Price <span>*</span></p>
            <input type="number" name="price" placeholder="Enter Price" maxlength="255" required class="box" value="<?= $fetch_kid['price'];?>">
            <p>Location <span>*</span></p>
<select name="location" class="box" required>
    <?php
    $locations = array(
        "Johor",
        "Kedah",
        "Kelantan",
        "Melaka",
        "Negeri Sembilan",
        "Pahang",
        "Perak",
        "Perlis",
        "Pulau Pinang (Penang)",
        "Sabah",
        "Sarawak",
        "Selangor",
        "Terengganu",
        "Kuala Lumpur",
        "Labuan",
        "Putrajaya"
    );

    // Loop through the locations array to generate the options
    foreach ($locations as $location) {
        // Check if the location from fetch_profile matches the current option
        $selected = ($fetch_kid['location'] === $location) ? 'selected' : '';
        echo '<option value="' . $location . '" ' . $selected . '>' . $location . '</option>';
    }
    ?>
</select>
            <p>Description <span>*</span></p>
            <textarea name="description" required class="box" maxlength="10000" placeholder="Description" cols="30" rows="10"><?= $fetch_kid['description']; ?></textarea>
      <?php if($fetch_kid['image'] != ''){ ?>
         <img src="../images/<?= $fetch_kid['image']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image" style="float: right;">
      <?php } ?>
      <p>Thumbnail image</p>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <?php if($fetch_kid['image2'] != ''){ ?>
         <img src="../images/<?= $fetch_kid['image2']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image2" style="float: right;">
      <?php } ?>
      <p>Image 2</p>
      <input type="file" name="image2" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <?php if($fetch_kid['image3'] != ''){ ?>
         <img src="../images/<?= $fetch_kid['image3']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image3" style="float: right;">
      <?php } ?>
      <p>Image 3</p>
      <input type="file" name="image3" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <?php if($fetch_kid['image4'] != ''){ ?>
         <img src="../images/<?= $fetch_kid['image4']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image4" style="float: right;">
      <?php } ?>
      <p>Image 4</p>
      <input type="file" name="image4" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <div class="flex-btn">
         <input type="submit" value="save" name="save" class="btn">
         <a href="kid_view.php?id=<?= $kid_id; ?>&delete=1" class="delete-btn" onclick="return delete_msg(event);">delete</a>
      </div>
      <a href="kid_view.php" class="exit-btn">go back</a>
   </form>

   <?php
         }
      }else{
         echo '<p class="empty">no kid found!</p>';
   ?>
   <div class="flex-btn">
      <a href="kid_view.php" class="option-btn">view kid</a>
      <a href="kid_add.php" class="option-btn">add kid</a>
   </div>
   <?php
      }
   ?>

</section>
<script src="../js/admin_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>