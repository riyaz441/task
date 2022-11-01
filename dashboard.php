<?php
session_start();
if (!isset($_SESSION['SESSION_ROLLNO'])) {
    header("Location: index.php");
    die();
}

include("db_connection.php");

try {
    $con = new PDO($str, $user, $pass);

    if (isset($_POST['submit'])) {
        $regid = $_SESSION['SESSION_REGID'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $rollno = $_POST['rollno'];
        $department = $_POST['department'];
        $address = $_POST['address'];
        $country = $_POST['country'];
        $state = $_POST['state'];
        $city = $_POST['city'];
        $zip = $_POST['zip'];
        $mobile = $_POST['mobile'];
        $gender = $_POST['gender'];
        $fathername = $_POST['fathername'];
        $mothername = $_POST['mothername'];

        // take country name using id
        $sql = "SELECT name FROM countries WHERE id = '$country'";
        $res = $con->query($sql);
        while ($row = $res->fetch()) {
            $country = $row["name"];
        }

        // take state name using id
        $sql = "SELECT name FROM states WHERE id = '$state'";
        $res = $con->query($sql);
        while ($row = $res->fetch()) {
            $state = $row["name"];
        }

        // take city name using id
        $sql = "SELECT name FROM cities WHERE id = '$city'";
        $res = $con->query($sql);
        while ($row = $res->fetch()) {
            $city = $row["name"];
        }

        // take department name using id
        $sql = "SELECT department FROM department WHERE did = '$department'";
        $res = $con->query($sql);
        while ($row = $res->fetch()) {
            $department = $row["department"];
        }


        $profileimage = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
        $size = $_FILES['file']['size'];

        if ($profileimage == 'jpg' || $profileimage == 'jpeg' || $profileimage == 'png') {
            if ($size < 3000000) {
                // student details insert
                $sql = "INSERT INTO `student_details` (`regid`,`firstname`,`lastname`,`rollno`,`department`,`address`,`country`,`state`,`city`,`zip`,`mobile`,`gender`) VALUES (:REGID,:FIRSTNAME,:LASTNAME,:ROLLNO,:DEPARTMENT,:ADDRESS,:COUNTRY,:STATE,:CITY,:ZIP,:MOBILE,:GENDER)";
                $res = $con->prepare($sql);
                $res->execute(["REGID" => $regid, "FIRSTNAME" => $firstname, "LASTNAME" => $lastname, "ROLLNO" => $rollno, "DEPARTMENT" => $department, "ADDRESS" => $address, "COUNTRY" => $country, "STATE" => $state, "CITY" => $city, "ZIP" => $zip, "MOBILE" => $mobile, "GENDER" => $gender]);
                // student family details insert
                $sql1 = "INSERT INTO `student_family_details` (`regid`,`fathername`,`mothername`) VALUES (:REGID,:FATHERNAME,:MOTHERNAME)";
                $res1 = $con->prepare($sql1);
                $res1->execute(["REGID" => $regid, "FATHERNAME" => $fathername, "MOTHERNAME" => $mothername]);

                // image upload
                $profileimage = $_FILES['file']['name'];
                $file_temp = $_FILES['file']['tmp_name'];
                $allowed_ext = array("jpeg", "jpg", "png");
                $exp = explode(".", $profileimage);
                $ext = end($exp);
                $path = "uploads/" . $profileimage;
                if (in_array($ext, $allowed_ext)) {
                    if (move_uploaded_file($file_temp, $path)) {
                        // student profile details insert
                        $sql2 = "INSERT INTO `student_profileimage` (`regid`,`profileimage`) VALUES (:REGID,:PROFILEIMAGE)";
                        $res2 = $con->prepare($sql2);
                        $res2->execute(["REGID" => $regid, "PROFILEIMAGE" => $profileimage]);
                    }
                }
                // go to show page
                header("Location: show.php");
                // update new user to old user
                $updatesql = "UPDATE student_registration SET SAVEFORM=:SAVEFORM WHERE ROLLNO=:ROLLNO";
                $res = $con->prepare($updatesql);
                $res->execute(["SAVEFORM" => 1, "ROLLNO" => $_SESSION['SESSION_ROLLNO']]);
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
    $errorMessage = "Error: " . $e->getMessage();
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
  <title>Registeration</title>
  <link rel="icon" type="image/x-icon" href="/sr/assets/brand/person-circle.svg">

  <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/checkout/">

  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

  <!-- JavaScript Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
  </script>

  <link href="/sr/assets/dist/css/bootstrap.min.css" rel="stylesheet">

  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

  <!-- jquery cdn -->
  <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>

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
  <a class="navbar-brand ps-3" href="dashboard.php"> <i class="fas fa-graduation-cap"></i> TCE</a>
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

<!-- side nav bar start -->
<div id="layoutSidenav">
  <div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
      <div class="sb-sidenav-menu">
        <div class="nav">
          <div class="sb-sidenav-menu-heading">Student</div>
          <a class="nav-link" href="dashboard.php">
            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
            Dashboard
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

      <body class="bg-light" oncontextmenu="return false;">

        <div class="container mt-3">
          <main>
            <div class="py-5 text-center">
              <h2>Profile Form</h2>

              <?php
?>
              <button type="button" class="btn btn-info disabled ms-2">Roll No:
                <?php echo $_SESSION['SESSION_ROLLNO']; ?></button>
              <?php
?>
            </div>


            <div class="row g-5">
              <div class="col-md-12">
                <form
                  action="<?php echo $_SERVER['PHP_SELF']; ?>"
                  method="post" enctype="multipart/form-data">

                  <!--form started-->

                  <div class="row">
                    <div class="col-sm-6">
                      <label for="firstName" class="form-label">First name</label><span style="color:red"> *</span>
                      <input name="firstname" type="text" class="form-control" id="firstName" placeholder="" value=""
                        required>
                      <div class="invalid-feedback">
                        Valid first name is required.
                      </div>
                    </div>

                    <div class="col-sm-6">
                      <label for="lastName" class="form-label">Last name</label><span style="color:red"> *</span>
                      <input name="lastname" type="text" class="form-control" id="lastName" placeholder="" value=""
                        required>
                      <div class="invalid-feedback">
                        Valid last name is required.
                      </div>
                    </div>
                  </div>

                  <div class="row mt-2">
                    <div class="col-sm-6">
                      <label for="lastName" class="form-label">Roll No</label><span style="color:red"> *</span>
                      <input name="rollno" type="text" class="form-control" id="lastName" placeholder=""
                        value="<?php echo $_SESSION['SESSION_ROLLNO']; ?>"
                        required>
                      <div class="invalid-feedback">
                        Valid last name is required.
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="lastName" class="form-label">Department</label><span style="color:red"> *</span>
                      <select name="department" class="form-select" aria-label="Default select example">
                        <option selected selected disabled hidden>Select Department</option>
                        <?php
          $sql = "SELECT did,department FROM department";
$res = $con->query($sql);
while ($row = $res->fetch()) {
    echo "<option value='{$row["did"]}'>{$row["department"]}</option>";
}
?>
                      </select>
                    </div>
                  </div>

                  <div class="row mt-2">
                    <div class="col-sm-6">
                      <label for="address" class="form-label">Address</label><span style="color:red"> *</span>
                      <input name="address" type="text" class="form-control" id="address" placeholder="" required>
                      <div class="invalid-feedback">
                        Please enter your shipping address.
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="country" class="form-label">Country</label><span style="color:red"> *</span>
                      <select name="country" id="country" class="form-select" aria-label="Default select example">
                        <option selected selected disabled hidden>Select Country</option>
                        <?php
$sql = "SELECT id,name FROM countries";
$res = $con->query($sql);
while ($row = $res->fetch()) {
    echo "<option value='{$row["id"]}'>{$row["name"]}</option>";
}
$con = null;
?>
                      </select>
                    </div>
                  </div>

                  <div class="row mt-2">
                    <div class="col-sm-6">
                      <label for="state" class="form-label">State</label><span style="color:red"> *</span>
                      <select name="state" id="state" class="form-select" aria-label="Default select example">
                        <option selected selected disabled hidden>Select the State</option>
                      </select>
                    </div>
                    <div class="col-sm-6">
                      <label for="city" class="form-label">City</label><span style="color:red"> *</span>
                      <select name="city" id="city" class="form-select" aria-label="Default select example">
                        <option selected selected disabled hidden>Select the City</option>
                      </select>
                    </div>
                  </div>

                  <div class="row mt-2">
                    <div class="col-sm-6">
                      <label for="zip" class="form-label">Zip</label><span style="color:red"> *</span>
                      <input name="zip" type="number" class="form-control mb-3" id="zip" placeholder="" required>
                      <div class="invalid-feedback">
                        Zip code required.
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="mobile" class="form-label">Mobile</label><span style="color:red"> *</span>
                      <input name="mobile" type="text" class="form-control" id="mobile" placeholder="" required
                        maxlength="10">
                      <div class="invalid-feedback">
                        Valid first name is required.
                      </div>
                    </div>
                  </div>

                  <div class="row mt-2">
                    <div class="col-sm-6">
                      <label class="form-label">Gender</label><span style="color:red"> *</span>
                      <div class="form-check">
                        <input name="gender" value="male" id="credit" name="paymentMethod" type="radio"
                          class="form-check-input" required>
                        <label class="form-check-label" for="credit">Male</label>
                      </div>
                      <div class="form-check">
                        <input name="gender" value="female" id="debit" name="paymentMethod" type="radio"
                          class="form-check-input" required>
                        <label class="form-check-label" for="debit">Female</label>
                      </div>
                      <div class="form-check">
                        <input name="gender" value="transgender" id="debit" name="paymentMethod" type="radio"
                          class="form-check-input" required>
                        <label class="form-check-label" for="debit">Transgender</label>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="cc-name" class="form-label">Father Name</label><span style="color:red"> *</span>
                      <input name="fathername" type="text" class="form-control" id="cc-name" placeholder="" required>
                      <div class="invalid-feedback">
                        Name on card is required
                      </div>
                    </div>
                  </div>

                  <div class="row mt-2">
                    <div class="col-sm-6">
                      <label for="cc-number" class="form-label">Mother Name</label><span style="color:red"> *</span>
                      <input name="mothername" type="text" class="form-control" id="cc-number" placeholder="" required>
                      <div class="invalid-feedback">
                        Credit card number is required
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="formFile" class="form-label">Upload Image <span style="color:red"> *</span> (maximum
                        size
                        3mb)</label>
                      <input name="file" class="form-control" type="file" id="formFile" accept="image/jpg,image/png"
                        required>
                      <label class="mt-3">Image Preview: </label>
                      <div id="selectedBanner" class="mt-2 float-right"></div>
                    </div>
                  </div>

                  <div class="row mt-2">
                    <div class="col-sm-6">
                      <button class=" btn btn-dark mt-5 mb-5" type="submit" name="submit">Save</button>
                    </div>
                  </div>
                  <!--form end-->

                </form>
              </div>
            </div>
          </main>
        </div>
      </body>
    </main>
  </div>
</div>
<!-- side nav bar end -->


<script src="js/scripts.js"></script>

<!-- thumbnil image show code -->

<script>
  var selDiv = "";
  var storedFiles = [];
  $(document).ready(function() {
    $("#formFile").on("change", handleFileSelect);
    selDiv = $("#selectedBanner");
  });

  function handleFileSelect(e) {
    var files = e.target.files;
    var filesArr = Array.prototype.slice.call(files);
    filesArr.forEach(function(f) {
      if (!f.type.match("image.*")) {
        return;
      }
      storedFiles.push(f);

      var reader = new FileReader();
      reader.onload = function(e) {
        var html =
          '<img src="' +
          e.target.result +
          "\" data-file='" +
          f.name +
          "' class='avatar rounded lg' alt='Category Image' height='75px' width='75px'>";
        selDiv.html(html);
      };
      reader.readAsDataURL(f);
    });
  }
</script>

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

<!-- block the inspect element -->
<script>
  document.onkeydown = function(e) {

    if (event.keyCode == 123) {
      return false;
    }

    if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
      return false;
    }

    if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
      return false;
    }

    if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
      return false;
    }

  }
</script>


</html>