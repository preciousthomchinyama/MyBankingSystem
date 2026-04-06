<?php
include '../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_name = $_POST['account_name'];
    $account_number = "10" . random_int(100000, 999999); // Generate a unique account number
    $password = $_POST['password'];

    // Insert new account into the database
    $sql = "INSERT INTO `members` (account_name, account_number, password) VALUES ('$account_name', '$account_number', sha1('$password'))";

    if (mysqli_query($conn, $sql)) {
        $msg = "New account created successfully. Account Number: " . $account_number;
    } else {
        $msg = "Error: " . mysqli_error($conn);
    }
    echo json_encode(['message' => $msg]);

}