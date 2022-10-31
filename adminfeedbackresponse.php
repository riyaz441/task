<?php

include("db_connection.php");
$fbid = $_GET['id'];
$status = $_GET['admin_status'];
try {
    $con = new PDO($str, $user, $pass);
    if ($status == "Accept") {
        $sql = "UPDATE student_feedback SET ADMIN_STATUS=:ADMINSTATUS,ADMINDATE=:ADMINDATE WHERE FBID=:FBID";
        $res = $con->prepare($sql);
        $res->execute(["ADMINSTATUS" => $status, "ADMINDATE" => date("d-m-Y"), "FBID" => $fbid]);
    }
    if ($status == "Reject") {
        $sql = "UPDATE student_feedback SET ADMIN_STATUS=:ADMINSTATUS WHERE FBID=:FBID";
        $res = $con->prepare($sql);
        $res->execute(["ADMINSTATUS" => $status, "ADMINDATE" => date("d-m-Y"), "FBID" => $fbid]);
    }
    header("Location: adminfeedback.php");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
