<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "art_gallery1");

$user_id = $_SESSION['user_id'];
$result = mysqli_query($con, "SELECT * FROM checkout_orders WHERE user_id = '$user_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Summary</title>
    <style>
        table {
            width: 80%;
            margin: 40px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background: #f4f4f4;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Your Order Summary</h2>
    <table>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>City</th>
            <th>ZIP</th>
            <th>Country</th>
            <th>Payment</th>
            <th>Date</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['full_name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['address'] ?></td>
            <td><?= $row['city'] ?></td>
            <td><?= $row['zip'] ?></td>
            <td><?= $row['country'] ?></td>
            <td><?= $row['payment_method'] ?></td>
            <td><?= $row['created_at'] ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
