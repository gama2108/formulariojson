<?php
include("lib/db.php");
$db = new db();
$db->abrir();

$message = "";
if ($_POST) {
    $querycol = "SHOW COLUMNS FROM user";
    $namecolumns = $db->consulta($querycol);
    $columns = array();
    $sql = "INSERT INTO `user` (";
    $fields = "";
    for ($i = 0; $i < count($namecolumns); $i++) {
        if ($namecolumns[$i]['Field'] != 'uid') {
            $type = explode("(", $namecolumns[$i]['Type']);
            $columns[$namecolumns[$i]['Field']] = $type[0];
            $fields .= "`" . $namecolumns[$i]['Field'] . "`,";
        }
    }
    $fields = trim($fields, ',');
    $sql .= $fields . ") VALUES (";

    $val = "";
    foreach ($_POST as $key => $value) {
        if ($columns[$key] == 'int') {
            if ($value != "") {
                $val .= "$value,";
            } else {
                $val .= "'',";
            }
        } else if ($columns[$key] == 'tinyint') {
            if ($value == "si") {
                $val .= "1,";
            } else {
                $val .= "0,";
            }
        } else {
            $val .= "'$value',";
        }
    }
    $val = trim($val, ',');
    $sql .= $val . ");";

    if ($db->sql($sql)) {
        $message = "Datos guardados";
    }
}
?>
<!DOCTYPE HTML>
<!--
        Strata by HTML5 UP
        html5up.net | @n33co
        Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Formulario</title>
        <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
        <link rel="stylesheet" href="css/core.css" />
        <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->

        <!-- jQuery UI CSS -->
        <link href="admin/js/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet">

        <!-- jQuery -->
        <script src="admin/bower_components/jquery/dist/jquery.min.js"></script>

        <!-- jQuery UI -->
        <script src="admin/js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>

        <!-- jQuery Validate -->
        <script src="admin/js/jquery-validation-1.13.1/dist/jquery.validate.js"></script>

        <!-- jQuery dForm -->
        <script src="admin/js/jquery.dform-1.1.0.js"></script>

        <script type="text/javascript">
            $(function () {
                // Generate a form
                $("#formgenerate").dform('http://localhost/formulariojson/admin/form.json', function (data) {
                    this //-> Generated $('#myform')
                    data //-> data from path/to/form.json
                });
            });
        </script>
    </head>
    <body id="top">

        <!-- Header -->
        <header id="header">
            <form id="formgenerate">
                <h1>Formulario Generado</h1>
                <p><?php echo $message; ?></p>
            </form>
        </header>

        <!-- Main -->
        <div id="main">

            <!-- One -->
            <section id="one">
                <header class="major">
                    <h2>Ipsum lorem dolor aliquam ante commodo<br />
                        magna sed accumsan arcu neque.</h2>
                </header>
                <p>Accumsan orci faucibus id eu lorem semper. Eu ac iaculis ac nunc nisi lorem vulputate lorem neque cubilia ac in adipiscing in curae lobortis tortor primis integer massa adipiscing id nisi accumsan pellentesque commodo blandit enim arcu non at amet id arcu magna. Accumsan orci faucibus id eu lorem semper nunc nisi lorem vulputate lorem neque cubilia.</p>

            </section>
        </div>


        <!-- Scripts -->
        <!--<script src="js/jquery.min.js"></script>-->
        <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->

    </body>
</html>