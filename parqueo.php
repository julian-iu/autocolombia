<?php
include_once 'header.php';
$titulopagina = "PARQUEADERO";
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    if ($accion == 'agregar') {
        include_once 'parqueoagregar.php';
    } else if ($accion == 'actualizar') {
        include_once 'parqueoactualizar.php';
    } else if ($accion == 'eliminar') {
        $id = $_POST['id'];
         $sqlcantidad = "SELECT COUNT(*) FROM pago WHERE pg_parqueoFK = " . $id . "";
        $query = $pdo->query($sqlcantidad);
        $cantidad = $query->fetchColumn();
        if ($cantidad != 0) {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Información en Pagos","error");';
            echo '}, 1000);</script>';
        } else {
            $eliminar = "DELETE FROM parqueo WHERE p_id = ?";
            $ejecutar = $pdo->prepare($eliminar);
            $ejecutar->execute(array($id));
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Eliminacion Exitosa","success");';
            echo '}, 1000);</script>';
            Conexion::desconectar();
        }
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
                        <th>Estado</th>
                        <th>Entrada/Salida</th>

                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Celda</th>
                        <th>Estado</th>
                        <th>Entrada/Salida</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $informacion = 'SELECT * FROM celda ORDER BY c_numero ASC;';
                    $contador = 1;
                    foreach ($pdo->query($informacion) as $dato) {
                    ?>
                        <tr>
                            <td><?php echo $contador; ?></td>
                            <td><?php echo $dato['c_numero'] ?></td>
                            <?php if ($dato['c_estado'] == 0) { ?>
                                <td style="background-color: green; color: white; font-weight: bold;">DISPONIBLE</td>
                            <?php } else { ?>
                                <td style="background-color: red; color: white; font-weight: bold;">OCUPADO</td>
                            <?php } ?>

                            <?php if ($dato['c_estado'] == 0) { ?>
                                <!--ATUALIZAR-->
                                <td>
                                    <center><button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalEntrar<?php echo $dato['c_id'] ?>">
                                    <i class="fa-solid fa-right-from-bracket"></i>&nbsp;&nbsp;Entrada
                                    </button></center>
                                    <div class="modal fade" id="exampleModalEntrar<?php echo $dato['c_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header text-white">
                                                    <h6 class="modal-title">ENTRADA AL PARQUEO <?php echo $dato['c_numero']; ?> </h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form ROLE="FORM" METHOD="POST" ACTION="">
                                                        <input type="hidden" class="form-control" id="accion" name="accion" value="agregar">
                                                        <input type="hidden" class="form-control form-control-sm" id="p_celdaFK" name="p_celdaFK" value="<?php echo !empty($dato['c_id']) ? $dato['c_id'] : ''; ?>" required>
                                                        <span>* Ingrese Información</span><br><br>
                                                        <div class="mb-3">
                                                            <div class="input-group input-group-sm mb-3">
                                                                <span class="input-group-text"><i class="fa-solid fa-car-side"></i></span>
                                                                <input type="text" class="form-control" name="p_vehiculoFK" id="p_vehiculoFK" value="<?php echo htmlspecialchars(isset($_SESSION['p_vehiculoFK']) ? $_SESSION['p_vehiculoFK'] : '');?>" class="form-control" placeholder="Ingrese la PLACA del Vehiculo" required>
                                                            </div>
                                                        </div>

                                                        <div class="input-group input-group-sm mb-3">
                                                            <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                                                            <input type="date" class="form-control" name="p_fechaentrada" class="form-control" value="<?php echo !empty($fechasistema) ? $fechasistema : ''; ?>" placeholder="Fecha Nacimiento" required>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text"><i class="fa-solid fa-clock"></i></span>
                                                            <input type="time" class="form-control" name="p_horaentrada" class="form-control" value="<?php echo !empty($horasistema) ? $horasistema : ''; ?>" placeholder="Fecha Nacimiento" required>
                                                        </div>
                                                        <br>
                                                        <center> <button type="submit" class="btn btn-success btn-sm">Guardar &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-regular fa-floppy-disk"></i></button></center>
                                                        </form>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                </td> 
                                <?php } else { ?>
                                    <td>
                                    <center><button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalSalir<?php echo $dato['c_id'] ?>">
                                     <i class="fa-solid fa-door-open"></i>&nbsp;&nbsp;Salida
                                        </button></center>
                                    <div class="modal fade" id="exampleModalSalir<?php echo $dato['c_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header text-white">
                                                    <h6 class="modal-title">SALIDA AL PARQUEO <?php echo $dato['c_numero']; ?> </h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form ROLE="FORM" METHOD="POST" ACTION="">
                                                        <input type="hidden" class="form-control" id="accion" name="accion" value="actualizar">
                                                        <input type="hidden" class="form-control form-control-sm" id="p_celdaFK" name="p_celdaFK" value="<?php echo !empty($dato['c_id']) ? $dato['c_id'] : ''; ?>" required>
                                                        <span>* Ingrese Información</span><br><br>
                                                        <div class="input-group input-group-sm mb-3">
                                                            <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                                                            <input type="date" class="form-control" name="p_fechasalida" class="form-control" value="<?php echo !empty($fechasistema) ? $fechasistema : ''; ?>" placeholder="Fecha Nacimiento" required>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text"><i class="fa-solid fa-clock"></i></span>
                                                            <input type="time" class="form-control" name="p_horasalida" class="form-control" value="<?php echo !empty($horasistema) ? $horasistema : ''; ?>" placeholder="Fecha Nacimiento" required>
                                                        </div>
                                                        <br>
                                                        <center> <button type="submit" class="btn btn-success btn-sm">Guardar &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-regular fa-floppy-disk"></i></button></center>
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