<?php
$db_name = 'mysql:host=localhost;dbname=fyp_db';
$user_name = 'root';
$user_password = '';

try{$conn = new PDO($db_name, $user_name, $user_password);
}catch(PDOException $err){
    // header('location:404error.php');
    Echo "Connection failed" . $err->getMessage();  
    exit();
}
function uni_id() {
   $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
   $rand = array();
   $length = strlen($str) - 1;
   for ($i = 0; $i < 20; $i++) {
       $n = mt_rand(0, $length);
       $rand[] = $str[$n];
   }
   return implode($rand);
}
?>