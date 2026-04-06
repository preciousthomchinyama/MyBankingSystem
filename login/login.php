<?php
include "../config.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if(!isset($_POST['admin_id'], $_POST['password'])){
        echo json_encode([
            'flag' => 'error',
            'login_msg' => 'Missing fields'
        ]);
        exit;
    }

    $admin_id = $_POST['admin_id'];
    $password = sha1($_POST['password']);

    $sql = "SELECT `first_name`,`surname`,`password`,`privilege` 
            FROM `users` 
            WHERE `id` = '$admin_id'";

    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) === 0){
        echo json_encode([
            'flag' => 'invalid_user',
            'login_msg' => 'No user found!!'
        ]);
        exit;
    }

    $row = mysqli_fetch_assoc($result);

    $fname = $row['first_name'];
    $sname = $row['surname'];
    $priv  = $row['privilege'];
    $pass  = $row['password'];

    // Check password
    if($password === $pass){
        echo json_encode([
            'flag' => 'valid_user',
            'login_msg' => "Correct credentials logging $fname $sname into the system",
            'priv' => $priv
        ]);
    } else {
        echo json_encode([
            'flag' => 'wrong_password',
            'login_msg' => 'Incorrect password'
        ]);
    }
}