<?php

include("db_connection.php");
$con = new PDO($str, $user, $pass);
$con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

$sql = "SELECT * FROM cities WHERE state_id = {$_POST["cid"]}";
$res = $con->query($sql);
echo "<option value=''>Select the City</option>";
while ($row = $res->fetch()) {
    echo "<option value='$row->id'>$row->name</option>";
}
$con = null;
