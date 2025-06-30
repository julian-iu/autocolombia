<?php
include_once 'header.php';
$titulopagina = "CONTROL DE MI PARQUEADERO";
?>
<?php

include_once 'error.php';
?>

<body>

    <div class="card">
        <div class="card-header text-white">
            <strong>TABLA DE <?php echo $titulopagina; ?> </strong>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="display table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Celda</th>
                        <th>Vehiculo</th>
                        <th>F. Entrada</th>
                        <th>H. Entrada</th>
                        <th>F. Salida</th>
                        <th>H. Salida</th>
                        <th>Horas</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Pagado</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Celda</th>
                        <th>Vehiculo</th>
                        <th>F. Entrada</th>
                        <th>H. Entrada</th>
                        <th>F. Salida</th>
                        <th>H. Salida</th>
                        <th>Horas</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Pagado</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $informacion = 'SELECT * FROM parqueo, vehiculo, celda WHERE v_usuarioFK  = '.$u_id.' AND  p_vehiculoFK = v_id AND p_celdaFK = c_id  ORDER BY p_id DESC;';
                    $contador = 1;
                    foreach ($pdo->query($informacion) as $dato) {

                        $fechaInicial = '' . $dato['p_fechaentrada']  . ' ' . $dato['p_horaentrada']  . ''; //fecha inicial
                        $fechaFinal =  '' . $dato['p_fechasalida']  . ' ' . $dato['p_horasalida']  . ''; //fecha inicial

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
                    ?>
                        <tr>
                            <td><?php echo $contador; ?></td>
                            <td><?php echo $dato['c_numero'] ?></td>
                            <td><?php echo $dato['v_placa'] ?></td>
                            <td><?php echo $dato['p_fechaentrada'] ?></td>
                            <td><?php echo $dato['p_horaentrada'] ?></td>
                            <td><?php echo $dato['p_fechasalida'] ?></td>
                            <td><?php echo $dato['p_horasalida'] ?></td>
                            <td style="text-align: center;"><?php echo round($h_horaparqueo, 1); ?></td>
                            <td style="text-align: right;"><?php echo number_format(round(($h_horaparqueo * $valor), 0), 0, ",", "."); ?></td>
                            <?php if ($dato['p_estado'] == 1) { ?>
                                <td style="background-color: red; color: white; font-weight: bold;">ENTRADA</td>
                            <?php } else { ?>
                                <td style="background-color: green; color: white; font-weight: bold;">SALIDA</td>
                            <?php } ?>

                            <?php if ($dato['p_pago'] == 1) { ?>
                                <td style="background-color: green; color: white; font-weight: bold;">PAGO</td>
                            <?php } else { ?>
                                <td style="background-color: red; color: white; font-weight: bold;">NO CANCELADO</td>
                            <?php } ?>

                        </tr>
                    <?php
                        $contador = $contador + 1;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
<?php include_once 'footer.php' ?>

<script src="js/simple-datatables.js" crossorigin="anonymous"></script>

</html>