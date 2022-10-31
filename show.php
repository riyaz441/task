<?php
session_start();
if (!isset($_SESSION['SESSION_ROLLNO'])) {
    header("Location: index.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="icon" type="image/x-icon" href="/sr/assets/brand/person-circle.svg">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="/sr/printThis.js"></script>
    <script src="/sr/custom.js"></script>

    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
</head>

<!-- top nav bar start -->
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="show.php"> <i class="fas fa-graduation-cap"></i> TCE</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
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
                    <div class="sb-sidenav-menu-heading">Student</div>
                    <a class="nav-link" href="profileupdate.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-pen"></i></div>
                        Profile Update
                    </a>
                    <a class="nav-link" href="studentfeedback.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-comment"></i></div>
                        Student Feedback
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

            <body>
                <?php

                include("db_connection.php");

                $regid = $_SESSION['SESSION_REGID'];

                try {
                    $con = new PDO($str, $user, $pass);
                    $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                    // main join query
                    $sql = "SELECT student_details.firstname, student_details.lastname, student_details.rollno, student_details.department, student_details.address, student_details.country, student_details.state, student_details.city, student_details.zip, student_details.mobile, student_details.gender, student_family_details.fathername, student_family_details.mothername, student_profileimage.profileimage FROM student_details JOIN student_family_details ON student_details.regid = student_family_details.regid JOIN student_profileimage ON student_details.regid = student_profileimage.regid WHERE student_details.regid = '$regid'";
                    $res = $con->query($sql);
                    while ($row = $res->fetch()) {
                        //echo $row->firstname;
                ?>
                        <div class="container mt-5 pt-2" id="pdf">

                            <table class="table table-bordered border-dark">

                                <tbody>
                                    <h1 class="text-center mt-2">TCE</h1>
                                    <tr>
                                        <td colspan="2">
                                            <h5 class="text-center">Name: <strong><?php echo $row->firstname; ?>
                                                    <?php echo $row->lastname; ?></strong>
                                            </h5>
                                        </td>
                                        <td><img src="/sr/uploads/<?php echo $row->profileimage; ?>" class="mx-auto d-block rounded" alt="Student Profile Image" width="150" height="150" onclick="myFunction(this);"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Roll Number:
                                            </h5>
                                        </td>
                                        <td colspan="2">
                                            <h5><strong><?php echo $row->rollno; ?></strong>
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Department:
                                            </h5>
                                        </td>
                                        <td colspan="2">
                                            <h5><strong><?php echo $row->department; ?></strong>
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Address:
                                            </h5>
                                        </td>
                                        <td colspan="2">
                                            <h5><strong><?php echo $row->address; ?></strong>
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>City:
                                            </h5>
                                        </td>
                                        <td colspan="2">
                                            <h5><strong><?php echo $row->city; ?></strong>
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>State:
                                            </h5>
                                        </td>
                                        <td colspan="2">
                                            <h5><strong><?php echo $row->state; ?></strong>
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Country:
                                            </h5>
                                        </td>
                                        <td colspan="2">
                                            <h5><strong><?php echo $row->country; ?></strong>
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Zip:
                                            </h5>
                                        </td>
                                        <td colspan="2">
                                            <h5><strong><?php echo $row->zip; ?></strong>
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Mobile:
                                            </h5>
                                        </td>
                                        <td colspan="2">
                                            <h5><strong><?php echo $row->mobile; ?></strong>
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Gender:
                                            </h5>
                                        </td>
                                        <td colspan="2">
                                            <h5><strong><?php echo $row->gender; ?></strong>
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Father Name:
                                            </h5>
                                        </td>
                                        <td colspan="2">
                                            <h5><strong><?php echo $row->fathername; ?></strong>
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Mother Name:
                                            </h5>
                                        </td>
                                        <td colspan="2">
                                            <h5><strong><?php echo $row->mothername; ?></strong>
                                            </h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <br>
                <?php
                    }
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
                $con = null;
                ?>





                <div class="container">
                    <button type="button" class="btn btn-dark mb-4" id="download">Download</button>
                </div>

                <div class="container" style="display: none;">
                    <span onclick="this.parentElement.style.display='none'" class="closebtn">&times;</span>
                    <img id="expandedImg" style="width:50%">
                    <div id="imgtext"></div>
                </div>

                <script>
                    function myFunction(imgs) {
                        var expandImg = document.getElementById("expandedImg");
                        var imgText = document.getElementById("imgtext");
                        expandImg.src = imgs.src;
                        imgText.innerHTML = imgs.alt;
                        expandImg.parentElement.style.display = "block";
                    }
                </script>

                <script src="js/scripts.js"></script>
            </body>
        </main>
    </div>
</div>



</html>