<?php

$c_numero = strtoupper($_POST["c_numero"]);
$c_estado = '0';

$sqlcantidad = "SELECT COUNT(*) FROM celda WHERE c_numero = '" . $c_numero . "'";
$query = $pdo->query($sqlcantidad);
$cantidad = $query->fetchColumn();


if ($cantidad != 0) {
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("Información!", "Celda ya Registrada","error");';
    echo '}, 1000);</script>';
    $_SESSION['c_numero'] = $c_numero;

} else { // insertamos
    $sql = "INSERT INTO celda (c_numero, c_estado) VALUES (?, ?);";
    $ejecutar = $pdo->prepare($sql);
    $ejecutar->execute(array($c_numero, $c_estado));
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("Información!", "Registro Exitoso","success");';
    echo '}, 1000);</script>';
    $_SESSION['c_numero'] = "";
    Conexion::desconectar();
}
