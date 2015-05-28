<?php include("header.php"); ?>

<?php
//saber si esxiste o no la tabla usuarios
$query = "show tables like 'user'";
$sql = $db->consulta($query);
if (!$sql) {
    header("location: config.php");
}

//nombre de las columnas
$querycol = "SHOW COLUMNS FROM user";
$namecolumns = $db->consulta($querycol);
$columns = array();
for ($i = 0; $i < count($namecolumns); $i++) {
    $columns[] = $namecolumns[$i]['Field'];
}

//usuarios registrados
$queryusers = "SELECT * FROM `user`";
$users = $db->consulta($queryusers);
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Usuarios</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Lista de registros ingresados
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <?php for ($i = 0; $i < count($columns); $i++) { ?>
                                        <th><?php echo $columns[$i]; ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!$users) { ?>
                                    <tr class="odd gradeX">
                                        <td colspan="<?php echo count($columns) ?>">No se encontraron registros</td>
                                    </tr>
                                    <?php
                                } else {
                                    for ($i = 0; $i < count($users); $i++) {
                                        ?>
                                        <tr class="odd gradeX">
                                            <?php
                                            foreach ($users[$i] as $value) {
                                                ?>
                                                <td><?php echo $value;?></td>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>
<!-- /#page-wrapper -->

<?php include("footer.php"); ?>