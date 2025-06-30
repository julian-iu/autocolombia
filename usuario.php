<?php
include_once 'header.php';
$titulopagina = "USUARIO";
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    if ($accion == 'agregar') {
        include_once 'usuarioagregar.php';
    } else if ($accion == 'editar') {
        include_once 'usuarioactualizar.php';
    } else if ($accion == 'eliminar') {
        $id = $_POST['id'];
        $sqlcantidad = "SELECT COUNT(*) FROM vehiculo WHERE v_usuarioFK  = " . $id . "";
        $query = $pdo->query($sqlcantidad);
        $cantidad = $query->fetchColumn();
        if ($cantidad != 0) {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Información en Vehiculos","error");';
            echo '}, 1000);</script>';
        } else {
            $eliminar = "DELETE FROM usuario WHERE u_id = ?";
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
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
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
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input type="text" class="form-control" name="u_nombre" placeholder="Nombres Completos" value="<?php echo htmlspecialchars(string: isset($_SESSION['u_nombre']) ? $_SESSION['u_nombre'] : '');?>">
                        </div>
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-mobile-screen-button"></i></span>
                            <input type="number" class="form-control" name="u_celular" placeholder="Número Celular" value="<?php echo htmlspecialchars(isset($_SESSION['u_celular']) ? $_SESSION['u_celular'] : '');?>" required>
                        </div>
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-star"></i></span>
                            <select class="form-select form-select-sm" name="u_tipo" id="u_tipo" required>
                                <?php
                                $tipousuario = array("ADMINISTRADOR", "USUARIO");
                                foreach ($tipousuario as $i => $valor) {
                                    if ($tipousuario[$i] == $_SESSION['u_tipo']) {
                                ?>
                                        <option value="<?php echo $tipousuario[$i] ?>" selected>
                                            <?php echo $tipousuario[$i] ?>
                                        </option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="<?php echo $tipousuario[$i] ?>">
                                            <?php echo $tipousuario[$i] ?>
                                        </option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                            <input type="text" class="form-control" name="u_usuario" placeholder="Usuario Sistema" value="<?php echo htmlspecialchars(isset($_SESSION['u_usuario']) ? $_SESSION['u_usuario'] : '');?>" required>
                        </div>
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                            <input type="password" class="form-control" name="u_contrasena" placeholder="Contraseña Sistema" value="<?php echo htmlspecialchars(isset($_SESSION['u_contrasena']) ? $_SESSION['u_contrasena'] : '');?>" required>
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
                        <th>Nombre</th>
                        <th>Celular</th>
                        <th>Usuario</th>
                        <th>Tipo Usuario</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Nombre</th>
                        <th>Celular</th>
                        <th>Usuario</th>
                        <th>Tipo Usuario</th>
                        <th></th>
                        <th></th>
                        <th></th>

                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $informacion = 'SELECT * FROM usuario  WHERE u_id != 1 ORDER BY u_nombre  ASC;';
                    $contador = 1;
                    foreach ($pdo->query($informacion) as $dato) {
                    ?>
                        <tr>
                            <td><?php echo $contador; ?></td>
                            <td><?php echo $dato['u_nombre'] ?></td>
                            <td><?php echo $dato['u_celular'] ?> </td>
                            <td><?php echo $dato['u_usuario'] ?></td>
                            <td><?php echo $dato['u_tipo'] ?></td>
                            <td>
                                    <center><a href="vehiculousuario?id=<?php echo $dato['u_id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-car"></i>
                                        </a></center>
                                </td>
                            <!--ATUALIZAR-->
                            <td>
                                <center><button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalEditar<?php echo $dato['u_id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button></center>
                                <div class="modal fade" id="exampleModalEditar<?php echo $dato['u_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header text-white">
                                                <h6 class="modal-title">ACTUALIZAR <?php echo $titulopagina; ?> </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form ROLE="FORM" METHOD="POST" ACTION="">
                                                    <input type="hidden" class="form-control" id="accion" name="accion" value="editar">
                                                    <input type="hidden" class="form-control form-control-sm" id="id" name="id" value="<?php echo !empty($dato['u_id']) ? $dato['u_id'] : ''; ?>" required>
                                                    <span>* Ingrese Información</span><br><br>

                                                    <div class="input-group input-group-sm mb-3">
                                                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                                        <input type="text" class="form-control" name="u_nombre" placeholder="Nombres Completos" value="<?php echo !empty($dato['u_nombre']) ? $dato['u_nombre'] : ''; ?>">
                                                    </div>
                                                    <div class="input-group input-group-sm mb-3">
                                                        <span class="input-group-text"><i class="fa-solid fa-mobile-screen-button"></i></span>
                                                        <input type="number" class="form-control" name="u_celular" class="form-control" placeholder="No. Celular" value="<?php echo !empty($dato['u_celular']) ? $dato['u_celular'] : ''; ?>" required>
                                                    </div>
                                                    <div class="input-group input-group-sm mb-3">
                                                        <span class="input-group-text"><i class="fa-solid fa-star"></i></span>
                                                        <select class="form-select form-select-sm" name="u_tipo" id="u_tipo" required>
                                                            <?php
                                                            $tipousuarioac = array("ADMINISTRADOR", "USUARIO");
                                                            foreach ($tipousuarioac as $i => $valor) {
                                                                if ($tipousuarioac[$i] == $dato['u_tipo']) {
                                                            ?>
                                                                    <option value="<?php echo $tipousuarioac[$i] ?>" selected>
                                                                        <?php echo $tipousuarioac[$i] ?>
                                                                    </option>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <option value="<?php echo $tipousuarioac[$i] ?>">
                                                                        <?php echo $tipousuarioac[$i] ?>
                                                                    </option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="input-group input-group-sm mb-3">
                                                        <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                                                        <input type="text" class="form-control" name="u_usuario" placeholder="Usuario Sistema" value="<?php echo $dato['u_usuario'] ? $dato['u_usuario'] : '' ?>" required>
                                                        <input type="hidden" class="form-control" name="u_usuarioviejo" value="<?php echo $dato['u_usuario'] ? $dato['u_usuario'] : '' ?>" required>

                                                    </div>
                                                    <div class="input-group input-group-sm mb-3">
                                                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                                        <input type="password" class="form-control" name="u_contrasena" placeholder="Contraseña Sistema" value="<?php echo $dato['u_contrasena'] ? $dato['u_contrasena'] : '' ?>" required>
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
                                <center> <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalEliminar<?php echo $dato['u_id'] ?>">
                                        <i class="fa-solid fa-trash"></i>
                                    </button></center>
                                <div class="modal fade" id="exampleModalEliminar<?php echo $dato['u_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header text-white">
                                                <h6 class="modal-title">ELIMINAR <?php echo $titulopagina; ?> </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form ROLE="FORM" METHOD="POST" ACTION="">
                                                    <input type="hidden" class="form-control" id="accion" name="accion" value="eliminar" />
                                                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo !empty($dato['u_id']) ? $dato['u_id'] : ''; ?>" />
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