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
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Generar Formulario con JSON</title>

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

        <style>
            body {
                font-family: sans-serif;
                padding: 10px;
            }

            label, input, select {
                display: block;
                margin-top: 10px;
            }

            input.error, select.error {
                border: 1px solid #A94442;
            }

            label.error {
                color: #A94442;
            }
        </style>
    </head>

    <body>
        <p><?php echo $message; ?></p>
        <form id="formgenerate"></form>
    </body>
</html>