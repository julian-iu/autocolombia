<?php
$p_id = $_POST["p_id"];
$p_celdaFK = $_POST["p_celdaFK"];
$p_fechaentrada = $_POST["p_fechaentrada"];
$p_horaentrada = $_POST["p_horaentrada"];
$p_fechasalida = $_POST["p_fechasalida"];
$p_horasalida = $_POST["p_horasalida"];
$p_estado = $_POST["p_estado"];

if ($p_estado == 1) { // entrada


    $sql = "UPDATE parqueo SET p_fechaentrada = ?, p_fechaentrada = ?,p_fechasalida = ?, p_horasalida = ?, p_estado = ? WHERE p_id = ?;";
    $ejecutar = $pdo->prepare($sql);
    $ejecutar->execute(array($p_fechaentrada, $p_fechaentrada, '', '', '1', $p_id));

    $sqlupdate = "UPDATE celda SET c_estado = ? WHERE c_id  = ?;";
    $ejecutarup = $pdo->prepare($sqlupdate);
    $ejecutarup->execute(array('1', $p_celdaFK));

    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("Información!", "Actualizacion Exitoso de Entrada de Vehiculo","success");';
    echo '}, 1000);</script>';
    Conexion::desconectar();
} else {

    if ($p_fechasalida == '' || $p_horasalida == '') {
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal("Información!", "Debe Ingresar la Fecha y Hora de Salida","error");';
        echo '}, 1000);</script>';
    } else {

        $sql = "UPDATE parqueo SET p_fechaentrada = ?, p_fechaentrada = ?,p_fechasalida = ?, p_horasalida = ?, p_estado = ? WHERE p_id = ?;";
        $ejecutar = $pdo->prepare($sql);
        $ejecutar->execute(array($p_fechaentrada, $p_fechaentrada, $p_fechasalida, $p_horasalida, '2', $p_id));

        $sqlupdate = "UPDATE celda SET c_estado = ? WHERE c_id  = ?;";
        $ejecutarup = $pdo->prepare($sqlupdate);
        $ejecutarup->execute(array('0', $p_celdaFK));

        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal("Información!", "Actualizacion Exitoso de Salida de Vehiculo","success");';
        echo '}, 1000);</script>';
        Conexion::desconectar();
    }
}
