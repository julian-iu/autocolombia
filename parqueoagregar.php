<?php
$p_vehiculoFK = str_replace(' ', '', strtoupper($_POST["p_vehiculoFK"]));
$p_celdaFK = strtoupper($_POST["p_celdaFK"]);
$p_fechaentrada = strtoupper($_POST["p_fechaentrada"]);
$p_horaentrada = strtoupper($_POST["p_horaentrada"]);

$sqlcantidad = "SELECT COUNT(*) FROM vehiculo WHERE v_placa = '" . $p_vehiculoFK . "'";

$query = $pdo->query($sqlcantidad);
$cantidad = $query->fetchColumn();

if ($cantidad == 0) {
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("Información!", "Placa del Vehiculo no registrada","error");';
    echo '}, 1000);</script>';
    $_SESSION['p_celdaFK'] = $p_celdaFK;
    $_SESSION['p_vehiculoFK'] = $p_vehiculoFK;
} else { // insertamos

    $sqlestado = "SELECT COUNT(*) FROM celda WHERE c_id = '" . $p_celdaFK . "' AND c_estado = 1";
    $queestado = $pdo->query($sqlestado);
    $cantidadestado = $queestado->fetchColumn();

    if ($cantidadestado != 0) {
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal("Información!", "Celda esta ocupada","error");';
        echo '}, 1000);</script>';
        $_SESSION['p_celdaFK'] = $p_celdaFK;
        $_SESSION['p_vehiculoFK'] = $p_vehiculoFK;
    } else {
        $buscarvehiculo = 'SELECT * FROM vehiculo WHERE v_placa = ?';
        $ejecveh = $pdo->prepare($buscarvehiculo);
        $ejecveh->execute(array($p_vehiculoFK));
        $datovh = $ejecveh->fetch(PDO::FETCH_ASSOC);
        $v_id = $datovh['v_id'];


        $sqlparqueo = "SELECT COUNT(*) FROM parqueo WHERE p_vehiculoFK = '" . $v_id . "' AND p_estado = 1 AND p_pago = 0";
        $querypar = $pdo->query($sqlparqueo);
        $cantidadparqueo = $querypar->fetchColumn();


        if ($cantidadparqueo != 0) {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Vehiculo esta dentro del Parqueo","error");';
            echo '}, 1000);</script>';
            $_SESSION['p_celdaFK'] = $p_celdaFK;
            $_SESSION['p_vehiculoFK'] = $p_vehiculoFK;
        } else {


            $sql = "INSERT INTO parqueo (p_vehiculoFK, p_celdaFK, p_fechaentrada, p_horaentrada, p_fechasalida, p_horasalida, p_estado, p_pago) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
            $ejecutar = $pdo->prepare($sql);
            $ejecutar->execute(array($v_id, $p_celdaFK, $p_fechaentrada, $p_horaentrada, '', '', '1', '0'));


            $sqlupdate = "UPDATE celda SET c_estado = ? WHERE c_id  = ?;";
            $ejecutarup = $pdo->prepare($sqlupdate);
            $ejecutarup->execute(array('1', $p_celdaFK));
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Registro Exitoso","success");';
            echo '}, 1000);</script>';
            Conexion::desconectar();

            $_SESSION['p_celdaFK'] = "";
            $_SESSION['p_vehiculoFK'] =  "";
        }
    }
}
include_once 'error.php';
