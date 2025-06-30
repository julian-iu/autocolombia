<?php
$p_celdaFK = strtoupper($_POST["p_celdaFK"]);
$p_fechasalida = strtoupper($_POST["p_fechasalida"]);
$p_horasalida = strtoupper($_POST["p_horasalida"]);


$sqlparqueo = "SELECT COUNT(*) FROM parqueo WHERE p_celdaFK  = '" . $p_celdaFK . "' AND p_estado = 2 AND p_pago = 0";
$querypar = $pdo->query($sqlparqueo);
$cantidadparqueo = $querypar->fetchColumn();

if ($cantidadparqueo != 0) {
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("Información!", "Vehiculo esta fuera del Parqueo","error");';
    echo '}, 1000);</script>';
    $_SESSION['p_celdaFK'] = $p_celdaFK;
} else {
    $buscarvehiculo = 'SELECT * FROM parqueo WHERE p_celdaFK = ? AND p_estado = ? AND p_pago = ?';
    $ejecveh = $pdo->prepare($buscarvehiculo);
    $ejecveh->execute(array($p_celdaFK, 1, 0));
    $datovh = $ejecveh->fetch(PDO::FETCH_ASSOC);
    $p_id  = $datovh['p_id'];

    $sql = "UPDATE parqueo SET p_fechasalida = ?, p_horasalida = ?, p_estado = ? WHERE p_id = ?;";
    $ejecutar = $pdo->prepare($sql);
    $ejecutar->execute(array($p_fechasalida, $p_horasalida, '2', $p_id));


    $sqlupdate = "UPDATE celda SET c_estado = ? WHERE c_id  = ?;";
    $ejecutarup = $pdo->prepare($sqlupdate);
    $ejecutarup->execute(array('0', $p_celdaFK));
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("Información!", "Actualizacion Exitoso","success");';
    echo '}, 1000);</script>';
    Conexion::desconectar();
    $_SESSION['p_celdaFK'] = "";
}


include_once 'error.php';
