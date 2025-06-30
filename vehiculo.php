<?php
include_once 'header.php';
$titulopagina = "VEHICULO";
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    if ($accion == 'editar') {
        include_once 'vehactualizar.php';
    } else if ($accion == 'eliminar') {
        $id = $_POST['id'];
        $sqlcantidad = "SELECT COUNT(*) FROM parqueo WHERE p_vehiculoFK = " . $id . "";
        $query = $pdo->query($sqlcantidad);
        $cantidad = $query->fetchColumn();
        if ($cantidad != 0) {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Información en Parqueo","error");';
            echo '}, 1000);</script>';
        } else {
            $eliminar = "DELETE FROM vehiculo WHERE v_id = ?";
            //$ejecutar = $pdo->prepare($eliminar);
            //$ejecutar->execute(array($id));
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Eliminacion Exitosa","success");';
            echo '}, 1000);</script>';
            Conexion::desconectar();
        }
    }
}

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
                        <th>PLaca</th>
                        <th>Propietario</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Color</th>
                        <th></th>
                        <th></th>

                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>PLaca</th>
                        <th>Propietario</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Color</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $informacion = 'SELECT * FROM vehiculo, usuario WHERE v_usuarioFK = u_id ORDER BY v_placa ASC;';
                    $contador = 1;
                    foreach ($pdo->query($informacion) as $dato) {
                    ?>
                        <tr>
                            <td><?php echo $contador; ?></td>
                            <td><?php echo $dato['v_placa'] ?></td>
                            <td><?php echo $dato['u_nombre'] ?></td>
                            <td><?php echo $dato['v_marca'] ?> </td>
                            <td><?php echo $dato['v_modelo'] ?></td>
                            <td><?php echo $dato['v_color'] ?></td>

                            <!--ATUALIZAR-->
                            <td>
                                <center><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalEditar<?php echo $dato['v_id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button></center>
                                <div class="modal fade" id="exampleModalEditar<?php echo $dato['v_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header text-white">
                                                <h6 class="modal-title">ACTUALIZAR <?php echo $titulopagina; ?> </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form ROLE="FORM" METHOD="POST" ACTION="">
                                                    <input type="hidden" class="form-control" id="accion" name="accion" value="editar">
                                                    <input type="hidden" class="form-control form-control-sm" id="id" name="id" value="<?php echo !empty($dato['v_id']) ? $dato['v_id'] : ''; ?>" required>
                                                    <span>* Ingrese Información</span><br><br>
                                                    <div class="input-group input-group-sm mb-3">
                                                        <span class="input-group-text"><i class="fa-regular fa-address-card"></i></span>
                                                        <input type="hidden" class="form-control" name="v_placaviejo" class="form-control" value="<?php echo !empty($dato['v_placa']) ? $dato['v_placa'] : ''; ?>" required>
                                                        <input type="text" class="form-control" name="v_placa" class="form-control" value="<?php echo !empty($dato['v_placa']) ? $dato['v_placa'] : ''; ?>" required>
                                                    </div>

                                                    <div class="input-group input-group-sm mb-3">
                                                        <span class="input-group-text"><i class="fa-brands fa-bandcamp"></i></span>
                                                        <input type="text" class="form-control" name="v_marca" placeholder="Marca del Vehiculo" value="<?php echo $dato['v_marca'] ? $dato['v_marca'] : '' ?>">
                                                    </div>
                                                    <div class="input-group input-group-sm mb-3">
                                                        <span class="input-group-text"><i class="fa-solid fa-font"></i></span>
                                                        <input type="text" class="form-control" name="v_modelo" placeholder="Modelo del Vehiculo" value="<?php echo $dato['v_modelo'] ? $dato['v_modelo'] : '' ?>" required>
                                                    </div>
                                                    <div class="input-group input-group-sm mb-3">
                                                        <span class="input-group-text"><i class="fa-solid fa-palette"></i></span>
                                                        <input type="text" class="form-control" name="v_color" placeholder="Color del Vehiculo" value="<?php echo $dato['v_color'] ? $dato['v_color'] : '' ?>" required>
                                                    </div>
                                                    <center> <button type="submit" class="btn btn-primary btn-sm">Actualizar &nbsp;&nbsp;<i class="fa-solid fa-pen-nib"></i></button></center>
                                                </form>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                            </td>
                            <!-- ELIMININAR  -->
                            <td>
                                <center> <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalEliminar<?php echo $dato['v_id'] ?>">
                                        <i class="fa-solid fa-trash"></i>
                                    </button></center>
                                <div class="modal fade" id="exampleModalEliminar<?php echo $dato['v_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header text-white">
                                                <h6 class="modal-title">ELIMINAR <?php echo $titulopagina; ?> </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form ROLE="FORM" METHOD="POST" ACTION="">
                                                    <input type="hidden" class="form-control" id="accion" name="accion" value="eliminar" />
                                                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo !empty($dato['v_id']) ? $dato['v_id'] : ''; ?>" />
                                                    <h5>¿Desea eliminar la información seleccionada?</h5>
                                                    <center><button type="submit" class="btn btn-primary btn-sm">Eliminar &nbsp;&nbsp; <i class="fa-solid fa-check"></i></button></center>
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