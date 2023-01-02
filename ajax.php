<!DOCTYPE html>
<html lang="en">
<?php
date_default_timezone_set('Asia/Calcutta');
require_once("db/Db.class.php");
$db = new Db();
$Get_College=$db->query("select college_name from college_profile where deleted=:del", array("del"=>0));
$cname=$Get_College[0]['college_name'];
?>
<!-- no cache headers -->
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="no-cache">
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta charset="UTF-8">
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- end no cache headers -->

<!-- bootstrap model link -->
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
</script>

<head>
    <title><?php echo ucwords($cname); ?></title>


    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/login_css/bootstrap.min1.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" type="text/css" href="css/util1.css">
    <link rel="stylesheet" type="text/css" href="css/main1.css">
    <link href="plugins/font-awesome/css/font1.css" rel="stylesheet">
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="validate_api.js"></script>
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="vendor/animsition/js/animsition.min.js"></script>
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/daterangepicker/moment.min.js"></script>
    <script src="vendor/daterangepicker/daterangepicker.js"></script>
    <script src="js/main.js"></script>

</head>

<script>
    $(document).ready(function() {
        var parameter = new Array(
            ["user_name", "custom", 'err_msg', 'Invalid User name #$#Enter Your User Name', '', '', ''],
            ["pswd", "custom", 'err_msg', 'Invalid Password #$#Enter your password', '', '', '']
        );


        // STATR ::: SEND EMAIL ::://
        $('body').on('click', '#submit_frm', function() {


            if ($('#user_name').val() != "") {
                $("#preloader1").show();
                $("#loadingImg").show();
                $("#err_msg").html();
                $.ajax({
                    type: "POST",
                    url: 'includes/forgotpasswordAjx.php',
                    data: $("#login_forms").serialize() + "&btn=forgotpassword",
                    success: function(data) {
                        if (data == 0) {
                            window.location = 'changepassword.php';
                        }
                        if (data == 1) {
                            $('#err_msg').html(
                                    "<font color='red' style='font-weight:bold'>Invalid Username!!!!</font>"
                                )
                                .show()
                                .fadeOut(2000);
                        }
                    }
                });
            } else {
                $('#err_msg').html(
                        "<font color='red' style='font-weight:bold'>Enter Username!!!!</font>")
                    .show()
                    .fadeOut(2000);
            }
            return false;

        });
        // END ::: SEND EMAIL ::://


        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $('#pswd').val();
            var x = document.getElementById("pswd");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        });


        $("#show").click(function() {

            var x = document.getElementById("pswd");

            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        });


        function SubmitFunction() {
            $('form').submit();

        }



        jQuery(document).ready(function($) {

            $(window).load(function() {
                $('#preloader').fadeOut('slow', function() {
                    $(this).remove();
                });
            });

        });


    });
</script>
<style>
    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 20px;
        height: 20px;
        -webkit-animation: spin 2s linear infinite;
        /* Safari */
        animation: spin 2s linear infinite;
    }


    /* Safari */
    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .js div#preloader {
        position: fixed;
        left: 0;
        top: 0;
        z-index: 999;
        width: 100%;
        height: 100%;
        overflow: visible;
        background: url('images/ajax-loader-3.gif') no-repeat center center;
    }
</style>

<body>


    <div class="js">

        <body>
            <span id="preloader" style="display:none;">
                <div id="preloader123"></div>
            </span>
    </div>

    <div class="limiter">
        <div class="container-login100" style="background-image: url('images/amc.jpg');">
            <div class="wrap-login100">
                <form class="login100-form validate-form" name='login_forms' id='login_forms' role='form' method="post">
                    <span class="login100-form-logo">
                        <img src="images/aclogin_logofinal.png" class="img-responsive" alt="Cinque Terre" width="133"
                            height="129">
                    </span>

                    <span class="login100-form-title p-b-34 p-t-27">
                        FORGOT PASSWORD
                    </span>


                    <div id="login_div" name="login_div">

                        <!-- rollno input -->
                        <div class="wrap-input100 validate-input" data-validate="Enter username" id="log_user">
                            <font style="color:white"><img class="avatar" src="images/login1.svg" alt=""
                                    style="width: 20px;position: absolute;top: 30%;"></font>
                            <input class="input100" type="text" name="user_name" id="user_name" placeholder="Username"
                                tabindex="1"
                                value="<?php echo isset($_REQUEST['Student']); ?>"
                                autocomplete="off">
                            <!--<span class="focus-input100" data-placeholder="&#xf207;"></span>-->
                        </div>

                        <!-- login page buttons -->
                        <div class="btn btn-group" id="log_btn">
                            <button type="" class="login100-form-btn" name="submit_frm" id="submit_frm"
                                tabindex="3">Verify</button>
                        </div>
                        <span id='loadingImg' style="margin-bottom:10px;display:none;">
                            <img src='images/loadingImg.gif'></span>
                        <div class="btn btn-group" id="log_btn1">
                            <button type="button" class="login100-form-btn" name="reset_frm" id="reset_frm"
                                tabindex="4">Reset</button>
                        </div>
                    </div>

                    <span id='err_msg'></span>

                </form>
            </div>
        </div>
    </div>

</body>

</html>