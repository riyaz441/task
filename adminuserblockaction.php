<?php

include("db_connection.php");
$rollno = $_GET['id'];
$status = $_GET['account_status'];
try {
    $con = new PDO($str, $user, $pass);
    if ($status == "Active") {
        $block = "UPDATE student_registration SET BLOCKED=:BLOCKED WHERE ROLLNO=:ROLLNO";
        $res = $con->prepare($block);
        $res->execute(["BLOCKED" => "Inactive", "ROLLNO" => $rollno]);
    }
    if ($status == "Inactive") {
        $unblock = "UPDATE student_registration SET BLOCKED=:BLOCKED WHERE ROLLNO=:ROLLNO";
        $res = $con->prepare($unblock);
        $res->execute(["BLOCKED" => "Active", "ROLLNO" => $rollno]);
    }
    header("Location: adminuserblock.php");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
