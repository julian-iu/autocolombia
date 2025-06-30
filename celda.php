<?php
include_once 'header.php';
$titulopagina = "TIPO DE CELDA";
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    if ($accion == 'agregar') {
        include_once 'celdaagregar.php';
    } else if ($accion == 'editar') {
        include_once 'celdaactualizar.php';
    } else if ($accion == 'eliminar') {
        $id = $_POST['id'];
        $sqlcantidad = "SELECT COUNT(*) FROM parqueo WHERE p_celdaFK = " . $id . "";
        $query = $pdo->query($sqlcantidad);
        $cantidad = $query->fetchColumn();
        if ($cantidad != 0) {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Información en Parqueo","error");';
            echo '}, 1000);</script>';
        } else {
            $eliminar = "DELETE FROM celda WHERE c_id = ?";
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

    <div class="modal fade" id="agregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content ">
                <div class="modal-header text-white">
                    <h6 class="modal-title" id="agregar">REGISTRAR <?php echo $titulopagina; ?></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form ROLE="FORM" METHOD="POST" ACTION="">
                        <input type="hidden" class="form-control" id="accion" name="accion" value="agregar">
                        <span>* Ingrese Información</span><br><br>

                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                            <input type="text" class="form-control" name="c_numero" placeholder="Ingrese el No. de la Celda" value="<?php echo htmlspecialchars(isset($_SESSION['c_numero']) ? $_SESSION['c_numero'] : ''); ?>">
                        </div>

                        <center> <button type="submit" class="btn btn-success btn-sm">Guardar &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-regular fa-floppy-disk"></i></button></center>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header text-white">
            <strong>TABLA DE <?php echo $titulopagina; ?> &nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#agregar"><i class="fa-solid fa-add"></i>&nbsp;&nbsp;<strong>Nuevo</strong></button>
            </strong>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="display table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Celda</th>
                        <th></th>
                        <th></th>

                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Celda</th>
                        <th></th>
                        <th></th>
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
                            <!--ATUALIZAR-->
                            <td>
                                <center><button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalEditar<?php echo $dato['c_id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button></center>
                                <div class="modal fade" id="exampleModalEditar<?php echo $dato['c_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header text-white">
                                                <h6 class="modal-title">ACTUALIZAR <?php echo $titulopagina; ?> </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form ROLE="FORM" METHOD="POST" ACTION="">
                                                    <input type="hidden" class="form-control" id="accion" name="accion" value="editar">
                                                    <input type="hidden" class="form-control form-control-sm" id="id" name="id" value="<?php echo !empty($dato['c_id']) ? $dato['c_id'] : ''; ?>" required>
                                                    <span>* Ingrese Información</span><br><br>
                                                    <div class="input-group input-group-sm mb-3">
                                                        <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                                                        <input type="hidden" class="form-control" name="c_numeroviejo" class="form-control" value="<?php echo !empty($dato['c_numero']) ? $dato['c_numero'] : ''; ?>" required>
                                                        <input type="text" class="form-control" name="c_numero" class="form-control" value="<?php echo !empty($dato['c_numero']) ? $dato['c_numero'] : ''; ?>" required>
                                                    </div>
                                                    <center> <button type="submit" class="btn btn-success btn-sm">Actualizar &nbsp;&nbsp;<i class="fa-solid fa-pen-nib"></i></button></center>
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
                                <center> <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalEliminar<?php echo $dato['c_id'] ?>">
                                        <i class="fa-solid fa-trash"></i>
                                    </button></center>
                                <div class="modal fade" id="exampleModalEliminar<?php echo $dato['c_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header text-white">
                                                <h6 class="modal-title">ELIMINAR <?php echo $titulopagina; ?> </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form ROLE="FORM" METHOD="POST" ACTION="">
                                                    <input type="hidden" class="form-control" id="accion" name="accion" value="eliminar" />
                                                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo !empty($dato['c_id']) ? $dato['c_id'] : ''; ?>" />
                                                    <h5>¿Desea eliminar la información seleccionada?</h5>
                                                    <center><button type="submit" class="btn btn-success btn-sm">Eliminar &nbsp;&nbsp; <i class="fa-solid fa-check"></i></button></center>
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