<?php
$placa = str_replace(' ', '', strtoupper($_POST["placa"]));
$mes = strtoupper($_POST["mes"]);
$ano = strtoupper($_POST["ano"]);

if ($placa != "" && $mes != "MES" && $ano != "") { // buscar ano y mes
    $sqlcantidad = "SELECT COUNT(*) FROM vehiculo WHERE v_placa = '" . $placa . "'";
    $query = $pdo->query($sqlcantidad);
    $cantidad = $query->fetchColumn();

    if ($cantidad == 0) {
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal("Información!", "Placa del Vehiculo no registrada","error");';
        echo '}, 1000);</script>';
        $_SESSION['placa'] = "";
        $_SESSION['mes'] = $mes;
        $_SESSION['ano'] = $ano;
    } else { // insertamos

        $buscarvehiculo = 'SELECT * FROM vehiculo WHERE v_placa = ?';
        $ejecveh = $pdo->prepare($buscarvehiculo);
        $ejecveh->execute(array($placa));
        $datovh = $ejecveh->fetch(PDO::FETCH_ASSOC);
        $v_id = $datovh['v_id'];

        $sqlinformacion = "SELECT * FROM parqueo, vehiculo, celda WHERE p_vehiculoFK = v_id AND p_celdaFK = c_id  AND MONTH(p_fechasalida) = " . $mes . " AND YEAR(p_fechasalida) = " . $ano . " AND p_estado = '2' AND  p_vehiculoFK  = " . $v_id . " AND p_pago = '1' ";


        foreach ($pdo->query($sqlinformacion) as $info) {

            $fechaInicial = '' . $info['p_fechaentrada']  . ' ' . $info['p_horaentrada']  . ''; //fecha inicial
            $fechaFinal =  '' . $info['p_fechasalida']  . ' ' . $info['p_horasalida']  . ''; //fecha inicial

            $segundos = strtotime($fechaFinal) - strtotime($fechaInicial);
            $horas =  $segundos * (1 / 3600);
            $h_horaparqueo = 0;

            if ($horas > 0) {
                $h_horaparqueo = $horas;
            } else {
                $h_horaparqueo = 0;
            }

            $buscarconc = 'SELECT * FROM concepto WHERE cp_id = ?';
            $consultacon = $pdo->prepare($buscarconc);
            $consultacon->execute(array(1));
            $datocon = $consultacon->fetch(PDO::FETCH_ASSOC);
            $valor = $datocon['cp_precio'];

            $suma = $h_horaparqueo * $valor;

            $eliminar = "DELETE FROM pago WHERE pg_parqueoFK = ?";
            $ejecutar = $pdo->prepare($eliminar);
            $ejecutar->execute(array($info['p_id']));

            $sqlupdate = "UPDATE parqueo SET p_pago = ? WHERE p_id  = ?;";
            $ejecutarup = $pdo->prepare($sqlupdate);
            $ejecutarup->execute(array('0', $info['p_id']));

            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Pago Exitoso","success");';
            echo '}, 1000);</script>';
            $sqlconsulta = $sqlinformacion;
        }
    }
} else {
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("Información!", "Ingrese todos los datos a consultar","error");';
    echo '}, 1000);</script>';
}
include_once 'error.php';
