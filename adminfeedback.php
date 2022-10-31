<?php
session_start();
if (!isset($_SESSION['SESSION_USERNAME'])) {
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
    <title>Admin Feedback Management</title>
    <link rel="icon" type="image/x-icon" href="/sr/assets/brand/person-circle.svg">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>

    <!-- data table cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">



    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="/sr/printThis.js"></script>
    <script src="/sr/custom.js"></script>

    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
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
                    <a class="nav-link" href="admindepartment.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-lock"></i></div>
                        Department Management
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


try {
    $con = new PDO($str, $user, $pass);
    $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    // main join query
    $sql = "SELECT student_feedback.fbid,student_feedback.name,student_feedback.mobile,student_feedback.comment,student_feedback.admin_status,student_feedback.date,student_feedback.admindate FROM student_feedback";
    $res = $con->query($sql);
    $res->execute();
    ?>
                <h3 class="text-center mt-3">Feedback Management</h3>
                <div class="container mt-5" id="pdf">

                    <table id="example" class="table table-striped" style="width:100%">
                        <thead>
                            <tr class="bg-secondary">
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Comments</th>
                                <th>Student Submitted Date</th>
                                <th>Admin Response Date</th>
                                <th>Feedback Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                    while ($row = $res->fetch()) {
                        ?>
                            <tr>
                                <td><?php echo $row->name ?>
                                </td>
                                <td><?php echo $row->mobile ?>
                                </td>
                                <td><?php echo $row->comment ?>
                                </td>
                                <td><?php echo date("d-m-Y", strtotime($row->date)) ?>
                                </td>
                                <td><?php echo $row->admindate ?>
                                </td>
                                <td><?php echo $row->admin_status ?>
                                </td>
                                <td><?php
                                    if ($row->admin_status == "") {
                                        echo '<a href="adminfeedbackresponse.php?id=' . $row->fbid . '&admin_status=Accept"><button name="a" type="button" class="btn btn-success btn-sm" value="" id="accept">Accept</button></a>
                                            <a href="adminfeedbackresponse.php?id=' . $row->fbid . '&admin_status=Reject"><button name="r" type="button" class="btn btn-danger btn-sm" value="" id="reject">Reject</button></a>';
                                    }
                                    if ($row->admin_status == "Accept") {
                                        echo '<button name="a" type="button" class="btn btn-success btn-sm" value="" id="accept" disabled>Accept</button>
                                            <a href="adminfeedbackresponse.php?id=' . $row->fbid . '&admin_status=Reject"><button name="r" type="button" class="btn btn-danger btn-sm" value="" id="reject">Reject</button></a>';
                                    }
                                    if ($row->admin_status == "Reject") {
                                        echo '<a href="adminfeedbackresponse.php?id=' . $row->fbid . '&admin_status=Accept"><button name="a" type="button" class="btn btn-success btn-sm" value="" id="accept" >Accept</button></a>
                                            <button name="r" type="button" class="btn btn-danger btn-sm" value="" id="reject" disabled>Reject</button>';
                                    }
                        ?>
                                </td>
                            </tr>
                            <?php
                    }
    ?>
                        </tbody>
                    </table>

                </div>
                <br>
                <?php
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
$con = null;
?>



                <div class="container" style="display: none;">
                    <span onclick="this.parentElement.style.display='none'" class="closebtn">&times;</span>
                    <img id="expandedImg" style="width:50%">
                    <div id="imgtext"></div>
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
                <script src="js/scripts.js"></script>
            </body>
        </main>
    </div>
</div>

</html>