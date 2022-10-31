<?php

session_start();
if (!isset($_SESSION['SESSION_USERNAME'])) {
    header("Location: index.php");
    die();
}

include("db_connection.php");

if (isset($_GET['id'])) {
    $regid = $_GET['id'];
} else {
    $regid = 0;
}

try {
    $con = new PDO($str, $user, $pass);
    $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    // main join query
    $sql = "SELECT student_details.firstname, student_details.lastname, student_details.rollno, student_details.department, student_details.address, student_details.country, student_details.state, student_details.city, student_details.zip, student_details.mobile, student_details.gender, student_family_details.fathername, student_family_details.mothername, student_profileimage.profileimage FROM student_details JOIN student_family_details ON student_details.regid = student_family_details.regid JOIN student_profileimage ON student_details.regid = student_profileimage.regid WHERE student_details.regid = '$regid'";
    $res = $con->query($sql);
    while ($row = $res->fetch()) {
        $fn = $row->firstname;
        $ln = $row->lastname;
        $addr = $row->address;
        $country = $row->country;
        $state = $row->state;
        $city = $row->city;
        $zip = $row->zip;
        $mobile = $row->mobile;
        $fathername = $row->fathername;
        $mothername = $row->mothername;
        $department = $row->department;
        $profileimage = $row->profileimage;
    }
    if (isset($_POST['submit'])) {
        $regid = $_POST['gid'];
        $firstnameupdate = $_POST['firstname'];
        $lastnameupdate = $_POST['lastname'];
        $departmentupdate = $_POST['department'];
        $addressupdate = $_POST['address'];
        $countryupdate = $_POST['country'];
        $stateupdate = $_POST['state'];
        $cityupdate = $_POST['city'];
        $zipupdate = $_POST['zip'];
        $mobileupdate = $_POST['mobile'];
        $fathernameupdate = $_POST['fathername'];
        $mothernameupdate = $_POST['mothername'];


        // take country name using id
        $sql = "SELECT name FROM countries WHERE id = '$countryupdate'";
        $res = $con->query($sql);
        while ($row = $res->fetch()) {
            $countryupdate = $row->name;
        }

        // take state name using id
        $sql = "SELECT name FROM states WHERE id = '$stateupdate'";
        $res = $con->query($sql);
        while ($row = $res->fetch()) {
            $stateupdate = $row->name;
        }

        // take city name using id
        $sql = "SELECT name FROM cities WHERE id = '$cityupdate'";
        $res = $con->query($sql);
        while ($row = $res->fetch()) {
            $cityupdate = $row->name;
        }

        // take department name using id
        $sql = "SELECT department FROM department WHERE did = '$departmentupdate'";
        $res = $con->query($sql);
        while ($row = $res->fetch()) {
            $departmentupdate = $row->department;
        }


        // update student details
        $updatesql = "UPDATE student_details SET FIRSTNAME=:FIRSTNAME,LASTNAME=:LASTNAME,DEPARTMENT=:DEPARTMENT,ADDRESS=:ADDRESS,COUNTRY=:COUNTRY,STATE=:STATE,CITY=:CITY,ZIP=:ZIP,MOBILE=:MOBILE WHERE REGID=:REGID";
        $res = $con->prepare($updatesql);
        $res->execute(["FIRSTNAME" => $firstnameupdate, "LASTNAME" => $lastnameupdate, "DEPARTMENT" => $departmentupdate, "ADDRESS" => $addressupdate, "COUNTRY" => $countryupdate, "STATE" => $stateupdate, "CITY" => $cityupdate, "ZIP" => $zipupdate, "MOBILE" => $mobileupdate, "REGID" => $regid]);
        header("Location: admindashboard.php");
        // update student family Details
        $updatesql1 = "UPDATE student_family_details SET FATHERNAME=:FATHERNAME,MOTHERNAME=:MOTHERNAME WHERE REGID=:REGID";
        $res1 = $con->prepare($updatesql1);
        $res1->execute(["FATHERNAME" => $fathernameupdate, "MOTHERNAME" => $mothernameupdate, "REGID" => $regid]);

        $profileimage = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
        $size = $_FILES['file']['size'];

        if ($profileimage == 'jpg' || $profileimage == 'jpeg' || $profileimage == 'png') {
            if ($size < 3000000) {
                // update student profileimage
                $profileimage = $_FILES['file']['name'];
                $file_temp = $_FILES['file']['tmp_name'];
                $allowed_ext = array("jpeg", "jpg", "png");
                $exp = explode(".", $profileimage);
                $ext = end($exp);
                $path = "uploads/" . $profileimage;
                if (in_array($ext, $allowed_ext)) {
                    if (move_uploaded_file($file_temp, $path)) {
                        // student details insert
                        $updatesql2 = "UPDATE student_profileimage SET PROFILEIMAGE=:PROFILEIMAGE WHERE REGID=:REGID";
                        $res2 = $con->prepare($updatesql2);
                        $res2->execute(["PROFILEIMAGE" => $profileimage, "REGID" => $regid]);
                        header("Location: admindashboard.php");
                    }
                }
            } else {
                ?>
<script>
    alert("File Size is High!");
</script>
<?php
            }
        } else {
            ?>
<script>
    alert("File Not Supported");
</script>
<?php
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.101.0">
    <title>Admin Profile Update</title>
    <link rel="icon" type="image/x-icon" href="/sr/assets/brand/person-circle.svg">

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/checkout/">





    <link href="/sr/assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
    </style>


</head>

<!-- top nav bar start -->
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="admindashboard.php"> <i class="fas fa-graduation-cap"></i> TCE</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
            class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..."
                aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>
<!-- top nav bar end -->

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Admin</div>
                    <a class="nav-link" href="adminuserblock.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-lock"></i></div>
                        Access Management
                    </a>
                    <a class="nav-link" href="adminfeedback.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-comment"></i></div>
                        Feedback Management
                    </a>
                    <a class="nav-link" href="admindepartment.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-building-columns"></i></div>
                        department Management
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">&copy; Copyright</div>
                TCE
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>

            <body class="bg-light">

                <div class="container">
                    <main>
                        <div class="py-5 text-center mt-4">
                            <img class="d-block mx-auto mb-4" src="/sr/assets/brand/person-circle.svg" alt="" width="72"
                                height="57">
                            <h2>Admin Update</h2>
                        </div>


                        <div class="row g-5">
                            <div class="col-md-12">
                                <h4 class="mb-3">Admin Update</h4>
                                <form
                                    action="<?php echo $_SERVER['PHP_SELF']; ?>"
                                    method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input name="gid" type="hidden" class="form-control" id="gid"
                                                value="<?php echo $_GET['id']; ?>">
                                            <label for="firstName" class="form-label">First name</label><span
                                                style="color:red"> *</span>
                                            <input name="firstname" type="text" class="form-control" id="firstName"
                                                placeholder=""
                                                value="<?php echo $fn; ?>"
                                                required>
                                            <div class="invalid-feedback">
                                                Valid first name is required.
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="lastName" class="form-label">Last name</label><span
                                                style="color:red"> *</span>
                                            <input name="lastname" type="text" class="form-control" id="lastName"
                                                placeholder=""
                                                value="<?php echo $ln; ?>"
                                                required>
                                            <div class="invalid-feedback">
                                                Valid last name is required.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-sm-6">
                                            <label for="lastName" class="form-label">Department</label><span
                                                style="color:red"> *</span>
                                            <select name="department" class="form-select"
                                                aria-label="Default select example">
                                                <option selected
                                                    value="<?php echo $department; ?>">
                                                    <?php echo $department; ?>
                                                </option>
                                                <?php
                                                $sql = "SELECT did,department FROM department";
$res = $con->query($sql);
while ($row = $res->fetch()) {
    echo "<option value='$row->did'>$row->department</option>";
}
?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="address" class="form-label">Address</label><span
                                                style="color:red"> *</span>
                                            <input name="address" type="text" class="form-control" id="address"
                                                placeholder=""
                                                value="<?php echo $addr; ?>"
                                                required>
                                            <div class="invalid-feedback">
                                                Please enter your shipping address.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-sm-6">
                                            <label for="zip" class="form-label">Country</label><span style="color:red">
                                                *</span>
                                            <select name="country" id="country" class="form-select"
                                                aria-label="Default select example" required>
                                                <option selected
                                                    value="<?php echo $country; ?>">
                                                    <?php echo $country; ?>
                                                </option>
                                                <?php
$sql = "SELECT id,name FROM countries";
$res = $con->query($sql);
while ($row = $res->fetch()) {
    echo "<option value='$row->id'>$row->name</option>";
}
$con = null;
?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Zip code required.
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="zip" class="form-label">State</label><span style="color:red">
                                                *</span>
                                            <select name="state" id="state" class="form-select"
                                                aria-label="Default select example" required>
                                                <option selected
                                                    value="<?php echo $state; ?>">
                                                    <?php echo $state; ?>
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Zip code required.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-sm-6">
                                            <label for="zip" class="form-label">City</label><span style="color:red">
                                                *</span>
                                            <select name="city" id="city" class="form-select"
                                                aria-label="Default select example" required>
                                                <option selected
                                                    value="<?php echo $city; ?>">
                                                    <?php echo $city; ?>
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Zip code required.
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="zip" class="form-label">Zip</label><span style="color:red">
                                                *</span>
                                            <input name="zip" type="number" class="form-control mb-3" id="zip"
                                                placeholder=""
                                                value="<?php echo $zip; ?>"
                                                required>
                                            <div class="invalid-feedback">
                                                Zip code required.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-sm-6">
                                            <label for="cc-name" class="form-label">Father Name</label><span
                                                style="color:red"> *</span>
                                            <input name="fathername" type="text" class="form-control" id="cc-name"
                                                placeholder=""
                                                value="<?php echo $fathername; ?>"
                                                required>
                                            <div class="invalid-feedback">
                                                Name on card is required
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="cc-number" class="form-label">Mother Name</label><span
                                                style="color:red"> *</span>
                                            <input name="mothername" type="text" class="form-control" id="cc-number"
                                                placeholder=""
                                                value="<?php echo $mothername; ?>"
                                                required>
                                            <div class="invalid-feedback">
                                                Credit card number is required
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-sm-6">
                                            <label for="mobile" class="form-label">Mobile</label><span
                                                style="color:red"> *</span>
                                            <input name="mobile" type="text" class="form-control" id="mobile"
                                                placeholder=""
                                                value="<?php echo $mobile; ?>"
                                                required maxlength="10">
                                            <div class="invalid-feedback">
                                                Valid first name is required.
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="formFile" class="form-label">Upload Image (maximum size
                                                3mb)</label>
                                            <input name="file" class="form-control" type="file" id="formFile"
                                                accept="image/jpg,image/png">
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-sm-6">
                                            <button class=" btn btn-dark mt-5 mb-5" type="submit"
                                                name="submit">Update</button>
                                        </div>
                                        <div class="col-sm-6">

                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </main>
                </div>

                <script src="/sr/assets/dist/js/bootstrap.bundle.min.js"></script>
                <script src="js/scripts.js"></script>

                <!-- Dependent Select box -->
                <script>
                    $("#country").change(function() {
                        sid = $(this).val();
                        $.ajax({
                            type: 'POST',
                            url: 'state.php',
                            data: 'sid=' + sid,
                            success: function(html) {
                                $('#state').html(html);
                            }
                        });
                    });

                    $("#state").change(function() {
                        cid = $(this).val();
                        $.ajax({
                            type: 'POST',
                            url: 'city.php',
                            data: 'cid=' + cid,
                            success: function(html) {
                                $('#city').html(html);
                            }
                        });
                    });
                </script>

            </body>
        </main>
    </div>
</div>



</html>