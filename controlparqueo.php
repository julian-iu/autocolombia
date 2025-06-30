<?php
include_once 'header.php';
$titulopagina = "CONTROL DE PARQUEADERO";
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    if ($accion == 'actualizarcontrol') {
        include_once 'parquecontroloactualizar.php';
    } else if ($accion == 'eliminar') {
        $id = $_POST['id'];
        /* $sqlcantidad = "SELECT COUNT(*) FROM tipolavado WHERE t_vehiculoFK = " . $id . "";
        $query = $pdo->query($sqlcantidad);
        $cantidad = $query->fetchColumn();
        if ($cantidad != 0) {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Información en Tipo de Lavado","error");';
            echo '}, 1000);</script>';
        } else {
            $eliminar = "DELETE FROM celdaiculo WHERE c_id = ?";
            $ejecutar = $pdo->prepare($eliminar);
            $ejecutar->execute(array($id));
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Eliminacion Exitosa","success");';
            echo '}, 1000);</script>';
            Conexion::desconectar();
        }*/
    }
}
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
                        <th>Actualizar</th>
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
                        <th>Actualizar</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $informacion = 'SELECT * FROM parqueo, vehiculo, celda WHERE p_vehiculoFK = v_id AND p_celdaFK = c_id  ORDER BY p_id DESC;';
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


                            <?php if ($dato['p_pago'] == 1) { ?>
                                <td style="background-color: green; color: white; font-weight: bold;">PAGO</td>
                            <?php } else { ?>
                                <td>
                                    <center><button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalEntrar<?php echo $dato['c_id'] ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button></center>
                                    <div class="modal fade" id="exampleModalEntrar<?php echo $dato['c_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header text-white">
                                                    <h6 class="modal-title">ACTUALIZAR PARQUEO CELDA No. <?php echo $dato['c_numero']; ?> </h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form ROLE="FORM" METHOD="POST" ACTION="">
                                                        <input type="hidden" class="form-control" id="accion" name="accion" value="actualizarcontrol">
                                                        <input type="hidden" class="form-control form-control-sm" id="p_id" name="p_id" value="<?php echo !empty($dato['p_id']) ? $dato['p_id'] : ''; ?>" required>
                                                        <input type="hidden" class="form-control form-control-sm" id="p_celdaFK" name="p_celdaFK" value="<?php echo !empty($dato['c_id']) ? $dato['c_id'] : ''; ?>" required>
                                                        <span>* Ingrese Información</span>
                                                        <br><br>
                                                        <span><strong>Entrada:</strong></span><br>

                                                        <div class="input-group input-group-sm mb-3">
                                                            <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                                                            <input type="date" class="form-control" name="p_fechaentrada" class="form-control" value="<?php echo !empty($dato['p_fechaentrada']) ? $dato['p_fechaentrada'] : ''; ?>" placeholder="Fecha Entrada" required>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text"><i class="fa-solid fa-clock"></i></span>
                                                            <input type="time" class="form-control" name="p_horaentrada" class="form-control" value="<?php echo !empty($dato['p_horaentrada']) ? $dato['p_horaentrada'] : ''; ?>" placeholder="Hora Salida" required>
                                                        </div>
                                                        <span><strong>Salida:</strong></span><br>
                                                        <div class="input-group input-group-sm mb-3">
                                                            <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                                                            <input type="date" class="form-control" name="p_fechasalida" class="form-control" value="<?php echo !empty($dato['p_fechasalida']) ? $dato['p_fechasalida'] : ''; ?>" placeholder="Fecha Entrada">
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text"><i class="fa-solid fa-clock"></i></span>
                                                            <input type="time" class="form-control" name="p_horasalida" class="form-control" value="<?php echo !empty($dato['p_horasalida']) ? $dato['p_horasalida'] : ''; ?>" placeholder="Hora Salida" required>
                                                        </div>
                                                        <br>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text"><i class="fa-solid fa-star"></i></span>
                                                            <select class="form-select form-select-sm" name="p_estado" id="p_estado" required>
                                                                <?php
                                                                if ($dato['p_estado'] == 1) {
                                                                ?>
                                                                    <option value="1" selected>ENTRADA</option>
                                                                    <option value="2">SALIDA</option>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <option value="1">ENTRADA</option>
                                                                    <option value="2" selected>SALIDA</option>
                                                                <?php
                                                                }

                                                                ?>
                                                            </select>
                                                        </div>
                                                        <br>
                                                        <center> <button type="submit" class="btn btn-success btn-sm">Actualizar &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-regular fa-floppy-disk"></i></button></center>
                                                    </form>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                </td>
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