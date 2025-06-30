<?php
include_once 'header.php';
$titulopagina = "REALIZAR PAGOS";
$sqlconsulta = "";
$sqlsuma = "";
$estado = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    if ($accion == 'buscar') {
        $placa = $_POST['placa'];
        $mes = $_POST['mes'];
        $ano = $_POST['ano'];
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
                $sqlconsulta = "SELECT * FROM parqueo, vehiculo, celda WHERE p_vehiculoFK = v_id AND p_celdaFK = c_id  AND MONTH(p_fechasalida) = " . $mes . " AND YEAR(p_fechasalida) = " . $ano . " AND p_estado = '2' AND  p_vehiculoFK  = " . $v_id . " AND p_pago = '0' ";
                $_SESSION['placa'] = $placa;
                $_SESSION['mes'] = $mes;
                $_SESSION['ano'] = $ano;
            }
        } else {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Ingrese todos los datos a consultar","error");';
            echo '}, 1000);</script>';
        }
    } else if ($accion == 'pagar') {
        include_once 'parqueopagar.php';
    }
}

?>

<body>
    <div class="container">

        <span><strong>Realizar consulta por:</strong></span><br><br>
        <form ROLE="FORM" METHOD="POST" ACTION="">
            <input type="hidden" class="form-control" name="accion" value="buscar">

            <div class="row">
                <div class="col-3">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                        <input type="text" class="form-control" name="placa" placeholder="Placa del Vehiculo" value="<?php echo htmlspecialchars(isset($placa) ? $placa : ''); ?>">
                    </div>
                </div>

                <div class="col-3">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                        <select class="form-select form-select-sm" name="mes" id="mes" required>
                            <?php
                            $mes = array("MES", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
                            foreach ($mes as $i => $valor) {
                                if ($mes[$i] == $_SESSION['mes']) {
                            ?>
                                    <option value="<?php echo $mes[$i] ?>" selected>
                                        <?php echo $mes[$i] ?>
                                    </option>
                                <?php
                                } else {
                                ?>
                                    <option value="<?php echo $mes[$i] ?>">
                                        <?php echo $mes[$i] ?>
                                    </option>
                            <?php
                                }
                            }

                            ?>
                        </select>
                    </div>

                </div>
                <div class="col-4">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                        <input type="number" class="form-control" name="ano" placeholder="Año a Consultar" value="<?php echo htmlspecialchars(isset($ano) ? $ano : ''); ?>">
                    </div>

                </div>

                <div class="col-1">
                    <center> <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-magnifying-glass fa-2x"></i></button></center>
                </div>


        </form>
    </div>
    <div class="card">
        <div class="card-header text-white">
            <strong>TABLA DE <?php echo $titulopagina; ?>
            </strong>
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
                    $contador = 1;
                    $suma = 0;

                    foreach ($pdo->query($sqlconsulta) as $dato) {

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

                        $suma = $suma + ($h_horaparqueo * $valor);
                        $vehiculo =  $dato['v_placa'];
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


                            <?php if ($dato['p_pago'] == 1) { ?>
                                <td style="background-color: green; color: white; font-weight: bold;">PAGO</td>
                            <?php } else { ?>

                            <?php } ?>
                        </tr>

                    <?php
                        $contador = $contador + 1;
                    }

                    ?>
                </tbody>
                <tbody>
                    <tr>
                        <td colspan='9'>
                            <h2>SUMATORIA TOTAL ES : <strong><?php echo number_format(round(($suma), 0), 0, ",", "."); ?></h2></strong>
                        </td>
                        <td colspan='2'>
                            <center> <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalEliminar">
                                    <i class="fa-solid fa-dollar"></i>&nbsp;&nbsp;CANCELAR
                                </button></center>
                            <div class="modal fade" id="exampleModalEliminar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header text-white">
                                            <h6 class="modal-title">PAGAR <?php echo $titulopagina; ?> </h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form ROLE="FORM" METHOD="POST" ACTION="">
                                                <input type="hidden" class="form-control" id="accion" name="accion" value="pagar" />
                                                <input type="hidden" class="form-control" name="placa" placeholder="Nombres Completos" value="<?php echo htmlspecialchars(string: isset($_SESSION['placa']) ? $_SESSION['placa'] : ''); ?>">
                                                <input type="hidden" class="form-control" name="mes" placeholder="Nombres Completos" value="<?php echo htmlspecialchars(string: isset($_SESSION['mes']) ? $_SESSION['mes'] : ''); ?>">
                                                <input type="hidden" class="form-control" name="ano" placeholder="Nombres Completos" value="<?php echo htmlspecialchars(string: isset($_SESSION['ano']) ? $_SESSION['ano'] : ''); ?>">

                                                <h5>¿Desea realizar el pago del mes correspondiente al vehiculo de placa <?php echo  $vehiculo; ?> del mes <?php echo htmlspecialchars(string: isset($_SESSION['mes']) ? $_SESSION['mes'] : ''); ?> año <?php echo htmlspecialchars(string: isset($_SESSION['ano']) ? $_SESSION['ano'] : ''); ?></h5>
                                                <center><button type="submit" class="btn btn-success btn-sm">Aceptar &nbsp;&nbsp; <i class="fa-solid fa-check"></i></button></center>
                                            </form>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>
<?php include_once 'footer.php' ?>
<script src="js/simple-datatables.js" crossorigin="anonymous"></script>

</html>