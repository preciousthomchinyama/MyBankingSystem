<?php

include 'config.php';

$sql = "SELECT SUM(balance) AS total_funds FROM `funds`";

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    $total_funds = $row['total_funds'];
    echo "Total Funds in the Bank: " . $total_funds;
} else {
    echo "No funds found.";
}