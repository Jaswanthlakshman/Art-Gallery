<?php
session_start();

// Optional: simulate user login
$_SESSION['user_id'] = 1; // Change based on your login system

// DB connection
$con = mysqli_connect("localhost", "root", "", "art_gallery1");
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}


   $user_id = $_SESSION['user_id'];
   $full_name = $_POST['full_name'];
   $email = $_POST['email'];
   $address = $_POST['address'];
   $city = $_POST['city'];
   $zip = $_POST['zip'];
   $country = $_POST['country'];
   $payment_method = $_POST['payment_method'];

   // Debug: uncomment if needed
   echo "<pre>"; print_r($_POST); echo "</pre>"; 

   $query = "INSERT INTO checkout_orders 
       (user_id, full_name, email, address, city, zip, country, payment_method)
       VALUES 
       ('$user_id', '$full_name', '$email', '$address', '$city', '$zip', '$country', '$payment_method')";
   if($con->query($query)==TRUE){
       header("location: profile.html");
   }
   else{
       echo "Error:".$con->error;
   }

   

?>
