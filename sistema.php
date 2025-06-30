<?php
include_once 'header.php';
$titulopagina = "CONFIGURACIÓN DEL SISTEMA";
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    if ($accion == 'editar') {
        $id = $_POST['id'];
        $cp_precio = strtoupper($_POST['cp_precio']);
        $sql = "UPDATE concepto SET cp_precio = ? WHERE cp_id  = ?";
        $ejecutar = $pdo->prepare($sql);
        $ejecutar->execute(array($cp_precio, $id));
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal("Información!", "Actualización Exitosa en el Sistema","success");';
        echo '}, 1000);</script>';
        Conexion::desconectar();
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
                        <th>Concepto</th>
                        <th>Precio</th>
                        <th></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Concepto</th>
                        <th>Precio</th>
                        <th></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $informacion = 'SELECT * FROM concepto ORDER BY cp_concepto ASC;';
                    $contador = 1;
                    foreach ($pdo->query($informacion) as $dato) {
                    ?>
                        <tr>
                            <td><?php echo $contador; ?></td>
                            <td><?php echo $dato['cp_concepto'] ?></td>
                            <td style="text-align: right;"><?php echo number_format($dato["cp_precio"], 0, ",", "."); ?></td>
                            <!--ATUALIZAR-->
                            <td>
                                <center><button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalEditar<?php echo $dato['cp_id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button></center>
                                <div class="modal fade" id="exampleModalEditar<?php echo $dato['cp_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header text-white">
                                                <h6 class="modal-title">ACTUALIZAR <?php echo $titulopagina; ?> </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form ROLE="FORM" METHOD="POST" ACTION="">
                                                    <input type="hidden" class="form-control" id="accion" name="accion" value="editar">
                                                    <input type="hidden" class="form-control form-control-sm" id="id" name="id" value="<?php echo !empty($dato['cp_id']) ? $dato['cp_id'] : ''; ?>" required>
                                                    <div class="mb-3">
                                                        <label class="form-label">Precio:</label>
                                                        <input type="number" class="form-control form-control-sm" id="cp_precio" name="cp_precio" placeholder="Ingrese Información" value="<?php echo !empty($dato['cp_precio']) ? $dato['cp_precio'] : ''; ?>" required>
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