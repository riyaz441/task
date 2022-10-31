<?php

session_start();
if (!isset($_SESSION['SESSION_ROLLNO'])) {
    header("Location: index.php");
    die();
}
include("db_connection.php");

$regid = $_SESSION['SESSION_REGID'];

try {
    $con = new PDO($str, $user, $pass);
    $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $mobile = $_POST['mobile'];
        $comment = $_POST['comment'];

        // check comment empty or not
        if (!empty($comment)) {

            // send mail function
            $to_email = "rm15324950@gmail.com";
            $subject = "Feedback Mail";
            $body = $comment;
            $headers = "From: $name";

            if (mail($to_email, $subject, $body, $headers)) {
                echo
                "
                <script>
                alert('Mail sent...');
                </script>
                ";
            } else {
                echo
                "
                <script>
                alert('Mail not sent...');
                </script>
                ";
            }

            // main insert query
            $sql1 = "INSERT INTO `student_feedback` (`regid`,`name`,`mobile`,`comment`) VALUES (:REGID,:NAME,:MOBILE,:COMMENT)";
            $res1 = $con->prepare($sql1);
            $res1->execute(["REGID" => $regid, "NAME" => $name, "MOBILE" => $mobile, "COMMENT" => $comment]);
        } else {
?>
            <script>
                alert("Comment box is empty!");
            </script>
<?php
        }
    }
    // main select query
    $sql = "SELECT student_feedback.comment,student_feedback.date,student_feedback.admin_status FROM `student_feedback` WHERE `regid` = '$regid'";
    $res = $con->query($sql);

    // main student name and mobile number prefill query
    $sql2 = "SELECT firstname, lastname, mobile from `student_details` WHERE `regid` = '$regid'";
    $res2 = $con->query($sql2);
    while ($row = $res2->fetch()) {
        $fn = $row->firstname;
        $ln = $row->lastname;
        $mobile = $row->mobile;
    }
} catch (Exception $e) {
    "Error: " . $e->getMessage();
}
$con = null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Feedback</title>
    <link rel="icon" type="image/x-icon" href="/sr/assets/brand/person-circle.svg">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>

    <!-- print page(download) -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="/sr/printThis.js"></script>
    <script src="/sr/custom.js"></script>

    <!-- data table cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">

    <!-- fontawesome icon -->
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

    <!-- rich text editor -->
    <script src="//cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>

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
                    <a class="nav-link" href="show.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <a class="nav-link" href="profileupdate.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-pen"></i></div>
                        Profile Update
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

                <form action="" method="POST" id="form">
                    <div class="container mt-5">
                        <div class="row">
                            <h3 class="text-center mb-3">Student Feedback</h3>
                            <div class="col-sm-6">
                                <label for="name" class="form-label">Student Name</label><span style="color:red">
                                    *</span>
                                <input name="name" type="text" class="form-control" id="name" aria-describedby="emailHelp" value="<?php echo $fn . " " . $ln; ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="mobile" class="form-label">Mobile</label><span style="color:red"> *</span>
                                <input name="mobile" type="number" class="form-control" id="mobile" aria-describedby="emailHelp" value="<?php echo $mobile; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="container mt-3">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="comment">Comments</label><span style="color:red"> *</span>
                                <textarea name="comment" class="form-control" id="comment" value="" required></textarea>
                            </div>
                        </div>
                        <button name="submit" type="submit" id="button" class="btn btn-dark mt-3">Send</button>
                    </div>
                </form>

                <h3 class="text-center mt-3">Student Feedback Table</h3>
                <div class="container mt-5 mb-5" id="pdf">
                    <table id="example" class="table table-striped" style="width:100%">
                        <thead>
                            <tr class="bg-secondary">
                                <th>Comment</th>
                                <th>Submitted Date</th>
                                <th>Admin Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            while ($row = $res->fetch()) {
                                echo '
                <tr>
                    <td>' . $row->comment . '</td>
                    <td>' . date("d-m-Y", strtotime($row->date)) . '</td>
                    <td>' . $row->admin_status . '</td>
                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>

                </div>

                <!-- data table js cdn -->
                <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
                <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
                <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
                <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
                <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
                <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
                <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
                <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>

                <!-- main datatable cdn -->


                <script>
                    $(document).ready(function() {
                        $('#example').DataTable({
                            dom: 'Bfrtip',
                            buttons: [{
                                    extend: 'copyHtml5',
                                    exportOptions: {
                                        columns: [0, ':visible']
                                    }
                                },
                                {
                                    extend: 'excelHtml5',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'pdfHtml5',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'print',
                                    exportOptions: {
                                        columns: [0, ':visible']
                                    }
                                },
                                'colvis'
                            ]
                        });
                    });
                </script>
            </body>
        </main>
    </div>
</div>

<script src="js/scripts.js"></script>

<script type="text/javascript">
    // Initialize CKEditor

    CKEDITOR.replace('comment', {
        height: "200px"
    });
</script>


</html>