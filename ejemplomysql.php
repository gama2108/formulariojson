<?php
include("lib/mysql.php");
$db = new MySQL();
$consulta = $db->consulta("SELECT id FROM user");
if ($db->num_rows($consulta) > 0) {
    while ($resultados = $db->fetch_array($consulta)) {
        echo "ID: " . $resultados['id'] . "<br />";
    }
}
?>