<?php
$id = $_GET['id'];

include_once 'header.php';

$busc = 'SELECT * FROM lavado WHERE l_id = ?';
$exec = $pdo->prepare($busc);
$exec->execute(array($id));
$datoveh = $exec->fetch(PDO::FETCH_ASSOC);
$tipolavado = $datoveh['l_tipolavadoFK'];

$buscTL = 'SELECT * FROM tipolavado WHERE t_id = ?';
$exec1 = $pdo->prepare($buscTL);
$exec1->execute(array($tipolavado));
$datoTL = $exec1->fetch(PDO::FETCH_ASSOC);
$vehiculo = $datoTL['t_vehiculoFK'];



if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $accion = $_POST['accion'];
    if ($accion == 'editar') { // activar 1 si se registar y 0 sino se registra
        include_once 'laveditar.php';
    }
}
$titulo = "LAVADO";
?>

<body>
    <?php
    include_once 'menumovil.php';
    ?>

    <!--CONTENIDO-->
    <div class="card">
        <div class="card-header text-white">
            <div class="row">
                <div class="col">
                    <strong>ACTUALIZAR <?php echo $titulo; ?> </strong>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form ROLE="FORM" METHOD="POST" ACTION="">
                <?php
                $informacion = 'SELECT * FROM lavado, vehiculo, empleado, tipolavado WHERE l_vehiculoFK = v_id AND l_empleadoFK = e_id AND l_tipolavadoFK = t_id AND l_id = ' . $id . ';';
                foreach ($pdo->query($informacion) as $dato) {
                ?>
                    <input type="hidden" class="form-control" id="accion" name="accion" value="editar">

                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-car-side"></i></span>
                        <input type="text" class="form-control" name="l_placa" id="l_placa" value="<?php echo $dato['v_placa'] ? $dato['v_placa'] : '' ?>" class="form-control" disabled>
                        <input type="hidden" class="form-control" name="l_vehiculoFK" id="l_vehiculoFK" value="<?php echo $dato['l_vehiculoFK'] ? $dato['l_vehiculoFK'] : '' ?>" class="form-control" required>
                        <input type="hidden" class="form-control" name="l_tipolavadoFK2" id="l_tipolavadoFK2" value="<?php echo $dato['l_tipolavadoFK'] ? $dato['l_tipolavadoFK'] : '' ?>" class="form-control" required>
                        <input type="hidden" class="form-control" name="l_id" id="l_id" value="<?php echo $dato['l_id'] ? $dato['l_id'] : '' ?>" class="form-control" required>

                    </div>
                    <div id="texto" class="form-text"></div>
                    <div class="mb-3">
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-list"></i></span>
                            <select class="form-select form-select-sm" name="l_tipovehiculo" id="l_tipovehiculo" required>
                                <?php
                                $tipovehiculo = 'SELECT * FROM tipolavado, tipovehiculo WHERE t_vehiculoFK = tv_id GROUP BY t_vehiculoFK ORDER BY t_vehiculoFK ASC;';
                                foreach ($pdo->query($tipovehiculo) as $tilv) {
                                    if ($tilv['tv_id'] == $dato['t_vehiculoFK']) {
                                ?>
                                        <option value="<?php echo $tilv['t_vehiculoFK']; ?>" selected>
                                            <?php echo $tilv['tv_vehiculo']; ?>
                                        </option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="<?php echo $tilv['t_vehiculoFK']; ?>">
                                            <?php echo $tilv['tv_vehiculo']; ?>
                                        </option>

                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div id="emailHelp" class="form-text">Colocara por defecto el tipo de lavado registrado, en caso de cambiar otro solo seleccione el tipo de vehiculo, sino escogio uno de la lista deplegable. Actualizara con el tipo de lavado que estaba registrado.</div>
                    </div>


                    <div class="mb-3">
                        <div class="row" id="tipovehiculoseleccionado">
                        </div>
                    </div>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                        <select class="form-select form-select-sm" name="l_empleadoFK" required>
                            <?php
                            $empleado = 'SELECT * FROM empleado WHERE e_estado = "ACTIVO" ORDER BY e_apellidos ASC;';
                            foreach ($pdo->query($empleado) as $empl) {
                                if ($empl['e_id'] == $dato['l_empleadoFK']) {
                            ?>
                                    <option value="<?php echo $empl['e_id']; ?>" selected>
                                        <?php echo $empl['e_nombres'] . "  " . $empl['e_apellidos']; ?>
                                    </option>
                                <?php
                                } else {
                                ?>
                                    <option value="<?php echo $empl['e_id']; ?>">
                                        <?php echo $empl['e_nombres'] . "  " . $empl['e_apellidos']; ?>
                                    </option>

                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                        <input type="date" class="form-control" name="l_fecha" class="form-control" value="<?php echo $dato['l_fecha'] ? $dato['l_fecha'] : ''; ?>" placeholder="Fecha Nacimiento" required>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fa-solid fa-clock"></i></span>
                        <input type="time" class="form-control" name="l_hora" class="form-control" value="<?php echo $dato['l_hora'] ? $dato['l_hora'] : ''; ?>" placeholder="Fecha Nacimiento" required>
                    </div>
                    <span style="font-size: 12px;">* Si la PLACA del Vehiculo no se encuentra registrada en el sistema, debe ingresarla. De lo contrarrio no aplicará la validación para lavarlo.</span>
                    <br><br>
                <?php
                }

                ?>
                <center> <button type="btn" class="btn btn-primary btn-sm">Actualizar &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-regular fa-floppy-disk"></i></button></center>
            </form>
        </div>
    </div>
</body>

<script language="javascript">
    var vehiculo = "<?php echo $vehiculo; ?>";
    var tipolavado = "<?php echo $tipolavado; ?>";
    $(document).ready(function() {
        $("#l_tipovehiculo").change(function() {
            $("#l_tipovehiculo option:selected").each(function() {
                l_tipo = $(this).val();
                tipolavado = "NO";
                $.post("consultartipolavado.php", {
                    l_t: l_tipo,
                    t_l: tipolavado
                }, function(data) {
                    $("#tipovehiculoseleccionado").html(data);
                });

            });

        })
        if (vehiculo == "" || tipolavado == "") {

        } else {
            $.post("consultartipolavado.php", {
                l_t: vehiculo,
                t_l: tipolavado

            }, function(data) {
                $("#tipovehiculoseleccionado").html(data);
            });

        }
    });
</script>

<?php include_once 'footer.php' ?>

</html>