<?php
include "../connect.php"; 

session_start();
$id = $_SESSION['id'];
if(!isset($id)){
   header('location: admin_login.php');
   exit();
}    

if(isset($_POST["submit"])){
   $kid_id = uni_id();
   $centre_id = $_POST['centre_id'];
   $centre_id = filter_var($centre_id, FILTER_SANITIZE_SPECIAL_CHARS);
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
   $price= $_POST["price"];
   $price= filter_var($price, FILTER_SANITIZE_SPECIAL_CHARS);
   $color = $_POST["color"];
   $color = filter_var($color, FILTER_SANITIZE_SPECIAL_CHARS);
   $location = $_POST['location'];
   $location = filter_var($location, FILTER_SANITIZE_SPECIAL_CHARS);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
   $status = 'waiting for owner';

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

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
   $image_ext = pathinfo($image, PATHINFO_EXTENSION);
   $re_image = uni_id().'.'.$image_ext;
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = "../images/" . $re_image;

   $image2 = $_FILES['image2']['name'];
   $image2 = filter_var($image2, FILTER_SANITIZE_SPECIAL_CHARS);
   $image2_ext = pathinfo($image2, PATHINFO_EXTENSION);
   $re_image2 = uni_id().'.'.$image2_ext;
   $image2_tmp_name = $_FILES['image2']['tmp_name'];
   $image2_folder = "../images/" . $re_image2;

   if (!empty($image2)) {
      move_uploaded_file($image2_tmp_name, $image2_folder);
  }else{
   $re_image2 = '';
}
     

   $image3 = $_FILES['image3']['name'];
   $image3 = filter_var($image3, FILTER_SANITIZE_SPECIAL_CHARS);
   $image3_ext = pathinfo($image3, PATHINFO_EXTENSION);
   $re_image3 = uni_id().'.'.$image3_ext;
   $image3_tmp_name = $_FILES['image3']['tmp_name'];
   $image3_folder = "../images/" . $re_image3;

      
   if (!empty($image3)) {
      move_uploaded_file($image3_tmp_name, $image3_folder);
  }else{
   $re_image3 = '';
}

   $image4 = $_FILES['image4']['name'];
   $image4 = filter_var($image4, FILTER_SANITIZE_SPECIAL_CHARS);
   $image4_ext = pathinfo($image4, PATHINFO_EXTENSION);
   $re_image4 = uni_id().'.'.$image4_ext;
   $image4_tmp_name = $_FILES['image4']['tmp_name'];
   $image4_folder = "../images/" . $re_image4;

   if (!empty($image4)) {
      move_uploaded_file($image4_tmp_name, $image4_folder);
  }else{
   $re_image4 = '';
}

   $kid = $conn->prepare("SELECT * FROM `kid` WHERE name = ? AND centre_id = ? AND category = ?");
   $kid->execute([$name, $centre_id, $category]);

   if($kid->rowCount() > 0){
      $error_msg[] = 'This pet is already added.';
   }else{
      $insert_kid = $conn->prepare("INSERT INTO `kid`(id, name, age, centre_id, category, breed, sex, color, vaccinated, dewormed, spray, neutered, price, location, description, image, image2, image3, image4, status) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
      $insert_kid->execute([$kid_id, $name, $age, $centre_id, $category, $breed, $sex, $color, $vaccinated, $dewormed, $spray, $neutered, $price, $location, $description, $re_image, $re_image2, $re_image3, $re_image4, $status]);
         move_uploaded_file($image_tmp_name, $image_folder);
         // move_uploaded_file($image2_tmp_name, $image2_folder);
         // move_uploaded_file($image3_tmp_name, $image3_folder);
         // move_uploaded_file($image4_tmp_name, $image4_folder);
         $success_msg[] = 'Pet is successfully added!';
         // $_SESSION['success_msg'] = $success_msg; // Store success message in session
         // header('Location: kid_view.php');
         // exit();
      }
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Kid Add</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
include "../admin/admin_header.php"; 
?>
<section class="norm_container">
   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h1 class="heading">Add New Kid</h1>
      <input type="hidden" name="centre_id" value="<?= $fetch_profile['id']; ?>">
      <p>Name <span>*</span></p>
      <input type="text" name="name" placeholder="Enter animal's name" maxlength="255" required class="box">
      <p>Age <span>*</span></p>
      <input type="text" name="age" placeholder="Enter animal's age" maxlength="255" required class="box">
      <div class="flex">
         <div class="col">
            <p>Category <span>*</span></p>
            <select name="category" class="box" required>
            <option value="" disabled selected>--category</option>
            <option value="Dog">Dog</option>
            <option value="Cat">Cat</option>
            <option value="Rabbit">Rabbit</option>
            <option value="Hamster">Hamster</option>
            <option value="Birds">Birds</option>
            </select>
            <p>Breed <span>*</span></p>
            <input type="text" name="breed" placeholder="Enter breed" maxlength="255" required class="box">
         </div>
         <div class="col">
         <p>Sex <span>*</span></p>
         <select name="sex" class="box" required>
            <option value="" disabled selected>--sex</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            </select>
            <p>Color <span>*</span></p>
            <input type="text" name="color" placeholder="Enter animal's color" maxlength="255" required class="box">
         </div>
         </div>
            <div class="checkbox">
            <p>Condition <span>*</span></p>
               <div required class="box">
                  <p><input type="checkbox" name="vaccinated" value="yes" />Vaccinated</p>
                  <p><input type="checkbox" name="dewormed" value="yes" />Dewormed</p>
                  <p><input type="checkbox" name="spray" value="yes" />Spray</p>
                  <p><input type="checkbox" name="neutered" value="yes" />Neutered</p>
               </div>
            </div>
            <p>Price <span>*</span></p>
            <input type="number" name="price" placeholder="Enter Price" maxlength="255" required class="box">
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
        $selected = ($fetch_profile['location'] === $location) ? 'selected' : '';
        echo '<option value="' . $location . '" ' . $selected . '>' . $location . '</option>';
    }
    ?>
</select>
            <p>Description <span>*</span></p>
            <textarea name="description" class="box" required maxlength="10000" placeholder="Description" cols="30" rows="10"></textarea>
            <p>Image<span>*</span></p>
            <input type="file" name="image" required class="box" accept="image/*">
            <p>Image2</p>
            <input type="file" name="image2" class="box" accept="image/*">
            <p>Image3</p>
            <input type="file" name="image3" class="box" accept="image/*">
            <p>Image4</p>
            <input type="file" name="image4" class="box" accept="image/*">
         </div>
         </div>
      </div>
         </div>
   </div>
   <div class="flex-btn">
         <input type="submit" value="Submit" name="submit" class="btn">
      </div>
      <a href="../admin/kid_view.php" class="exit-btn">Back</a>
   </form>
</section>

<script src="../js/admin_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include '../message.php'; ?>
</body>
</html>
