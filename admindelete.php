<?php

include("db_connection.php");
$regid = $_GET['id'];
try {
    $con = new PDO($str, $user, $pass);
    // delete student_details
    $sql = "DELETE FROM student_details WHERE REGID=:REGID";
    $res = $con->prepare($sql);
    $res->execute(["REGID"=>$regid]);

    // delete student family_details
    $sql1 = "DELETE FROM student_family_details WHERE REGID=:REGID";
    $res1 = $con->prepare($sql1);
    $res1->execute(["REGID"=>$regid]);

    // delete student profile
    $sql2 = "DELETE FROM student_profileimage WHERE REGID=:REGID";
    $res2 = $con->prepare($sql2);
    $res2->execute(["REGID"=>$regid]);

    // delete student account
    $sql3 = "DELETE FROM student_registration WHERE REGID=:REGID";
    $res3 = $con->prepare($sql3);
    $res3->execute(["REGID"=>$regid]);

    // $updatesql = "UPDATE student_registration SET DELETED=:DELETED WHERE REGID=:REGID";
    // $res = $con->prepare($updatesql);
    // $res->execute(["DELETED" => 1, "REGID"=>$regid]);
    // header("Location: admindashboard.php");
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
