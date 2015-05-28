<?php include("header.php"); ?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$displayform = 'block';
$displayjson = 'none';
$json = '';

if ($_POST) {
    $displayform = 'none';
    $displayjson = 'block';

    //arreglo para formar el JSON
    $arr_json['action'] = "";
    $arr_json['method'] = "post";
    //$arr_json['html'][] = array('type' => 'h1', 'html' => 'Formulario');

    $arr_fields = array();

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
            $temp['placeholder'] = $labels;
            $temp['datepicker']['minDate'] = "-100Y";
            $temp['datepicker']['maxDate'] = date("Y-m-d");
            $temp['datepicker']['dateFormat'] = "yy-mm-dd";
            $temp['datepicker']['autoSize'] = true;
            $temp['datepicker']['defaultDate'] = null;
            $temp['datepicker']['buttonImageOnly'] = true;
            $temp['datepicker']['buttonText'] = "Fecha";
            $temp['datepicker']['selectOtherMonths'] = true;
            $temp['datepicker']['showAnim'] = "slide";
            $temp['datepicker']['showButtonPanel'] = true;
            $temp['datepicker']['showOtherMonths'] = true;
            $temp['datepicker']['yearRange'] = "1910:2099";
            $temp['datepicker']['changeMonth'] = true;
            $temp['datepicker']['changeYear'] = true;
            $temp['datepicker']['closeText'] = "Cerrar";
            $temp['datepicker']['currentText'] = "Hoy";
            $temp['datepicker']['dayNames'] = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", utf8_encode("Sábado")];
            $temp['datepicker']['dayNamesMin'] = ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"];
            $temp['datepicker']['monthNames'] = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $temp['datepicker']['monthNamesShort'] = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];

            $types = $_POST['types'][$i];
        } else if ($_POST['types'][$i] == 'boolean') {
            $temp['type'] = "radiobuttons";
            $temp['name'] = $names;
            $temp['id'] = $names;
            $temp['caption'] = $labels;
            if ($_POST['nulos'][$i] == 'SI') {
                $temp['options']["no"] = "No";
            } else {
                $temp['options']["no"]["checked"] = "checked";
                $temp['options']["no"]["caption"] = "No";
            }
            $temp['options']["si"] = "Si";

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
                    if ($names == 'genero' || $names == 'sexo' || $names == 'genre') {
                        $temp['options']["F"] = "Femenino";
                        $temp['options']["M"] = "Masculino";
                    } else {
                        $temp['options']["v1"] = "Valor 1";
                        $temp['options']["v2"] = "Valor 2";
                        $temp['options']["v3"] = "Valor 3";
                    }
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
            if ($_POST['types'][$i] != 'boolean') {
                $temp['validate']['required'] = true;
                if ($_POST['longs'][$i] != "" && $_POST['longs'][$i] != null) {
                    $temp['validate']['maxlength'] = $_POST['longs'][$i];
                }
                $temp['validate']['messages']['required'] = $labels . " es obligatorio";
                if ($_POST['longs'][$i] != "" && $_POST['longs'][$i] != null) {
                    $temp['validate']['messages']['maxlength'] = "Por favor no ingrese mas de {0} caracteres.";
                }
                if ($_POST['types'][$i] == 'email') {
                    $temp['validate']['messages']['email'] = utf8_encode("Por favor ingrese un email válido");
                }
            }

            $nulos = "NOT NULL";
        }
        $query .= "`$names` $types $nulos,
                ";

        //$arr_json['html'][] = $temp;
        $arr_fields[] = array('type' => "div", 'class' => "6u 12u$(xsmall)", 'html' => $temp);
    }
    $query .= "PRIMARY KEY (`uid`)
            )";

    $arr_json['html'][] = array('type' => "div", 'class' => "row uniform 50%", 'html' => $arr_fields);
    $arr_json['html'][] = array(
        'type' => "ul", 'class' => "actions", 'html' => array(
            'type' => "li", 'html' => array(
                'type' => "submit", 'value' => "Guardar"
            )
        )
    );
    $json = json_encode($arr_json);
    $file = 'form.json';
    file_put_contents($file, $json);
    $resp = $db->sql($query);
}
?>
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
                $('#long_' + id[1]).attr('readonly', 'readonly');
            } else {
                $('#long_' + id[1]).removeAttr('readonly');
                $('#long_' + id[1]).val("");
                $('#long_' + id[1]).addClass("required");
            }
        });
    });
</script>
<div id="page-wrapper">
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
            <div class="panel panel-default">
                <div class="panel-body">                
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
            <!-- /.panel -->
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
<!-- /#page-wrapper -->

<?php include("footer.php"); ?>