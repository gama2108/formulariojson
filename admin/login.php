<?php
session_start();
include("../lib/db.php");
$db = new db();
$db->abrir();

$message = "Username y Password no pueden ser vacios";
$messagedisplay = "none";

if ($_POST) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $query = "SELECT * FROM `login` WHERE `username` = '$username' AND `password` = '$password'";
    $login = $db->consulta($query);
    if ($login) {
        $_SESSION['id'] = $login[0]['id'];
        $_SESSION['username'] = $login[0]['username'];
        header("Location: index.php");
    }else{
        $message = "El Username y/o Password no son correctos";
        $messagedisplay = "block";
    }
}
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Login</title>

        <!-- Bootstrap Core CSS -->
        <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="dist/css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- jQuery -->
        <script src="bower_components/jquery/dist/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="dist/js/sb-admin-2.js"></script>

        <script>
            $(document).ready(function () {
                $("#login").on("click", function () {
                    console.log("login");
                    var error = 0;
                    $.each($('.required'), function (key, value) {
                        var id = value.id;
                        var val = $("#" + id).val();
                        if (val == "") {
                            $("#" + id).addClass("error");
                            error++;
                        } else {
                            $("#" + id).removeClass("error");
                        }
                    });

                    if (error == 0) {
                        $("#message_error").hide();
                        $("#formlogin").submit();
                    } else {
                        $("#message_error").show();
                    }
                });
            });
        </script>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Ingrese sus credenciales</h3>
                        </div>
                        <div class="panel-body">
                            <form id="formlogin" role="form" action="" method="post">
                                <p id="message_error" class="errormsj" style="display: <?php echo $messagedisplay ?>;"><?php echo $message ?></p>
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control required" placeholder="Username" id="username" name="username" type="username" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control required" placeholder="Password" id="password" name="password" type="password" value="">
                                    </div>
                                    <button type="button" id="login" class="btn btn-lg btn-success btn-block">Login</button>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
