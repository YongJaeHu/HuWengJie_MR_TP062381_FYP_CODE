<?php
include "../connect.php"; 

session_start();
if(isset($_SESSION['id'])){
    $id = $_SESSION['id'];
  }else{
    $id = '';
  };  

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Centre view</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include "../user/user_header.php"; 
?>


<section class="categories">

   <h1 class="heading">Centre Location</h1>

   <div class="box-container">
      <div class="box"><span>1</span><a href="centre_location.php?location=Johor">Johor</a></div>
      <div class="box"><span>2</span><a href="centre_location.php?location=Kedah">Kedah</a></div>
      <div class="box"><span>3</span><a href="centre_location.php?location=Kelantan">Kelantan</a></div>
      <div class="box"><span>4</span><a href="centre_location.php?location=Melaka">Melak</a></div>
      <div class="box"><span>5</span><a href="centre_location.php?location=Negeri Sembilan">Negeri Sembilan</a></div>
      <div class="box"><span>6</span><a href="centre_location.php?location=Pahang">Pahang</a></div>
      <div class="box"><span>7</span><a href="centre_location.php?location=Perak">Perak</a></div>
      <div class="box"><span>8</span><a href="centre_location.php?location=Perlis">Perlis</a></div>
      <div class="box"><span>9</span><a href="centre_location.php?location=Pulau Pinang (Penang)">Pulau Pinang (Penang)</a></div>
      <div class="box"><span>10</span><a href="centre_location.php?location=Sabah">Sabah</a></div>
      <div class="box"><span>11</span><a href="centre_location.php?location=Sarawak">Sarawak</a></div>
      <div class="box"><span>12</span><a href="centre_location.php?location=Selangor">Selangor</a></div>
      <div class="box"><span>13</span><a href="centre_location.php?location=Terengganu">Terengganu</a></div>
      <div class="box"><span>14</span><a href="centre_location.php?location=Kuala Lumpur">Kuala Lumpur</a></div>
      <div class="box"><span>15</span><a href="centre_location.php?location=Labuan">Labuan</a></div>
      <div class="box"><span>16</span><a href="centre_location.php?location=Putrajaya">Putrajaya</a></div>
   </div>

</section>
<?php include 'footer.php'; ?>
<script src="../js/script.js"></script>
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