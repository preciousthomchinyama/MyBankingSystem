<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "bank_system";

$conn = mysqli_connect($host, $username, $password, $database); // connection function 

if (!$conn){
    die("Connection failed: " . mysqli_connect_error());
}