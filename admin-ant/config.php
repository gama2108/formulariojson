<?php include("header.php"); ?>

<?php
$displayform = 'block';
$displayjson = 'none';
$json = '';

if ($_POST) {
    $displayform = 'none';
    $displayjson = 'block';

    //arreglo para formar el JSON
    $arr_json['action'] = "";
    $arr_json['method'] = "post";
    $arr_json['html'][] = array('type' => 'p', 'html' => 'Mi formulario');

    $query = "CREATE TABLE IF NOT EXISTS `user` (
                `uid` int(11) NOT NULL AUTO_INCREMENT,
                ";
    for ($i = 0; $i < count($_POST['names']); $i++) {
        $temp = array();
        $names = $_POST['names'][$i];
        $labels = $_POST['labels'][$i];

        //type
        if ($_POST['types'][$i] == 'date') {
            $temp['type'] = "text";
            $temp['name'] = $names;
            $temp['id'] = $names;
            $temp['caption'] = $labels;
            $temp['datepicker']['minDate'] = "+1";

            $types = $_POST['types'][$i];
        } else if ($_POST['types'][$i] == 'boolean') {
            $temp['type'] = "radiobuttons";
            $temp['name'] = $names;
            $temp['id'] = $names;
            $temp['caption'] = $labels;
            $temp['options']["0"] = "No";
            $temp['options']["1"] = "Si";

            $types = 'tinyint(1)';
        } else {
            $longs = $_POST['longs'][$i];
            if ($_POST['types'][$i] == 'email' || $_POST['types'][$i] == 'multiple') {
                if ($_POST['types'][$i] == 'email') {
                    $temp['type'] = "email";
                    $temp['name'] = $names;
                    $temp['id'] = $names;
                    $temp['caption'] = $labels;
                    $temp['placeholder'] = $labels;
                } else {
                    $temp['type'] = "select";
                    $temp['name'] = $names;
                    $temp['id'] = $names;
                    $temp['caption'] = $labels;
                    $temp['options'][""] = "--Seleccione uno--";
                    $temp['options']["F"] = "Femenino";
                    $temp['options']["M"] = "Masculino";
                }
                $type = 'varchar';
            } else {
                if ($_POST['types'][$i] == 'int') {
                    $temp['type'] = "number";
                } else {
                    $temp['type'] = "text";
                }
                $temp['name'] = $names;
                $temp['id'] = $names;
                $temp['caption'] = $labels;
                $temp['placeholder'] = $labels;

                $type = $_POST['types'][$i];
            }
            $types = $type . "($longs)";
        }

        //null
        if ($_POST['nulos'][$i] == 'SI') {
            $nulos = "DEFAULT NULL";
        } else {
            $temp['class'] = "required";

            $nulos = "NOT NULL";
        }
        $query .= "`$names` $types $nulos,
                ";

        $arr_json['html'][] = $temp;
    }
    $query .= "PRIMARY KEY (`uid`)
            )";

    $arr_json['html'][] = array('type' => 'submit', 'html' => 'Enviar');
    $json = json_encode($arr_json);
    //var_dump($query);
    //echo $json;
    $file = 'form.json';
    file_put_contents($file, $json);
    $resp = $db->sql($query);
}
?>
<style>
    .error {
        border: 1px solid #A94442;
        /*box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset;*/
    }
