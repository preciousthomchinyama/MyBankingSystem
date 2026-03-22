<?php
include "config.php";

header('Content-Type: application/json');

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $acc_number = $_POST['acc_number'];
    $w_amount = floatval($_POST['w_amount']);

    // Validate input
    if(empty($acc_number) || !is_numeric($w_amount) || $w_amount <= 0){
        echo json_encode(['withd_message' => 'Invalid account or amount']);
        exit;
    }

    // Get current balance
    $check_sql = "SELECT `balance` FROM `funds` WHERE account_number = '$acc_number'";
    $check_result = mysqli_query($conn, $check_sql);

    if(mysqli_num_rows($check_result) > 0){

        $row = mysqli_fetch_assoc($check_result);
        $balance = floatval($row['balance']);

        // 🚫 Prevent overdraft
        if($w_amount > $balance){
            echo json_encode(['withd_message' => 'Insufficient funds']);
            exit;
        }

        $curr_balance = round($balance - $w_amount, 2);

        // Update balance (IMPORTANT: WHERE clause)
        $update_sql = "UPDATE `funds` SET balance = '$curr_balance' WHERE account_number = '$acc_number'";
        $update_result = mysqli_query($conn, $update_sql);

        if($update_result){
            $msg = "You have withdrawn ".$w_amount.". Your current balance is ".$curr_balance;
        }else{
            $msg = "Transaction failed: ".mysqli_error($conn);
        }

    } else {
        $msg = "Account number not found.";
    }

    echo json_encode(['withd_message' => $msg]);
}