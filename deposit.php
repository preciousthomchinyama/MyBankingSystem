<?php
include "config.php";

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === "POST") {

    $acc_number = $_POST['account_number'];
    $amount = floatval($_POST['amount']);

    // Validate input
    if(empty($acc_number) || !is_numeric($amount)) {
        echo json_encode(['dep_message' => 'Invalid account number or amount']);
        exit;
    }

    // Get current balance
    $sql = "SELECT `balance` FROM `funds` WHERE account_number = '$acc_number'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $balance = floatval($row['balance']); // ensure decimal
        $curr_balance = round($balance + $amount, 2); // round to 2 decimals

        // Update balance
        $update_sql = "UPDATE `funds` SET balance = '$curr_balance' WHERE account_number = '$acc_number'";
        $update_result = mysqli_query($conn, $update_sql);

        if($update_result) {
            $msg = "You have deposited ".$amount." in your account. Your current balance is ".$curr_balance;
        } else {
            $msg = "Transaction was not successful: ".mysqli_error($conn);
        }

    } else {
        $msg = "Account number not found.";
    }

    echo json_encode(['dep_message' => $msg]);
}