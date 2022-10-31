<?php
session_start();
if (isset($_SESSION['SESSION_USERNAME'])) {
    header("Location: admindashboard.php");
    die();
}

include("db_connection.php");

try {
    $con = new PDO($str, $user, $pass);
    $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        //main select statement for check user present or not
        $sql = "SELECT username,password FROM `admin_login` WHERE `username` = ? AND `password` = ?";
        $res = $con->prepare($sql);
        $res->execute(array($username, $password));
        $output = $res->fetchAll();
        if (!empty($_POST['rememberme'])) {
            setcookie("username", $_POST['username'], time() + 604800);
            setcookie("password", $_POST['password'], time() + 604800);
        } else {
            if (isset($_COOKIE["username"])) {
                setcookie("username", "");
            }
            if (isset($_COOKIE["password"])) {
                setcookie("password", "");
            }
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
    <title>Admin Login</title>
    <link rel="icon" type="image/x-icon" href="/sr/assets/brand/person-circle.svg">

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/sign-in/">


    <link href="/sr/assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>

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

        .form-signin input[type="text"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
</head>

<body class="text-center">

    <main class="form-signin w-100 m-auto">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <img class="mb-4" src="/sr/assets/brand/person-circle.svg" alt="" width="72" height="57">
            <h1 class="h3 mb-3 fw-normal">Admin Login</h1>

            <div class="form-floating">
                <input name="username" type="text" class="form-control" id="floatingInput" placeholder="name@example.com" value="<?php if (isset($_COOKIE["username"])) {
                                                                                                                                        echo $_COOKIE["username"];
                                                                                                                                    } ?>" required>
                <label for="floatingInput">User Name</label>
            </div>
            <div class="form-floating">
                <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" value="<?php if (isset($_COOKIE["password"])) {
                                                                                                                                    echo $_COOKIE["password"];
                                                                                                                                } ?>" required>
                <label for="floatingPassword">Password</label>
            </div>

            <div class="checkbox mb-3">
                <label>
                    <input name="rememberme" type="checkbox" <?php if (isset($_COOKIE["rollno"])) { ?> checked <?php } ?>> Remember me
                </label>
            </div>

            <button class="w-100 btn btn-lg btn-dark mb-3 mt-2" type="submit" name="submit">Login</button>
        </form>
        <?php
        if (isset($_POST['submit'])) {
            if (count($output) == 1) {
                $_SESSION['SESSION_USERNAME'] = $username;
                header("Location: admindashboard.php");
            } else {
        ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <?php echo "Login Failed"; ?>
                </div>
        <?php
            }
        }
        ?>
        <a href="forgotpassword.php" class="link-dark" style="text-decoration: none;">Forgot Password?</a>
    </main>
</body>

</html>