</style>
<script>
    $(document).ready(function () {
        var num = 2;

        $("#add_field").on("click", function () {
            var html = '<div class="form-group form-config" id="form-group_' + num + '">' +
                    '<div class="field-group">' +
                    '<label>Nombre del campo</label>' +
                    ' <input placeholder="Nombre" name="names[]" id="name_' + num + '" class="form-field required text">' +
                    '<p class="help-block">Debe ir sin espacios y solo letras</p>' +
                    '</div>' +
                    '<div class="field-group">' +
                    '<label>Etiqueta del campo</label>' +
                    ' <input placeholder="Etiqueta" name="labels[]" id="label_' + num + '" class="form-field required">' +
                    '<p class="help-block">Etiqueta que se muestra en el formulario</p>' +
                    '</div>' +
                    '<div class="field-group">' +
                    '<label>Tipo</label>' +
                    ' <select name="types[]" id="type_' + num + '" class="form-field type">' +
                    '<option value="int">Num&eacute;rico</option>' +
                    '<option value="varchar">Texto</option>' +
                    '<option value="email">Email</option>' +
                    '<option value="multiple">M&uacute;ltiples opciones</option>' +
                    '<option value="date">Fecha</option>' +
                    '<option value="boolean">Verdadero/Falso</option>' +
                    '</select>' +
                    '<p class="help-block">El tipo del campo</p>' +
                    '</div>' +
                    '<div class="field-group">' +
                    '<label>Longitud de valores</label>' +
                    ' <input placeholder="Longitud" name="longs[]" id="long_' + num + '" class="form-field required number">' +
                    '<p class="help-block">El n&uacute;mero m&aacute;ximo de caracteres</p>' +
                    '</div>' +
                    '<div class="field-group">' +
                    '<label>Nulo</label>' +
                    ' <select name="nulos[]" id="nulo_' + num + '" class="form-field">' +
                    '<option value="SI">Si</option>' +
                    '<option value="NO">No</option>' +
                    '</select>' +
                    '<p class="help-block">Si el campo puede ser vacio o no</p>' +
                    '</div>' +
                    '<div class="field-group delete-field-group">' +
                    '<button type="button" class="btn btn-danger delete-field" id="delete-field_' + num + '">Eliminar</button>' +
                    '</div>' +
                    '</div>';
            $("#container_fields").append(html);
            num++;

            //solo numeros
            $(".number").validCampoFranz('0123456789');

            //solo texto
            $('.text').validCampoFranz('abcdefghijklmnñopqrstuvwxyz');
        });

        $("#save").on("click", function () {
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
                $("#formjson").submit();
            } else {
                $("#message_error").show();
            }
        });

        //solo numeros
        $(".number").validCampoFranz('0123456789');

        //solo texto
        $('.text').validCampoFranz('abcdefghijklmnñopqrstuvwxyz');

        $(document).on('click', '.delete-field', function () {
            var attrid = $(this).attr('id');
            var id = attrid.split('_');
            $("#form-group_" + id[1]).remove();
            num--;
        });

        $(document).on('change', '.type', function () {
            var val = $(this).val();
            var attrid = $(this).attr('id');
            var id = attrid.split('_');
            if (val == 'date' || val == 'boolean') {
                $('#long_' + id[1]).removeClass("required");
                $('#long_' + id[1]).removeClass("error");
                $('#long_' + id[1]).val("");
                $('#long_' + id[1]).attr('disabled', 'disabled');
            } else {
                $('#long_' + id[1]).removeAttr('disabled');
                $('#long_' + id[1]).addClass("required");
            }
        });
    });
</script>
<div id="page-wrapper">
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Configuraci&oacute;n
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="index.php">Inicio</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-edit"></i> Configuraci&oacute;n
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="row" style="display: <?php echo $displayform ?>;">
            <div class="col-lg-12">
                <p>Por favor ingrese los datos solicitados a continuaci&oacute;n para la generaci&oacute;n de los campos del formulario:</p>
                <div class="alert alert-danger" id="message_error" style="display: none;">
                    Por favor ingresar los datos requeridos.
                </div>
                <form role="form" action="" method="POST" id="formjson">
                    <div id="container_fields">
                        <div class="form-group form-config" id="form-group_1">
                            <div class="field-group">
                                <label>Nombre del campo</label>
                                <input placeholder="Nombre" name="names[]" id="name_1" class="form-field required text">
                                <p class="help-block">Debe ir sin espacios y solo letras</p>
                            </div>
                            <div class="field-group">
                                <label>Etiqueta del campo</label>
                                <input placeholder="Etiqueta" name="labels[]" id="label_1" class="form-field required">
                                <p class="help-block">Etiqueta que se muestra en el formulario</p>
                            </div>
                            <div class="field-group">
                                <label>Tipo</label>
                                <select name="types[]" id="type_1" class="form-field type">
                                    <option value="int">Num&eacute;rico</option>
                                    <option value="varchar">Texto</option>
                                    <option value="email">Email</option>
                                    <option value="multiple">M&uacute;ltiples opciones</option>
                                    <option value="date">Fecha</option>
                                    <option value="boolean">Verdadero/Falso</option>
                                </select>
                                <p class="help-block">El tipo del campo</p>
                            </div>
                            <div class="field-group">
                                <label>Longitud de valores</label>
                                <input placeholder="Longitud" name="longs[]" id="long_1" class="form-field required number">
                                <p class="help-block">El n&uacute;mero m&aacute;ximo de caracteres</p>
                            </div>
                            <div class="field-group">
                                <label>Nulo</label>
                                <select name="nulos[]" id="nulo_1" class="form-field">
                                    <option value="SI">Si</option>
                                    <option value="NO">No</option>
                                </select>
                                <p class="help-block">Si el campo puede ser vacio o no</p>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success" id="add_field">Agregar</button>
                    <hr/>

                    <button type="button" class="btn btn-primary" id="save">Enviar</button>
                    <!--<button type="submit" class="btn btn-primary">Enviar</button>-->
                </form>
            </div>
        </div>
        <!-- /.row -->

        <div class="row" style="display: <?php echo $displayjson ?>;">
            <div class="col-lg-12">
                <p>Este es el JSON generado</p>
                <p><?php echo $json; ?></p>
                <p><a href="index.php">Inicio</a></p>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<?php include("footer.php"); ?>