<?php

include("db_connection.php");
$con = new PDO($str, $user, $pass);
$con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

$sql = "SELECT * FROM states WHERE country_id = {$_POST["sid"]}";
$res = $con->query($sql);
echo "<option value=''>Select the State</option>";
while ($row = $res->fetch()) {
    echo "<option value='$row->id'>$row->name</option>";
}
$con = null;
