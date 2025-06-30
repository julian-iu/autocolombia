<?php
include_once 'header.php';
$titulopagina = "PARQUEOS";
?>

<body>
    <?php
    include_once 'menumovil.php';
    ?>
    <div class="card">
        <div class="card-header text-white">
            <strong>TABLA DE <?php echo $titulopagina; ?>
            </strong>
        </div>
        <div class="card-body">
            <form action=" " method="POST">

                <table id="datatablesSimple" class="display table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>PLACA</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>H. Parqueo</th>
                            <th>Horas</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>PLACA</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>H. Parqueo</th>
                            <th>Horas</th>
                            <th>Total</th>
                        </tr>
                    </tfoot>
                    <tbody>

                        <?php
                        $informacion = 'SELECT * FROM parqueo, lavado, tipolavado, vehiculo, empleado WHERE p_lavadoFK = l_id AND l_tipolavadoFK = t_id AND l_vehiculoFK = v_id AND l_empleadoFK = e_id AND l_fecha = "' . $fechasistema . '" ORDER BY l_estado ASC;';
                        $contador = 1;
                        foreach ($pdo->query($informacion) as $dato) {
                        ?>
                            <tr>
                                <td><?php echo $dato['v_placa'] ?></td>
                                <td><?php echo $dato['v_tipovehiculo'] ?></td>

                                <td style="text-align: right;"><?php echo number_format($dato["t_precio"], 0, ",", "."); ?></td>
                                <?php

                                // CONCEPTO
                                $buscarconc = 'SELECT * FROM concepto WHERE c_concepto = ?';
                                $consultacon = $pdo->prepare($buscarconc);
                                $consultacon->execute(array($dato['v_tipovehiculo']));
                                $datocon = $consultacon->fetch(PDO::FETCH_ASSOC);
                                $valor = $datocon['c_precio'];

                                $fechaInicial = '' . $dato['p_fecha']  . ' ' . $dato['p_hora']  . ''; //fecha inicial
                                $fechaFinal = '' . $fechasistema . ' ' . $horasistema . '';

                                $segundos = strtotime($fechaFinal) - strtotime($fechaInicial);
                                $horas =  $segundos * (1 / 3600);
                                $h_horaparqueo = 0;

                                if ($horas > 1.0) {
                                    $h_horaparqueo = $horas - 1;
                                } else {
                                    $h_horaparqueo = 0;
                                }

                                ?>
                                <td style="text-align: right;"><?php echo $dato['p_hora'] ?></td>
                                <td style="text-align: center;"><?php echo round($h_horaparqueo, 1); ?></td>
                                <td style="text-align: right;"><?php echo number_format(round(($h_horaparqueo * $valor), 0), 0, ",", "."); ?></td>
                            </tr>
                        <?php
                            $contador = $contador + 1;
                        }
                        ?>

                    </tbody>

                </table>
            </form>
        </div>
    </div>

</body>
<?php include_once 'footer.php' ?>
<script src="js/simple-datatables.js" crossorigin="anonymous"></script>

</html>